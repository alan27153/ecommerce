<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/models/User.php';

class UserTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Configura tu conexión a la base de datos de test
        $this->conn = new PDO(
            "mysql:host=bv6gbzxygjnl7o0bsgte-mysql.services.clever-cloud.com;dbname=bv6gbzxygjnl7o0bsgte",
            "u0kn1nfdegvefpmn",
            "rlvGJxyHCLRcMcebMoNO"
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Asegurarse de que el usuario de prueba exista y esté reseteado
        $email = 'testverify@example.com';
        if (!User::emailExists($this->conn, $email)) {
            User::create(
                $this->conn,
                'Usuario Test',
                $email,
                password_hash('123456', PASSWORD_DEFAULT),
                'ABC123'
            );
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE users SET verified = 0, verification_code = 'ABC123' WHERE email = :email"
            );
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        }
    }

    public function testEmailExists()
    {
        // Este correo sí existe en la base de datos
        $this->assertTrue(User::emailExists($this->conn, 'testverify@example.com'));

        // Este correo no existe
        $this->assertFalse(User::emailExists($this->conn, 'noexiste@example.com'));
    }

    public function testCreateUser()
    {
        $email = 'newuser@example.com';
        // Primero eliminar si ya existe
        $stmt = $this->conn->prepare("DELETE FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $id = User::create(
            $this->conn,
            'Nuevo Usuario',
            $email,
            password_hash('123456', PASSWORD_DEFAULT),
            'XYZ789'
        );

        $this->assertIsNumeric($id);
        $this->assertTrue(User::emailExists($this->conn, $email));
    }

    public function testFindByEmail()
    {
        $user = User::findByEmail($this->conn, 'testverify@example.com');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('email', $user);
        $this->assertEquals('testverify@example.com', $user['email']);
    }

    public function testVerifyAccount()
    {
        $email = 'testverify@example.com';
        $verified = User::verifyAccount($this->conn, $email, 'ABC123');

        $this->assertTrue($verified);

        // Verificar que el campo "verified" ahora sea 1
        $user = User::findByEmail($this->conn, $email);
        $this->assertEquals(1, $user['verified']);
    }
}
?>
