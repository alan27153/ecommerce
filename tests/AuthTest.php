<?php
require_once __DIR__ . '/../app/models/User.php';

class AuthTest
{
    public function testPasswordHashing()
    {
        $password = '123456';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!password_verify($password, $hash)) {
            echo "FAIL: password hashing\n";
        } else {
            echo "PASS: password hashing\n";
        }
    }

    public function testUserExists()
    {
        $userModel = new User();
        $user = $userModel->findByEmail('test@example.com');
        
        if ($user === null) {
            echo "FAIL: user not found\n";
        } else {
            echo "PASS: user found\n";
        }
    }
}

// Ejecutar tests manualmente
$test = new AuthTest();
$test->testPasswordHashing();
$test->testUserExists();
