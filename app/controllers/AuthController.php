<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function showLogin() {
        renderView('auth/login');
    }

    public function handleLogin() {
        $phone = $_POST['phone'] ?? '';
        
        // Basic validation
        if (empty($phone)) {
            // Flash error? For now just reload
            renderView('auth/login', ['error' => 'Phone number is required']);
            return;
        }

        // Generate Mock OTP
        $otp = rand(100000, 999999);
        
        // Store in Session
        $_SESSION['login_otp'] = $otp;
        $_SESSION['login_phone'] = $phone;
        
        // "Send" SMS (Simulated)
        // In a real app, this would call Twilio/SNS
        // passing OTP to view to display it for the user (Dev Mode)
        
        header('Location: ' . BASE_URL . 'verify-otp');
        exit;
    }

    public function showVerify() {
        if (!isset($_SESSION['login_phone'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        // PASS OTP TO VIEW FOR DEMO/TESTING PURPOSES
        $demo_otp = $_SESSION['login_otp'] ?? '';
        
        renderView('auth/verify', ['phone' => $_SESSION['login_phone'], 'demo_otp' => $demo_otp]);
    }

    public function handleVerify() {
        $input_otp = $_POST['otp'] ?? '';
        $digit1 = $_POST['digit1'] ?? ''; 
        // If using multi-input for OTP, likely concatenated in JS or use single input.
        
        // Let's assume single input named 'otp' for simplicity in controller,
        // but View might use multiple boxes.
        
        $input_otp = trim($input_otp);
        $stored_otp = isset($_SESSION['login_otp']) ? trim((string)$_SESSION['login_otp']) : '';

        if ($input_otp !== $stored_otp) {
            renderView('auth/verify', [
                'phone' => $_SESSION['login_phone'] ?? 'Unknown', 
                'error' => 'Invalid OTP code. Please try again.',
                'demo_otp' => $_SESSION['login_otp'] ?? 'expired'
            ]);
            return;
        }

        // OTP Verified
        $phone = $_SESSION['login_phone'];
        $userModel = new User();
        $user = $userModel->findByPhone($phone);

        if (!$user) {
            // Create new user
            if ($userModel->createWithPhone($phone)) {
                $user = $userModel->findByPhone($phone);
            } else {
                die("Error creating user");
            }
        }

        // Login User
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_phone'] = $user['phone']; // fixed to use phone not name
        $_SESSION['user_name'] = $user['name'];

        // Clear OTP
        unset($_SESSION['login_otp']);
        unset($_SESSION['login_phone']);

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
