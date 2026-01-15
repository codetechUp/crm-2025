<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductCategory as ProductCategoryContract;

class ProductCategory extends Model implements ProductCategoryContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(ProductProxy::modelClass(), 'product_category_id');
    }
}
