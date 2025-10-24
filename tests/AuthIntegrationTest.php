<?php
use PHPUnit\Framework\TestCase;
require_once 'controllers/AuthController.php';
require_once 'models/User.php';

class AuthIntegrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=test_db", "root", "");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testUserRegistrationAndVerification()
    {
        $controller = new AuthController();

        // Simular registro
        $result = $controller->register([
            'name' => 'Integracion Test',
            'email' => 'integration@test.com',
            'password' => '123456',
            'dni' => '12345678'
        ]);

        $this->assertTrue($result['success']);

        // Verificar que el usuario fue creado
        $user = User::findByEmail($this->conn, 'integration@test.com');
        $this->assertNotEmpty($user);

        // Simular verificación de cuenta
        $verified = User::verifyAccount($this->conn, 'integration@test.com', $user['verification_code']);
        $this->assertTrue($verified);
    }
}
?>
