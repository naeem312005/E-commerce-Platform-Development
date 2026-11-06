<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_can_create_category_with_validation(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.categories.store'), [
            'name' => 'Fashion & Apparel',
            'description' => 'All trendy clothing and fashion',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Fashion & Apparel',
            'slug' => 'fashion-apparel',
        ]);
    }

    public function test_cannot_create_duplicate_category(): void
    {
        Category::create([
            'name' => 'Watches',
            'slug' => 'watches',
        ]);

        $response = $this->actingAs($this->user)->post(route('admin.categories.store'), [
            'name' => 'Watches',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_can_create_subcategory_under_category(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        $response = $this->actingAs($this->user)->post(route('admin.subcategories.store'), [
            'category_id' => $category->id,
            'name' => 'Mobile Phones',
            'description' => 'Smart and feature phones',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.subcategories.index'));
        $this->assertDatabaseHas('subcategories', [
            'category_id' => $category->id,
            'name' => 'Mobile Phones',
            'slug' => 'mobile-phones',
        ]);
    }

    public function test_can_create_product_with_image_and_pricing(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Gadgets', 'slug' => 'gadgets']);
        $subcategory = Subcategory::create(['category_id' => $category->id, 'name' => 'Wearables', 'slug' => 'wearables']);

        $image = UploadedFile::fake()->image('watch.jpg', 600, 600);

        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Ultra Smart Watch Series 9',
            'description' => 'Next gen wearable with health tracking.',
            'image' => $image,
            'old_price' => 5000.00,
            'new_price' => 4200.00,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Ultra Smart Watch Series 9',
            'slug' => 'ultra-smart-watch-series-9',
            'old_price' => 5000.00,
            'new_price' => 4200.00,
        ]);

        $product = Product::where('slug', 'ultra-smart-watch-series-9')->first();
        $this->assertNotNull($product);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_product_validation_fails_if_new_price_exceeds_old_price(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Footwear', 'slug' => 'footwear']);
        $subcategory = Subcategory::create(['category_id' => $category->id, 'name' => 'Sneakers', 'slug' => 'sneakers']);

        $image = UploadedFile::fake()->image('shoes.jpg');

        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Running Sneakers',
            'description' => 'Lightweight mesh runners.',
            'image' => $image,
            'old_price' => 2000.00,
            'new_price' => 2500.00, // Invalid: higher than old price!
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('new_price');
    }

    public function test_can_view_product_details_by_slug(): void
    {
        $category = Category::create(['name' => 'Computers', 'slug' => 'computers']);
        $subcategory = Subcategory::create(['category_id' => $category->id, 'name' => 'Laptops', 'slug' => 'laptops']);

        $product = Product::create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'MacBook Air M2',
            'slug' => 'macbook-air-m2',
            'sku' => 'PRD-TEST01',
            'description' => 'Thin and light notebook.',
            'image' => 'https://example.com/macbook.jpg',
            'old_price' => 125000.00,
            'new_price' => 115000.00,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.show', ['slug' => 'macbook-air-m2']));
        $response->assertStatus(200);
        $response->assertSee('MacBook Air M2');
    }

    public function test_can_view_catalog_showcase_organized_by_subcategories(): void
    {
        $category = Category::create(['name' => 'Gaming', 'slug' => 'gaming']);
        $subcategory = Subcategory::create(['category_id' => $category->id, 'name' => 'Consoles', 'slug' => 'consoles']);

        Product::create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'PlayStation 5 Pro',
            'slug' => 'playstation-5-pro',
            'sku' => 'PRD-PS5PRO',
            'description' => 'Next gen 4K 120fps gaming console.',
            'image' => 'https://example.com/ps5.jpg',
            'old_price' => 85000.00,
            'new_price' => 79000.00,
            'is_active' => true,
        ]);

        $response = $this->get(route('catalog.public'));
        $response->assertStatus(200);
        $response->assertSee('Consoles');
    }
}
