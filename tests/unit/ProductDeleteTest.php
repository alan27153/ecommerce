<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Product.php';

class ProductDeleteTest extends TestCase {
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

    // 🔴 Etapa ROJA: debería fallar porque delete() aún no está implementada o devuelve null
    public function testDeleteProductShouldReturnTrue(): void {
        $id = 1; // usa un id real de producto existente

        $result = $this->product->delete($id);

        $this->assertTrue($result, "El método delete() aún no está implementado o falló.");
    }
}
