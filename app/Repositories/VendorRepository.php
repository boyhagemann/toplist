<?php namespace App\Repositories;

use App\Vendor;
use Illuminate\Support\Str;

class VendorRepository
{
    /**
     * @param $name
     * @return Vendor
     */
    public static function firstOrCreateByName($name)
    {
        $slug = Str::slug($name);
        $vendor = Vendor::firstOrNew(['slug' => $slug]);
        $vendor->name = $name;
        $vendor->save();

        return $vendor;
    }

}