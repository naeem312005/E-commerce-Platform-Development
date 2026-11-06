<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'sku',
        'description',
        'image',
        'old_price',
        'new_price',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'image_url',
        'discount_percentage',
        'saved_amount',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = static::generateUniqueSku();
            }
        });
    }

    /**
     * Generate unique slug for product.
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Generate unique SKU for product.
     */
    public static function generateUniqueSku(): string
    {
        do {
            $sku = 'PRD-'.strtoupper(Str::random(6));
        } while (static::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Scope for active products.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor for full image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://placehold.co/600x600/f3f4f6/6b7280?text=No+Image';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    /**
     * Accessor for calculated discount percentage.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if ($this->old_price > 0 && $this->old_price > $this->new_price) {
            return (int) round((($this->old_price - $this->new_price) / $this->old_price) * 100);
        }

        return 0;
    }

    /**
     * Accessor for saved amount.
     */
    public function getSavedAmountAttribute(): float
    {
        if ($this->old_price > $this->new_price) {
            return (float) ($this->old_price - $this->new_price);
        }

        return 0.00;
    }

    /**
     * Get the category of the product.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subcategory of the product.
     *
     * @return BelongsTo<Subcategory, $this>
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }
}
