<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthentication;
use Tests\Concerns\InteractsWithProducts;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    use InteractsWithAuthentication;
    use InteractsWithProducts;
    use RefreshDatabase;

    public function test_store_creates_product_with_valid_data(): void
    {
        $payload = $this->validProductPayload([
            'name'  => 'Mechanical Keyboard',
            'price' => 7500,
        ]);

        $response = $this->postJson('/api/products', $payload, $this->adminHeaders());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => $this->productJsonStructure(),
            ])
            ->assertJsonPath('data.name', 'Mechanical Keyboard')
            ->assertJsonPath('data.price', 7500);

        $this->assertDatabaseHas('products', [
            'name'  => 'Mechanical Keyboard',
            'price' => 7500,
        ]);
    }

    public function test_store_returns_validation_error_for_invalid_data(): void
    {
        $response = $this->postJson('/api/products', [
            'name'     => '',
            'price'    => -10,
            'category' => '',
        ], $this->adminHeaders());

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'category']);
    }
}
