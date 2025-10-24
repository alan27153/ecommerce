<?php
use PHPUnit\Framework\TestCase;

// Importamos el modelo de PRUEBA, no el real
require_once __DIR__ . '/../models/Product.php';

class ProductTest extends TestCase {
    private PDO $conn;
    private Product $product;

    protected function setUp(): void {
        $this->conn = new PDO(
            "mysql:host=bv6gbzxygjnl7o0bsgte-mysql.services.clever-cloud.com;
             port=3306;
             dbname=bv6gbzxygjnl7o0bsgte",
            "u0kn1nfdegvefpmn",
            "rlvGJxyHCLRcMcebMoNO"
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->product = new Product($this->conn);
    }

    // 🔴 Etapa ROJA: prueba que debería fallar
    public function testCreateProductShouldReturnTrue(): void {
        $data = [
            'category_id' => 1,
            'name' => 'Producto TDD',
            'slug' => 'producto-tdd',
            'description' => 'Producto creado en etapa roja',
            'price_cents' => 1000,
            'currency' => 'PEN',
            'stock' => 10,
            'active' => 1
        ];
 
        $result = $this->product->create($data);
        $this->assertTrue($result, "El método create() aún no está implementado o falló.");
    }
}
