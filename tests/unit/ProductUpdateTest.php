<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Product.php';

class ProductUpdateTest extends TestCase {
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

    // 🔴 Etapa ROJA: debería fallar porque la función update() aún no existe o no devuelve true
    public function testUpdateProductShouldReturnTrue(): void {
        $data = [
            'category_id' => 1,
            'name' => 'Producto actualizado TDD',
            'slug' => 'producto-actualizado-tdd',
            'description' => 'Actualizado con TDD',
            'price_cents' => 2500,
            'currency' => 'PEN',
            'stock' => 20,
            'active' => 1
        ];

        // Probamos con un ID que exista en tu BD (ajusta si es necesario)
        $result = $this->product->update(1, $data);
        $this->assertTrue($result, "El método update() aún no está implementado o falló.");
    }
}
