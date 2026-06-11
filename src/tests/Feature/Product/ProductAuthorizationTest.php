<?php

namespace Tests\Feature\Product;

use App\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthentication;
use Tests\Concerns\InteractsWithProducts;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use InteractsWithAuthentication;
    use InteractsWithProducts;
    use RefreshDatabase;

    public function test_guest_can_read_products(): void
    {
        $product = Product::factory()->create();

        $this->getJson('/api/products')->assertOk();
        $this->getJson("/api/products/{$product->id}")->assertOk();
    }

    public function test_guest_cannot_modify_products(): void
    {
        $product = Product::factory()->create();

        $this->postJson('/api/products', $this->validProductPayload())->assertUnauthorized();
        $this->patchJson("/api/products/{$product->id}", ['name' => 'Updated'])->assertUnauthorized();
        $this->deleteJson("/api/products/{$product->id}")->assertUnauthorized();
    }

    public function test_regular_user_cannot_modify_products(): void
    {
        $product = Product::factory()->create();
        $headers = $this->userHeaders();

        $this->postJson('/api/products', $this->validProductPayload(), $headers)->assertForbidden();
        $this->patchJson("/api/products/{$product->id}", ['name' => 'Updated'], $headers)->assertForbidden();
        $this->deleteJson("/api/products/{$product->id}", [], $headers)->assertForbidden();
    }
}
