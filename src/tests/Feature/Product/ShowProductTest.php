<?php

namespace Tests\Feature\Product;

use App\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithProducts;
use Tests\TestCase;

class ShowProductTest extends TestCase
{
    use InteractsWithProducts;
    use RefreshDatabase;

    public function test_show_returns_existing_product(): void
    {
        $product = Product::factory()->create([
            'name'     => 'Wireless Mouse',
            'price'    => 2500,
            'category' => 'electronics',
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->productJsonStructure(),
            ])
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', 'Wireless Mouse')
            ->assertJsonPath('data.price', 2500);
    }

    public function test_show_returns_not_found_for_missing_product(): void
    {
        $response = $this->getJson('/api/products/999999');

        $response->assertNotFound();
    }
}
