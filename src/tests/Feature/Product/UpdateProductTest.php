<?php

namespace Tests\Feature\Product;

use App\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthentication;
use Tests\Concerns\InteractsWithProducts;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use InteractsWithAuthentication;
    use InteractsWithProducts;
    use RefreshDatabase;

    public function test_update_changes_product_with_valid_data(): void
    {
        $product = Product::factory()->create([
            'name'  => 'Old Name',
            'price' => 1000,
        ]);

        $response = $this->patchJson("/api/products/{$product->id}", [
            'name'  => 'Updated Name',
            'price' => 3200,
        ], $this->adminHeaders());

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.price', 3200);

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'Updated Name',
            'price' => 3200,
        ]);
    }

    public function test_update_returns_not_found_for_missing_product(): void
    {
        $response = $this->patchJson('/api/products/999999', [
            'name' => 'Ghost Product',
        ], $this->adminHeaders());

        $response->assertNotFound();
    }
}
