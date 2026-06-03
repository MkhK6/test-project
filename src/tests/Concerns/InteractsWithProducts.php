<?php

namespace Tests\Concerns;

trait InteractsWithProducts
{
    /**
     * @return array<string, mixed>
     */
    protected function validProductPayload(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Test Product',
            'description' => 'A product used in feature tests',
            'price'       => 1999,
            'weight'      => 1.25,
            'category'    => 'electronics',
        ], $overrides);
    }

    /**
     * @return array<int, string>
     */
    protected function productJsonStructure(): array
    {
        return [
            'id',
            'name',
            'description',
            'price',
            'weight',
            'category',
            'created_at',
            'updated_at',
        ];
    }
}
