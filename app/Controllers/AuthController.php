<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;
    protected $validation;
    protected $email;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
        helper(['form', 'url', 'session']);
    }

    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[student,associate,itso_personnel]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $profilePicture = $this->request->getFile('profile_picture');
        $profilePictureName = null;

        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            if ($profilePicture->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('error', 'Profile picture must be less than 2MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($profilePicture->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Only JPG, PNG, and GIF images are allowed');
            }

            $profilePictureName = $this->processProfilePicture($profilePicture);
            
            if (!$profilePictureName) {
                return redirect()->back()->withInput()->with('error', 'Failed to process profile picture');
            }
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'department' => $this->request->getPost('department'),
            'contact_number' => $this->request->getPost('contact_number'),
            'profile_picture' => $profilePictureName,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($userData)) {
            $this->sendRegistrationEmail($userData);
            
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }

    public function login()
    {
        if ($this->session->get('logged_in')) {
            $role = $this->session->get('role');
            return $this->redirectToDashboard($role);
        }
        return view('auth/login');
    }

    public function processLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)
                               ->orWhere('email', $username)
                               ->first();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active'] == 1) {
                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role'],
                    'profile_picture' => $user['profile_picture'],
                    'logged_in' => true
                ];
                $this->session->set($sessionData);

                $this->sendLoginEmail($user);

                return $this->redirectToDashboard($user['role'], $user['first_name']);
            } else {
                return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact administrator.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid username/email or password.');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if ($user) {

            $token = $this->userModel->createPasswordResetToken($email);
            
            if ($token) {

                $this->sendPasswordResetEmail($user, $token);
            }
            
            return redirect()->back()->with('success', 'If the email exists, reset instructions have been sent.');
        } else {
            return redirect()->back()->with('success', 'If the email exists, reset instructions have been sent.');
        }
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid reset token.');
        }

        $user = $this->userModel->verifyResetToken($token);
        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $rules = [
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'token' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyResetToken($token);
        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        if ($this->userModel->updatePassword($user['id'], $password)) {

            $this->sendPasswordChangedEmail($user);
            
            return redirect()->to('/login')->with('success', 'Password reset successfully. Please login with your new password.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to reset password. Please try again.');
        }
    }

    private function sendPasswordResetEmail($user, $token)
    {
        $resetLink = base_url("reset-password/{$token}");
        $fullName = $user['first_name'] . ' ' . $user['last_name'];
        $currentTime = date('F j, Y \a\t g:i A');
        $expiryTime = date('F j, Y \a\t g:i A', strtotime('+1 hour'));
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #D4AF37; }
                .btn-reset { display: inline-block; background: #D4AF37; color: #0C3B2E; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 15px 0; }
                .security-note { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>ITSO Equipment Management System</h2>
                </div>
                <div class='content'>
                    <h3>Password Reset Request</h3>
                    <p>Hello {$user['first_name']},</p>
                    <p>We received a request to reset your password for your ITSO EMS account. Click the button below to reset your password:</p>
                    
                    <div style='text-align: center;'>
                        <a href='{$resetLink}' class='btn-reset'>Reset Your Password</a>
                    </div>
                    
                    <div class='info-box'>
                        <p><strong>Request Details:</strong></p>
                        <p><strong>Name:</strong> {$fullName}</p>
                        <p><strong>Username:</strong> {$user['username']}</p>
                        <p><strong>Request Time:</strong> {$currentTime}</p>
                        <p><strong>Link Expires:</strong> {$expiryTime}</p>
                    </div>
                    
                    <div class='security-note'>
                        <p><strong>Security Note:</strong> If you didn't request this password reset, please ignore this email. Your account security is important to us.</p>
                    </div>
                    
                    <p>Alternatively, you can copy and paste this link in your browser:</p>
                    <p style='word-break: break-all; background: #f0f0f0; padding: 10px; border-radius: 5px; font-size: 12px;'>{$resetLink}</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        $this->email->setMailType('html');
        
        $this->email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
        $this->email->setTo($user['email']);
        $this->email->setSubject('Password Reset Request - ITSO EMS');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', "Password reset email sent to {$user['email']}");
            return true;
        } else {
            log_message('error', "Failed to send password reset email to {$user['email']}");
            return false;
        }
        
        $this->email->clear();
    }

    private function sendPasswordChangedEmail($user)
    {
        $currentTime = date('F j, Y \a\t g:i A');
        $fullName = $user['first_name'] . ' ' . $user['last_name'];
        $ipAddress = $this->request->getIPAddress();
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745; }
                .security-note { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>ITSO Equipment Management System</h2>
                </div>
                <div class='content'>
                    <h3>Password Changed Successfully</h3>
                    <p>Hello {$user['first_name']},</p>
                    <p>Your password has been successfully changed for your ITSO EMS account.</p>
                    
                    <div class='info-box'>
                        <p><strong>Change Details:</strong></p>
                        <p><strong>Name:</strong> {$fullName}</p>
                        <p><strong>Username:</strong> {$user['username']}</p>
                        <p><strong>Change Time:</strong> {$currentTime}</p>
                        <p><strong>IP Address:</strong> {$ipAddress}</p>
                    </div>
                    
                    <div class='security-note'>
                        <p><strong>Security Alert:</strong> If you didn't make this change, please contact our support team immediately at itso-support@fit.edu.ph</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        $this->email->setMailType('html');
        
        $this->email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
        $this->email->setTo($user['email']);
        $this->email->setSubject('Password Changed - ITSO EMS');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', "Password changed notification sent to {$user['email']}");
            return true;
        } else {
            log_message('error', "Failed to send password changed notification to {$user['email']}");
            return false;
        }
        
        $this->email->clear();
    }

    private function redirectToDashboard($role, $firstName = null)
    {
        $welcomeMessage = $firstName ? 'Welcome back, ' . $firstName . '!' : 'Welcome back!';
        
        switch ($role) {
            case 'itso_personnel':
                return redirect()->to('/itso-dashboard')->with('success', $welcomeMessage);
                
            case 'associate':
                return redirect()->to('/associate/dashboard')->with('success', $welcomeMessage);
                
            case 'student':
                return redirect()->to('/student/dashboard')->with('success', $welcomeMessage);
                
            default:
                return redirect()->to('/dashboard')->with('success', $welcomeMessage);
        }
    }

    public function checkSession()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = $this->session->get('role');
        return $this->redirectToDashboard($role);
    }

    public function getCurrentUser()
    {
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Not logged in']);
        }

        $userData = [
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'email' => $this->session->get('email'),
            'first_name' => $this->session->get('first_name'),
            'last_name' => $this->session->get('last_name'),
            'role' => $this->session->get('role'),
            'profile_picture' => $this->session->get('profile_picture')
        ];

        return $this->response->setJSON($userData);
    }

    public function updateProfile()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $profilePicture = $this->request->getFile('profile_picture');
        $profilePictureName = $user['profile_picture'];

        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            if ($profilePicture->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('error', 'Profile picture must be less than 2MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($profilePicture->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Only JPG, PNG, and GIF images are allowed');
            }

            if ($profilePictureName && file_exists(ROOTPATH . 'public/uploads/profiles/' . $profilePictureName)) {
                unlink(ROOTPATH . 'public/uploads/profiles/' . $profilePictureName);
                
                $thumbnailPath = ROOTPATH . 'public/uploads/profiles/thumbnails/' . $profilePictureName;
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            $profilePictureName = $this->processProfilePicture($profilePicture);
            
            if (!$profilePictureName) {
                return redirect()->back()->withInput()->with('error', 'Failed to process profile picture');
            }
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
            'contact_number' => $this->request->getPost('contact_number'),
            'profile_picture' => $profilePictureName,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($userId, $userData)) {

            $sessionData = [
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'profile_picture' => $userData['profile_picture']
            ];
            $this->session->set($sessionData);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile.');
        }
    }

    public function changePassword()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
        }

        $userData = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->update($userId, $userData)) {
            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to change password.');
        }
    }

    private function processProfilePicture($profilePicture)
    {
        try {
            $image = service('image');

            $newName = $profilePicture->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/profiles/';
            $thumbnailPath = ROOTPATH . 'public/uploads/profiles/thumbnails/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }

            $image->withFile($profilePicture->getTempName())
                  ->fit(300, 300, 'center') 
                  ->save($uploadPath . $newName, 85); 

            $image->withFile($profilePicture->getTempName())
                  ->fit(100, 100, 'center')
                  ->save($thumbnailPath . $newName, 85);
            
            return $newName;
            
        } catch (\Exception $e) {
            log_message('error', 'Image processing error: ' . $e->getMessage());
            return false;
        }
    }

    public function getProfilePicture($filename = null, $thumbnail = false)
    {
        $defaultImage = 'profile_default-removebg-preview.png';
        
        if (!$filename) {
            $filename = $defaultImage;
        }
        
        $path = $thumbnail ? 
            ROOTPATH . 'public/uploads/profiles/thumbnails/' . $filename :
            ROOTPATH . 'public/uploads/profiles/' . $filename;
        
        if (!file_exists($path)) {
            $filename = $defaultImage;
            $path = ROOTPATH . 'public/img/' . $filename;
        }
        
        return $this->response->setContentType(mime_content_type($path))
                             ->setBody(file_get_contents($path));
    }

    private function sendRegistrationEmail($userData)
    {
        $currentTime = date('F j, Y \a\t g:i A');
        $fullName = $userData['first_name'] . ' ' . $userData['last_name'];
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #D4AF37; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>ITSO Equipment Management System</h2>
                </div>
                <div class='content'>
                    <h3>Welcome to ITSO EMS, {$userData['first_name']}!</h3>
                    <p>Your account has been successfully created with the following details:</p>
                    
                    <div class='info-box'>
                        <p><strong>Name:</strong> {$fullName}</p>
                        <p><strong>Username:</strong> {$userData['username']}</p>
                        <p><strong>Email:</strong> {$userData['email']}</p>
                        <p><strong>Role:</strong> " . ucfirst(str_replace('_', ' ', $userData['role'])) . "</p>
                        <p><strong>Department:</strong> " . strtoupper($userData['department']) . "</p>
                        <p><strong>Registration Time:</strong> {$currentTime}</p>
                    </div>
                    
                    <p>You can now login to the system and start using the equipment management features.</p>
                    <p>If you have any questions, please contact our support team at itso-support@fit.edu.ph</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        $this->email->setMailType('html');
        
        $this->email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
        $this->email->setTo($userData['email']);
        $this->email->setSubject('Account Registration Successful - ITSO EMS');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', "Registration email sent to {$userData['email']}");
        } else {
            log_message('error', "Failed to send registration email to {$userData['email']}");
        }
        
        $this->email->clear();
    }

    private function sendLoginEmail($user)
    {
        try {
            if (empty($user['email'])) {
                log_message('error', "Cannot send login email: User email is empty for user ID {$user['id']}, role: {$user['role']}");
                return false;
            }

            $email = \Config\Services::email();
            $email->clear();

            $currentTime = date('F j, Y \a\t g:i A');
            $fullName = $user['first_name'] . ' ' . $user['last_name'];
            $ipAddress = $this->request->getIPAddress();
            
            $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                    .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                    .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #D4AF37; }
                    .security-note { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 15px 0; border-radius: 5px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>ITSO Equipment Management System</h2>
                    </div>
                    <div class='content'>
                        <h3>Login Activity Notification</h3>
                        <p>Hello {$user['first_name']},</p>
                        <p>Your account was successfully accessed with the following details:</p>
                        
                        <div class='info-box'>
                            <p><strong>Name:</strong> {$fullName}</p>
                            <p><strong>Username:</strong> {$user['username']}</p>
                            <p><strong>Login Time:</strong> {$currentTime}</p>
                            <p><strong>IP Address:</strong> {$ipAddress}</p>
                            <p><strong>Role:</strong> " . ucfirst(str_replace('_', ' ', $user['role'])) . "</p>
                        </div>
                        
                        <div class='security-note'>
                            <p><strong>Security Note:</strong> If this login was not authorized by you, please contact the ITSO support team immediately at itso-support@fit.edu.ph</p>
                        </div>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";

            $email->setMailType('html');
            $email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
            $email->setTo($user['email']);
            $email->setSubject('Login Successful - ITSO EMS');
            $email->setMessage($message);

            if ($email->send()) {
                log_message('info', "Login notification email sent successfully to {$user['email']} (Role: {$user['role']}, User ID: {$user['id']})");
                return true;
            } else {
                $error = $email->printDebugger(['headers', 'subject', 'body']);
                log_message('error', "Failed to send login notification email to {$user['email']} (Role: {$user['role']}, User ID: {$user['id']}). Error: " . $error);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Exception while sending login email to {$user['email']} (Role: {$user['role']}, User ID: {$user['id']}): " . $e->getMessage());
            return false;
        } finally {
            if (isset($email)) {
                $email->clear();
            }
        }
    }

    public function sendBorrowEmail($userData, $equipmentData, $borrowDetails)
    {
        $fullName = $userData['first_name'] . ' ' . $userData['last_name'];
        $borrowDate = date('F j, Y \a\t g:i A', strtotime($borrowDetails['borrow_date']));
        $expectedReturnDate = date('F j, Y \a\t g:i A', strtotime($borrowDetails['expected_return_date']));
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #D4AF37; }
                .success-message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>ITSO Equipment Management System</h2>
                </div>
                <div class='content'>
                    <div class='success-message'>
                        <h3>✅ Equipment Borrowed Successfully!</h3>
                    </div>
                    
                    <p>Hello <strong>{$userData['first_name']}</strong>,</p>
                    <p>You have successfully borrowed equipment from ITSO. Here are the details:</p>
                    
                    <div class='info-box'>
                        <h4>Equipment Details:</h4>
                        <p><strong>Equipment Name:</strong> {$equipmentData['name']}</p>
                        <p><strong>Equipment Code:</strong> {$equipmentData['equipment_code']}</p>
                        <p><strong>Category:</strong> {$equipmentData['category']}</p>
                    </div>
                    
                    <div class='info-box'>
                        <h4>Borrow Details:</h4>
                        <p><strong>Borrowed On:</strong> {$borrowDate}</p>
                        <p><strong>Expected Return:</strong> {$expectedReturnDate}</p>
                        <p><strong>Purpose:</strong> {$borrowDetails['purpose']}</p>
                        <p><strong>Borrow ID:</strong> #{$borrowDetails['borrow_id']}</p>
                    </div>
                    
                    <div class='info-box'>
                        <h4>Important Reminders:</h4>
                        <ul>
                            <li>Please handle the equipment with care</li>
                            <li>Return the equipment on or before the expected return date</li>
                            <li>Any damages must be reported immediately</li>
                            <li>Late returns may affect your borrowing privileges</li>
                        </ul>
                    </div>
                    
                    <p>If you have any questions or need to modify your borrow request, please contact ITSO support.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                    <p>Contact: itso-support@fit.edu.ph</p>
                </div>
            </div>
        </body>
        </html>";

        $this->email->setMailType('html');
        
        $this->email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
        $this->email->setTo($userData['email']);
        $this->email->setSubject('Equipment Borrowed Successfully - ITSO EMS');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', "Borrow notification email sent to {$userData['email']}");
            return true;
        } else {
            log_message('error', "Failed to send borrow notification email to {$userData['email']}");
            return false;
        }
    }

    public function sendReturnEmail($userData, $equipmentData, $returnDetails)
    {
        $fullName = $userData['first_name'] . ' ' . $userData['last_name'];
        $returnDate = date('F j, Y \a\t g:i A', strtotime($returnDetails['returned_at']));
        $borrowDate = date('F j, Y', strtotime($returnDetails['borrow_date']));
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0C3B2E; color: #D4AF37; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745; }
                .success-message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 15px 0; }
                .thank-you { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>ITSO Equipment Management System</h2>
                </div>
                <div class='content'>
                    <div class='success-message'>
                        <h3>✅ Equipment Returned Successfully!</h3>
                    </div>
                    
                    <p>Hello <strong>{$userData['first_name']}</strong>,</p>
                    <p>Thank you for returning the equipment to ITSO. Here's a summary of your return:</p>
                    
                    <div class='info-box'>
                        <h4>Equipment Details:</h4>
                        <p><strong>Equipment Name:</strong> {$equipmentData['name']}</p>
                        <p><strong>Equipment Code:</strong> {$equipmentData['equipment_code']}</p>
                        <p><strong>Category:</strong> {$equipmentData['category']}</p>
                    </div>
                    
                    <div class='info-box'>
                        <h4>Borrow & Return Details:</h4>
                        <p><strong>Borrowed On:</strong> {$borrowDate}</p>
                        <p><strong>Returned On:</strong> {$returnDate}</p>
                        <p><strong>Borrow ID:</strong> #{$returnDetails['borrow_id']}</p>
                    </div>
                    
                    <div class='thank-you'>
                        <h4>Thank You! 🙏</h4>
                        <p>Your responsible handling and timely return of equipment helps maintain ITSO's resources for all students and faculty.</p>
                    </div>
                    
                    <p>We appreciate you following the equipment borrowing guidelines. You're welcome to borrow again anytime!</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " ITSO Equipment Management System. All rights reserved.</p>
                    <p>Contact: itso-support@fit.edu.ph</p>
                </div>
            </div>
        </body>
        </html>";

        $this->email->setMailType('html');
        
        $this->email->setFrom('noreply@gmail.com', 'ITSO Equipment Management System');
        $this->email->setTo($userData['email']);
        $this->email->setSubject('Equipment Returned Successfully - ITSO EMS');
        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', "Return notification email sent to {$userData['email']}");
            return true;
        } else {
            log_message('error', "Failed to send return notification email to {$userData['email']}");
            return false;
        }
    }
}