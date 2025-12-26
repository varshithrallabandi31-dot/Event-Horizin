<?php
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/User.php';

class EventController {

    public function show($id) {
        $eventModel = new Event();
        $event = $eventModel->getById($id);

        if (!$event) {
            http_response_code(404);
            renderView('404'); 
            return;
        }

        require_once __DIR__ . '/../models/RSVP.php';
        require_once __DIR__ . '/../models/User.php';
        $rsvpModel = new RSVP();
        $userModel = new User();
        $rsvpStatus = null;
        $currentUser = null;
        
        if (isset($_SESSION['user_id'])) {
            $rsvpStatus = $rsvpModel->getUserStatus($_SESSION['user_id'], $id);
            $currentUser = $userModel->findById($_SESSION['user_id']); // Fetch user info
        }

        $ticketTiers = $eventModel->getTicketTiers($id);
        $memories = $eventModel->getMemories($id);
        $faqs = $eventModel->getFaqs($id);

        renderView('events/show', [
            'event' => $event, 
            'rsvpStatus' => $rsvpStatus,
            'ticketTiers' => $ticketTiers,
            'memories' => $memories,
            'faqs' => $faqs,
            'currentUser' => $currentUser // Pass to view
        ]);
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        renderView('events/create');
    }

    private function handleCreate() {
        $eventModel = new Event();
        
        // Prepare data from POST
        $data = [
            'organizer_id' => $_SESSION['user_id'],
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'start_time' => ($_POST['date'] ?? '') . ' ' . ($_POST['time'] ?? ''),
            'location_name' => $_POST['location_name'] ?? '',
            'category' => $_POST['category'] ?? 'Social',
            'image_url' => $_POST['image'] ?? '',
            'latitude' => $_POST['latitude'] ?? null,
            'longitude' => $_POST['longitude'] ?? null,
            'requires_approval' => isset($_POST['requires_approval']) ? (int)$_POST['requires_approval'] : 1
        ];

        $tiers = [];
        if (isset($_POST['tiers'])) {
            $tiers = json_decode($_POST['tiers'], true);
            if (!is_array($tiers)) $tiers = [];
        }

        $eventId = $eventModel->create($data, $tiers);

        if ($eventId) {
            // Auto-Generate Standard FAQs
            $faqs = [
                ['question' => 'Is parking available?', 'answer' => 'Yes, we have a dedicated lot.'],
                ['question' => 'Is there a dress code?', 'answer' => 'Smart casual is recommended.'],
                ['question' => 'Can I bring a plus one?', 'answer' => 'Please check the ticket details.'],
                ['question' => 'Is food provided?', 'answer' => 'Yes, generic snacks will be available.'],
                ['question' => 'Is there wheelchair access?', 'answer' => 'Yes, the venue is fully accessible.'],
                ['question' => 'What is the refund policy?', 'answer' => 'Refunds are available up to 24 hours before.'],
                ['question' => 'Are pets allowed?', 'answer' => 'Service animals only.']
            ];

            $db = new Database();
            $conn = $db->getConnection();
            $faqStmt = $conn->prepare("INSERT INTO faqs (event_id, question, answer, created_at) VALUES (?, ?, ?, NOW())");
            
            foreach ($faqs as $faq) {
                $faqStmt->execute([$eventId, $faq['question'], $faq['answer']]);
            }

            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'id' => $eventId, 'redirect' => BASE_URL . 'event/' . $eventId]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Failed to save event']);
        }
        exit;
    }
    

    public function ticket($eventId) {
        $eventModel = new Event();
        $event = $eventModel->getById($eventId);
        
        if(!$event) {
            http_response_code(404);
            renderView('404');
            return;
        }

        renderView('events/ticket', ['event' => $event]);
    }
    public function organizerDashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $eventModel = new Event();
        $db = new Database();
        $conn = $db->getConnection();

        // 1. Fetch Request Stats (Total Pending Requests)
        $query = "SELECT COUNT(*) as count FROM rsvps r 
                  JOIN events e ON r.event_id = e.id 
                  WHERE e.organizer_id = ? AND r.status = 'pending'";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        $pendingRequests = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // 2. Fetch Events created by this user
        $events = $eventModel->getHostedEvents($userId);

        // 3. Fetch All Recent RSVP Activity (for the list)
        $query = "SELECT r.*, e.title as event_title, u.name as user_name, u.email as user_email 
                  FROM rsvps r
                  JOIN events e ON r.event_id = e.id
                  JOIN users u ON r.user_id = u.id
                  WHERE e.organizer_id = ? 
                  ORDER BY r.created_at DESC LIMIT 50";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        $recentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        renderView('events/organizer_dashboard', [
            'hostedEvents' => $events,
            'pendingRequests' => $pendingRequests,
            'rsvps' => $recentRequests
        ]);
    }

    public function analytics($eventId) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $eventModel = new Event();
        $event = $eventModel->getById($eventId);
        
        // Ensure user is the organizer
        if($event['organizer_id'] != $_SESSION['user_id']) {
             die("Unauthorized");
        }

        $db = new Database();
        $conn = $db->getConnection();
        
        // 1. Get Status Counts
        $stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM rsvps WHERE event_id = ? GROUP BY status");
        $stmt->execute([$eventId]);
        $rawStats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Map DB statuses to UI Expected Keys
        $analytics = [
            'total_registrations' => array_sum($rawStats),
            'checked_in' => $rawStats['checked_in'] ?? 0, // Assuming 'checked_in' status exists or is future feature
            'confirmed' => $rawStats['approved'] ?? 0,
            'cancelled' => ($rawStats['rejected'] ?? 0) + ($rawStats['cancelled'] ?? 0),
            'trend' => []
        ];

        // 2. Get Trend (Last 7 Days)
        $stmt = $conn->prepare("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM rsvps 
            WHERE event_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ");
        $stmt->execute([$eventId]);
        $trendData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fill empty days for better chart
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $found = false;
            foreach($trendData as $day) {
                if($day['date'] == $date) {
                     $last7Days[] = ['date' => $date, 'count' => $day['count']];
                     $found = true;
                     break;
                }
            }
            if(!$found) $last7Days[] = ['date' => $date, 'count' => 0];
        }
        $analytics['trend'] = $last7Days;

        renderView('events/analytics', [
            'event' => $event,
            'analytics' => $analytics // Pass the variable view expects
        ]);
    }

    public function submitRSVP() {
        if (!isset($_SESSION['user_id'])) {
             // For form submission, we should redirect to login
             $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'];
             header('Location: ' . BASE_URL . 'login');
             exit;
        }

        $eventId = $_POST['event_id'] ?? 0;
        $interest = $_POST['interest'] ?? '';
        $name = $_POST['name'] ?? ''; 
        $email = $_POST['email'] ?? ''; 
        
        $userId = $_SESSION['user_id'];
        
        // Update User Info if provided (Smart Update)
        if (!empty($name) || !empty($email)) {
            $userModel = new User();
            // Fetch current to avoid overwriting with empty if not provided? 
            // Actually updateBasicInfo handles raw strings. 
            // If name is empty here but user exists, we shouldn't wipe it.
            // But frontend only sends if it was missing. 
            // Let's rely on updateBasicInfo. Ideally we check if not empty.
            
            $userModel->updateBasicInfo($userId, $name, !empty($email) ? $email : null);
        }

        $db = new Database();
        $conn = $db->getConnection();

        // Check exists
        $stmt = $conn->prepare("SELECT id FROM rsvps WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$eventId, $userId]);
        if($stmt->rowCount() > 0) {
            // Already requested, just redirect to success or show message
            // Ideally should check status, but for now redirect to success page
            header('Location: ' . BASE_URL . 'rsvp/success');
            exit;
        }
        
        // Check if event requires approval
        $eventModel = new Event();
        $event = $eventModel->getById($eventId);
        $status = 'pending';
        
        if ($event && isset($event['requires_approval']) && $event['requires_approval'] == 0) {
            $status = 'approved';
        }

        // Store interest
        $answers = json_encode(['interest' => $interest]);

        $stmt = $conn->prepare("INSERT INTO rsvps (event_id, user_id, answers, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        if($stmt->execute([$eventId, $userId, $answers, $status])) {
            // Send Email if Approved
            if ($status === 'approved') {
                $this->sendApprovalEmail($userId, $eventId);
            }
            header('Location: ' . BASE_URL . 'rsvp/success');
        } else {
             // Fallback
             $stmt = $conn->prepare("INSERT INTO rsvps (event_id, user_id, interest, status, created_at) VALUES (?, ?, ?, ?, NOW())");
             if($stmt->execute([$eventId, $userId, $interest, $status])) {
                 // Send Email if Approved
                 if ($status === 'approved') {
                     $this->sendApprovalEmail($userId, $eventId);
                 }
                 header('Location: ' . BASE_URL . 'rsvp/success');
             } else {
                 die("Database Error");
             }
        }
        exit;
    }

    private function sendApprovalEmail($userId, $eventId) {
        require_once __DIR__ . '/../libs/MailHelper.php';
        require_once __DIR__ . '/../libs/EmailTemplates.php';
        require_once __DIR__ . '/../libs/KitHelper.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Event.php';

        $userModel = new User();
        $eventModel = new Event();
        
        $user = $userModel->findById($userId);
        $event = $eventModel->getById($eventId);
        
        if ($user && $event && !empty($user['email'])) {
             // Generate PDF
             $pdfContent = KitHelper::generate($event, $user);
             $pdfName = 'EventKit_' . preg_replace('/[^a-zA-Z0-9]/', '_', $event['title']) . '.pdf';
             
             // Generate Email Body
             $eventLink = BASE_URL . 'event/' . $eventId;
             $kitLink = BASE_URL . 'event/' . $eventId . '/download-kit';
             $body = EmailTemplates::rsvpConfirmation($user['name'], $event['title'], $eventLink, $kitLink);
             
             // Send
             MailHelper::sendWithAttachment($user['email'], "You're In! - " . $event['title'], $body, $pdfContent, $pdfName);
        }
    }

    public function rsvpSuccess() {
        renderView('events/rsvp_success');
    }

    public function approveRSVP($rsvpId) {
        $this->updateRSVPStatus($rsvpId, 'approved');
    }

    public function rejectRSVP($rsvpId) {
         $this->updateRSVPStatus($rsvpId, 'rejected');
    }

    private function updateRSVPStatus($rsvpId, $status) {
         if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
         }
         
         $db = new Database();
         $conn = $db->getConnection();
         
         // Verify ownership
         $stmt = $conn->prepare("SELECT r.id FROM rsvps r JOIN events e ON r.event_id = e.id WHERE r.id = ? AND e.organizer_id = ?");
         $stmt->execute([$rsvpId, $_SESSION['user_id']]);
         if($stmt->rowCount() == 0) {
             die("Unauthorized");
         }

         $stmt = $conn->prepare("UPDATE rsvps SET status = ? WHERE id = ?");
         $stmt->execute([$status, $rsvpId]);
         
         header('Location: ' . $_SERVER['HTTP_REFERER']);
         exit;
    }

    public function chat($eventId) {
        if (!isset($_SESSION['user_id'])) {
             // Ensure clean output
             if (ob_get_length()) ob_clean();
             header('Content-Type: application/json');
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        
        // Fetch messages with User info
        $stmt = $conn->prepare("
            SELECT m.*, u.name as user_name, m.created_at 
            FROM messages m 
            JOIN users u ON m.user_id = u.id 
            WHERE m.event_id = ? 
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$eventId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $formatted = [];
        foreach($messages as $msg) {
            $formatted[] = [
                'user' => $msg['user_name'],
                // Decode potentially double-encoded chars or just simple escaping
                'content' => htmlspecialchars($msg['content']), 
                'time' => date('h:i A', strtotime($msg['created_at'])),
                'is_me' => ($msg['user_id'] == $_SESSION['user_id'])
            ];
        }

        // Ensure clean output before JSON
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        echo json_encode(['status' => 'success', 'messages' => $formatted]);
        exit;
    }

    public function sendMessage($eventId) {
        if (!isset($_SESSION['user_id'])) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $userId = $_SESSION['user_id'];
        $message = trim($_POST['message'] ?? '');
        
        if (empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Message empty']);
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO messages (event_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        if($stmt->execute([$eventId, $userId, $message])) {
             // Return the message structure so JS can append it immediately
             $userModel = new User();
             $user = $userModel->findById($userId);
             echo json_encode([
                 'status' => 'success',
                 'message' => [
                     'user' => $user['name'],
                     'content' => htmlspecialchars($message),
                     'time' => date('h:i A'),
                     'is_me' => true
                 ]
             ]);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Database error']);
        }
        exit;
    }

    public function getPolls($eventId) {
        $userId = $_SESSION['user_id'] ?? null;
        require_once __DIR__ . '/../models/Poll.php';
        $pollModel = new Poll();
        $polls = $pollModel->getByEventId($eventId, $userId);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'polls' => $polls]);
        exit;
    }

    public function createPoll($eventId) {
        if (!isset($_SESSION['user_id'])) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $userId = $_SESSION['user_id'];
        $question = $_POST['question'] ?? '';
        $options = $_POST['options'] ?? [];

        if (empty($question) || count($options) < 2) {
             echo json_encode(['status' => 'error', 'message' => 'Invalid poll data']);
             exit;
        }

        require_once __DIR__ . '/../models/Poll.php';
        $pollModel = new Poll();
        $result = $pollModel->create($eventId, $userId, $question, $options);
        
        if (is_numeric($result)) {
             echo json_encode(['status' => 'success']);
        } else {
             // Result contains the error message
             echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $result]);
        }
        exit;
    }

    public function votePoll($eventId) {
        if (!isset($_SESSION['user_id'])) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $userId = $_SESSION['user_id'];
        $pollId = $_POST['poll_id'] ?? 0;
        $optionId = $_POST['option_id'] ?? 0;

        require_once __DIR__ . '/../models/Poll.php';
        $pollModel = new Poll();
        if ($pollModel->vote($pollId, $optionId, $userId)) {
             echo json_encode(['status' => 'success']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Already voted or error']);
        }
        exit;
    }

    public function referFriend($eventId) {
        if (!isset($_SESSION['user_id'])) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
             exit;
        }

        $friendName = $_POST['friend_name'] ?? '';
        $friendEmail = $_POST['friend_email'] ?? '';

        if (empty($friendName) || empty($friendEmail) || !filter_var($friendEmail, FILTER_VALIDATE_EMAIL)) {
             echo json_encode(['status' => 'error', 'message' => 'Invalid details']);
             exit;
        }

        $eventModel = new Event();
        $event = $eventModel->getById($eventId);
        
        if (!$event) {
            echo json_encode(['status' => 'error', 'message' => 'Event not found']);
            exit;
        }

        require_once __DIR__ . '/../libs/MailHelper.php';
        require_once __DIR__ . '/../libs/EmailTemplates.php';

        $senderName = $_SESSION['user_name'];
        $eventLink = BASE_URL . 'event/' . $eventId;
        $eventDate = date('l, M j, Y @ g:i A', strtotime($event['start_time']));
        
        $emailContent = EmailTemplates::referralInvite(
            $senderName, 
            $event['title'], 
            $eventDate, 
            $event['location_name'], 
            $eventLink
        );

        if (MailHelper::send($friendEmail, "Invitation: " . $event['title'], $emailContent)) {
             echo json_encode(['status' => 'success']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
        }
        exit;
    }
    
    public function postMemory($eventId) {
         if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
         }
         
         $imageUrl = $_POST['image_url'] ?? '';
         $caption = $_POST['caption'] ?? '';
         
         if (!empty($imageUrl)) {
             $eventModel = new Event();
             $eventModel->addMemory($eventId, $_SESSION['user_id'], $imageUrl, $caption);
         }
         
         header('Location: ' . $_SERVER['HTTP_REFERER']);
         exit;
    }

    public function sendBulkMail() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('Location: ' . BASE_URL . 'organizer/dashboard');
             exit;
        }

        $organizerId = $_SESSION['user_id'];
        $organizerName = $_SESSION['user_name'];
        $subject = $_POST['subject'] ?? 'Update from Organizer';
        $message = $_POST['message'] ?? '';
        
        if (empty($message)) {
             header('Location: ' . BASE_URL . 'organizer/dashboard?error=empty_message');
             exit;
        }

        require_once __DIR__ . '/../libs/MailHelper.php';
        require_once __DIR__ . '/../libs/EmailTemplates.php';

        $db = new Database();
        $conn = $db->getConnection();
        
        // Fetch all approved/pending rsvps for this organizer's events
        // We group by email to avoid sending duplicate emails if a user registered for multiple events
        // But we might want to mention which event it is for?
        // For "All Events", let's keep it general or comma separate titles? 
        // Simpler approach: Send one email per user, saying "regarding your events with [Organizer]"
        
        $query = "SELECT DISTINCT u.email, u.name, GROUP_CONCAT(DISTINCT e.title SEPARATOR ', ') as event_titles
                  FROM rsvps r
                  JOIN events e ON r.event_id = e.id
                  JOIN users u ON r.user_id = u.id
                  WHERE e.organizer_id = ?
                  GROUP BY u.email";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$organizerId]);
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = 0;
        foreach ($recipients as $recipient) {
            $emailContent = EmailTemplates::formalUpdate(
                $organizerName,
                $subject,
                $message,
                $recipient['event_titles'] // "Tech Meetup, Music Fest"
            );

            if(MailHelper::send($recipient['email'], $subject, $emailContent)) {
                $count++;
            }
        }

        header('Location: ' . BASE_URL . 'organizer/dashboard?success=mail_sent&count=' . $count);
        exit;
    }
}
