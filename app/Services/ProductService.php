<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getFilteredProducts(array $filters)
    {
        return Product::query()
            ->when(
                $filters['sort'] ?? null,
                fn($query, $sort) => $query->orderBy('price', $sort)
            )
            ->when(
                $filters['min_price'] ?? null,
                fn($query, $price) => $query->where('price', '>', $price)
            )
            ->when(
                $filters['max_price'] ?? null,
                fn($query, $price) => $query->where('price', '<=', $price)
            )
            ->when(
                $filters['name'] ?? null,
                fn($query, $name) => $query->where('name', 'like', "%{$name}%")
            )
            ->when(
                $filters['category'] ?? null,
                fn($query, $category) => $query->where('category_id', $category)
            )
            ->paginate(8)
            ->withQueryString();
    }
}
