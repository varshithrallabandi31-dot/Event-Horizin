<?php
require_once __DIR__ . '/../models/User.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AuthController {

    public function showLogin() {
        renderView('auth/login');
    }

    public function handleLogin() {
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        
        $isEmail = !empty($email);
        $identity = $isEmail ? $email : $phone;

        // Basic validation
        if (empty($identity)) {
            renderView('auth/login', ['error' => 'Phone number or Email is required']);
            return;
        }

        // Generate Mock OTP
        $otp = rand(100000, 999999);
        
        // Store in Session
        $_SESSION['login_otp'] = $otp;
        if ($isEmail) {
            $_SESSION['login_email'] = $email;
            unset($_SESSION['login_phone']); // clear previous
            
            // Send Email OTP
            $this->sendEmailOTP($email, $otp);
            
        } else {
            $_SESSION['login_phone'] = $phone;
            unset($_SESSION['login_email']); // clear previous
            
            // "Send" SMS (Simulated)
        }
        
        header('Location: ' . BASE_URL . 'verify-otp');
        exit;
    }

    private function sendEmailOTP($email, $otp) {
        if (!Config::isEmailConfigured()) {
            // Log for dev if no config
            error_log("SMTP Not Configured. OTP for $email is $otp");
            return;
        }

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = Config::get('SMTP_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = Config::get('SMTP_USER');
            $mail->Password   = Config::get('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = Config::get('SMTP_PORT', 587);

            // Recipients
            $mail->setFrom(Config::get('SMTP_USER'), 'Event Horizons');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Login Code';
            $mail->Body    = "Your login verification code is: <b>$otp</b>";
            $mail->AltBody = "Your login verification code is: $otp";

            $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    public function showVerify() {
        if (!isset($_SESSION['login_phone']) && !isset($_SESSION['login_email'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        // PASS OTP TO VIEW FOR DEMO/TESTING PURPOSES
        $demo_otp = $_SESSION['login_otp'] ?? '';
        $identity = $_SESSION['login_email'] ?? $_SESSION['login_phone'];
        $isEmail = isset($_SESSION['login_email']);
        
        renderView('auth/verify', [
            'phone' => $identity, 
            'is_email' => $isEmail,
            'demo_otp' => $demo_otp 
        ]);
    }

    public function handleVerify() {
        $input_otp = $_POST['otp'] ?? '';
        $input_otp = trim($input_otp);
        $stored_otp = isset($_SESSION['login_otp']) ? trim((string)$_SESSION['login_otp']) : '';

        $identity = $_SESSION['login_email'] ?? $_SESSION['login_phone'] ?? '';
        $isEmail = isset($_SESSION['login_email']);

        if ($input_otp !== $stored_otp) {
            renderView('auth/verify', [
                'phone' => $identity, 
                'is_email' => $isEmail,
                'error' => 'Invalid OTP code. Please try again.',
                'demo_otp' => $_SESSION['login_otp'] ?? 'expired'
            ]);
            return;
        }

        // OTP Verified
        $userModel = new User();
        $user = false;

        if ($isEmail) {
            $user = $userModel->findByEmail($identity);
            if (!$user) {
                // Create user with Email
                if ($userModel->createWithEmail($identity)) {
                    $user = $userModel->findByEmail($identity);
                }
            }
        } else {
            $user = $userModel->findByPhone($identity);
            if (!$user) {
                // Create user with Phone
                if ($userModel->createWithPhone($identity)) {
                    $user = $userModel->findByPhone($identity);
                }
            }
        }
        
        if (!$user) {
             die("Error creating user/logging in");
        }

        // Login User
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_phone'] = $user['phone']; 
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];

        // Clear OTP
        unset($_SESSION['login_otp']);
        unset($_SESSION['login_phone']);
        unset($_SESSION['login_email']);

        header('Location: ' . BASE_URL);
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}
?>
