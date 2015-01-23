<?php namespace App\Repositories;

use App\Offer;
use App\Product;
use App\Vendor;

class OfferRepository
{
    public static function firstOrNew(Product $product, Vendor $vendor)
    {
        Offer::unguard();
        return Offer::firstOrNew([
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
        ]);
    }
}