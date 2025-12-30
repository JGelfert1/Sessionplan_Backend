<?php

class Auth
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
            ]);
        }
    }

    public static function login($username, $password)
    {
        self::startSession();
        if ($username === 'admin' && password_verify($password, ADMIN_PASSWORD_HASH)) {
            $_SESSION['authenticated'] = true;
            return true;
        }
        return false;
    }

    public static function logout()
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    public static function isAuthenticated()
    {
        self::startSession();
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }
}
