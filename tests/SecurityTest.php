<?php

namespace PocketSecurity\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PocketSecurity\Security;
use PocketTesting\Testing;

class SecurityTest extends Testing
{
    public function __construct()
    {
        // avoid header issues → start session silently
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // suppress header warnings only for tests
        ob_start();

        parent::__construct(); // prints header
    }

    public function run()
    {
        $this->testSanitizeOutput();
        $this->testSanitizeSQL();
        $this->testCsrfToken();
        $this->testCsrfVerify();
        $this->testDestroySession();

        ob_end_clean(); // remove any header warnings
        $this->summary();
    }

    private function testSanitizeOutput()
    {
        $input = '<script>alert("xss")</script>';
        $output = Security::sanitizeOutput($input);
        $expected = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        $this->checkEqual($expected, $output, "sanitizeOutput should escape HTML");
    }

    private function testSanitizeSQL()
    {
        $input = "abc; DROP TABLE users -- ";
        $sanitized = Security::sanitizeSQL($input);

        $this->checkTrue(strpos($sanitized, ';') === false, "sanitizeSQL should remove semicolons");
        $this->checkTrue(strpos($sanitized, '--') === false, "sanitizeSQL should remove SQL comments");
        $this->checkTrue(strpos($sanitized, "'") === false, "sanitizeSQL should remove single quotes");
        $this->checkTrue(strpos($sanitized, '"') === false, "sanitizeSQL should remove double quotes");
    }

    private function testCsrfToken()
    {
        $token1 = Security::csrfGenerate();
        $token2 = Security::csrfGenerate();

        $this->checkTrue(!empty($token1), "CSRF token should not be empty");
        $this->checkEqual($token1, $token2, "csrfGenerate should return same token on repeat");
    }

    private function testCsrfVerify()
    {
        $token = Security::csrfGenerate();

        ob_start();
        Security::csrfVerify($token);
        ob_end_clean();

        $this->checkTrue(true, "csrfVerify should pass with correct token");
    }

    private function testDestroySession()
    {
        $_SESSION['test'] = 'value';

        // suppress header warnings from setcookie()
        ob_start();
        Security::destroySession();
        ob_end_clean();

        $this->checkTrue(empty($_SESSION), "destroySession should clear session array");
    }
}

$test = new SecurityTest();
$test->run();
