<?php


namespace Controllers\User;

use Models\User\UserManager;

class UserController
{

    private $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }


    public function acceptCookie()
    {
        setcookie('accept_cookie', true, time() + 365 * 24 * 3600, '/', null, false, true);
        isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])
            ? header('Location: ' . $_SERVER['HTTP_REFERER'])
            : header('Location: login');
    }

    public function authUser()
    {
        require_once './errors/errors.php';

        $errors = [
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'password' => '',
            'general' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_SERVER['REQUEST_URI'] === '/auth-user/register') {
                $input = filter_input_array(INPUT_POST, [
                    'firstname' => FILTER_SANITIZE_SPECIAL_CHARS,
                    'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
                    'email' => FILTER_SANITIZE_EMAIL,
                ]);
                $firstname = $input['firstname'] ?? '';
                $lastname = $input['lastname'] ?? '';
            } elseif ($_SERVER['REQUEST_URI'] === '/auth-user/login') {
                $input = filter_input_array(INPUT_POST, [
                    'email' => FILTER_SANITIZE_EMAIL,
                ]);
            }
            $email = $input['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($_SERVER['REQUEST_URI'] === '/auth-user/register') {
                if (!$firstname) {
                    $errors['firstname'] = ERROR_REQUIRED;
                } elseif (mb_strlen($firstname) < 2) {
                    $errors['firstname'] = ERROR_TOO_SHORT;
                }

                if (!$lastname) {
                    $errors['lastname'] = ERROR_REQUIRED;
                } elseif (mb_strlen($lastname) < 2) {
                    $errors['lastname'] = ERROR_TOO_SHORT;
                }
            }

            if (!$email) {
                $errors['email'] = ERROR_REQUIRED;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = ERROR_INVALID_EMAIL;
            }

            if (!$password) {
                $errors['password'] = ERROR_REQUIRED;
            } elseif (mb_strlen($password) < 6) {
                $errors['password'] = ERROR_TOO_SHORT_EMAIL;
            }

            if (!count(array_filter($errors, fn ($e) => $e !== ''))) {
                if ($_SERVER['REQUEST_URI'] === '/auth-user/register') {
                    $checkEmail = $this->userManager->checkEmail($email);
                    if ($checkEmail > 0) {
                        $errors['general'] = ERROR_EXIST_EMAIL;
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
                        $this->userManager->register($firstname, $lastname, $email, $hashedPassword);
                        header('Location: login');
                    }
                } elseif ($_SERVER['REQUEST_URI'] === '/auth-user/login') {
                    $currentUser = $this->userManager->login($email);

                    if ($currentUser && password_verify($password, $currentUser->password)) {
                        $sessionId = bin2hex(random_bytes(32));
                        $signature = hash_hmac('sha256', $sessionId, 'J@NcRfUjXn2r5u7x!A%D*G-KaPdSgVkY');
                        $this->userManager->sessionCreateOne($currentUser, $sessionId);
                        // \print_r($session);
                        setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, "", "", false, true);
                        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);
                        header('Location: profile');
                    } else {
                        $errors['general'] = ERROR_INVALID_EMAIL_PASSWORD;
                    }
                }
            }
        }
        require_once './Views/auth/form-auth.php';
    }



    public function isLoggedIn()
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $signature = $_COOKIE['signature'] ?? '';
        if ($sessionId && $signature) {
            $hash = hash_hmac('sha256', $sessionId, 'J@NcRfUjXn2r5u7x!A%D*G-KaPdSgVkY');
            if (hash_equals($hash, $signature)) {
                $user = $this->userManager->sessionUser($sessionId);
            }
        }
        return $user ?? false;
    }


    // a optimiser
    public function profile()
    {
        $currentUser = $this->isLoggedIn();
        require_once './Views/auth/profile.php';
    }


    public function logout()
    {
        $sessionId = $_COOKIE['session'] ?? '';
        if ($sessionId) {
            $this->userManager->logout($sessionId);
            setcookie('session', '', time() - 1);
            setcookie('signature', '');
        }
        header('Location: login');
    }
}
