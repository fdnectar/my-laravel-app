<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;


class SkuGeneratorService {
    public function generateSku(string $productName, ?string $prefix = null): string
    {
        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', $productName);
        $namePrefix = strtoupper(substr($cleanName, 0, 3));

        $skuPrefix = $prefix ? strtoupper($prefix) . '-' : '';

        $random = str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $sku = $skuPrefix . $namePrefix . '-' . $random;

        while (Product::where('sku', $sku)->exists()) {
            $random = str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $sku = $skuPrefix . $namePrefix . '-' . $random;
        }

        return $sku;
    }

    public function isValidSkuFormat(string $sku): bool
    {
        return preg_match('/^([A-Z]+-)?[A-Z]{3}-\d{4}$/', $sku);
    }
}
