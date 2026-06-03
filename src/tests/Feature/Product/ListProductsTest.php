<?php

namespace Tests\Feature\Product;

use App\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithProducts;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use InteractsWithProducts;
    use RefreshDatabase;

    public function test_index_returns_paginated_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->productJsonStructure(),
                ],
                'links',
                'meta',
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_index_rejects_unsupported_http_method(): void
    {
        $response = $this->json('TRACE', '/api/products');

        $response->assertStatus(405);
    }
}
