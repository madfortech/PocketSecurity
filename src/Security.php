<?php
namespace PocketSecurity;

class Security {
    /**
     * Sanitize output to prevent XSS.
     * @param string $data
     * @return string
     */
    public static function sanitizeOutput($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate and verify CSRF tokens.
     */
    public static function csrfGenerate() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function csrfVerify($token) {
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            die("CSRF validation failed.");
        }
    }

    /**
     * Basic SQL Injection protection (use with prepared statements).
     * @param string $input
     * @return string
     */
    public static function sanitizeSQL($input) {
        return str_replace([';', "'", '"', '--'], '', $input);
    }

    // Destroy session
    public static function destroySession(): void {
        // 1. Clear session data
        $_SESSION = [];
    
        // 2. Delete session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
    
        // 3. Destroy server-side session file
        session_destroy();
    }

}
?>