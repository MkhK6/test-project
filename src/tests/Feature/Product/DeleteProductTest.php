<?php

namespace Tests\Feature\Product;

use App\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy_deletes_existing_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_destroy_returns_not_found_for_missing_product(): void
    {
        $response = $this->deleteJson('/api/products/999999');

        $response->assertNotFound();
    }
}
