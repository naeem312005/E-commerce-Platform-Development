<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Admin Users
        User::updateOrCreate(
            ['email' => 'admin@banglapuzzle.com'],
            [
                'name' => 'Md Naimul Hasan Naeem',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'naimulhasan312005@gmail.com'],
            [
                'name' => 'Naimul Hasan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Data Structure for Categories, Subcategories, and Products
        $catalogData = [
            [
                'name' => 'Smartphones & Gadgets',
                'description' => 'Flagship smartphones, smart wearables, and mobile lifestyle accessories.',
                'subcategories' => [
                    [
                        'name' => 'Flagship Smartphones',
                        'description' => 'Top tier flagship and premium Android and iOS devices.',
                        'products' => [
                            [
                                'name' => 'Apple iPhone 15 Pro Max 256GB',
                                'description' => 'Forged in titanium and featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever with 5x optical zoom.',
                                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 175000.00,
                                'new_price' => 159999.00,
                            ],
                            [
                                'name' => 'Samsung Galaxy S24 Ultra 5G',
                                'description' => 'Galaxy AI is here. Meet Galaxy S24 Ultra, featuring a durable titanium exterior, built-in S Pen, and a 200MP camera system with advanced Nightography.',
                                'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 165000.00,
                                'new_price' => 148500.00,
                            ],
                            [
                                'name' => 'Google Pixel 8 Pro',
                                'description' => 'The engineered by Google phone with the next-gen Tensor G3 chip, best-in-class computational photography, and immersive 120Hz Super Actua display.',
                                'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 115000.00,
                                'new_price' => 98900.00,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Smartwatches & Fitness',
                        'description' => 'Health monitoring, GPS tracking, and smart notifications on your wrist.',
                        'products' => [
                            [
                                'name' => 'Apple Watch Ultra 2 Titanium',
                                'description' => 'The most rugged and capable Apple Watch. Features an ultra-bright display, precision dual-frequency GPS, and up to 36 hours of battery life.',
                                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 95000.00,
                                'new_price' => 84500.00,
                            ],
                            [
                                'name' => 'Samsung Galaxy Watch 6 Classic',
                                'description' => 'Rotating bezel design with advanced sleep coaching, body composition analysis, and ECG heart rate tracking in a stainless steel casing.',
                                'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 38000.00,
                                'new_price' => 32500.00,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Computers & Accessories',
                'description' => 'High performance laptops, mechanical typing experiences, and gaming peripherals.',
                'subcategories' => [
                    [
                        'name' => 'Laptops & Ultrabooks',
                        'description' => 'Slim, powerful, and durable laptops for developers and creators.',
                        'products' => [
                            [
                                'name' => 'Apple MacBook Pro 16-inch M3 Max',
                                'description' => 'Unbelievable pro performance featuring the M3 Max chip with 16-core CPU, 40-core GPU, Liquid Retina XDR display, and up to 22 hours of battery life.',
                                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 385000.00,
                                'new_price' => 349000.00,
                            ],
                            [
                                'name' => 'Dell XPS 15 OLED InfinityEdge',
                                'description' => 'Stunning 3.5K OLED touchscreen display powered by Intel Core i9 13th Gen with NVIDIA GeForce RTX 4070 graphics in CNC machined aluminum.',
                                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 265000.00,
                                'new_price' => 238000.00,
                            ],
                            [
                                'name' => 'ASUS ROG Zephyrus G14 Gaming Laptop',
                                'description' => 'Compact 14-inch gaming beast with AMD Ryzen 9, ROG Nebula Display with 165Hz refresh rate, and AniMe Matrix LED lid customization.',
                                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 210000.00,
                                'new_price' => 189999.00,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Mechanical Keyboards',
                        'description' => 'Tactile custom mechanical keyboards and premium desk accessories.',
                        'products' => [
                            [
                                'name' => 'Keychron Q1 Pro Wireless Custom Mechanical Keyboard',
                                'description' => 'Full CNC aluminum body, QMK/VIA programmable, double-gasket acoustic design, hot-swappable switches, and multi-device Bluetooth 5.1.',
                                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 22000.00,
                                'new_price' => 18500.00,
                            ],
                            [
                                'name' => 'Logitech MX Mechanical Wireless Illuminated Keyboard',
                                'description' => 'Low-profile mechanical switches, smart backlighting, dual-OS layout with seamless flow control across 3 computers simultaneously.',
                                'image' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 17500.00,
                                'new_price' => 14900.00,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Audio & Entertainment',
                'description' => 'Audiophile headphones, noise-canceling wireless earbuds, and spatial sound.',
                'subcategories' => [
                    [
                        'name' => 'Wireless Headphones',
                        'description' => 'Active noise cancellation over-ear and wireless listening.',
                        'products' => [
                            [
                                'name' => 'Sony WH-1000XM5 Wireless Noise-Canceling Headphones',
                                'description' => 'Industry-leading noise cancellation with two processors and 8 microphones, crystal clear hands-free calling, and 30-hour battery life with quick charging.',
                                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 45000.00,
                                'new_price' => 38900.00,
                            ],
                            [
                                'name' => 'Apple AirPods Max Space Gray',
                                'description' => 'High-fidelity audio combined with industry-leading Active Noise Cancellation and Transparency mode. Computational audio creates a theater-like sound experience.',
                                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 68000.00,
                                'new_price' => 59500.00,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Bluetooth Speakers',
                        'description' => 'Portable, waterproof room-filling wireless audio sound systems.',
                        'products' => [
                            [
                                'name' => 'Marshall Stanmore III Bluetooth Speaker',
                                'description' => 'Iconic vintage styling with a wider soundstage, re-engineered sound for a more immersive home audio experience, and Bluetooth 5.2 connectivity.',
                                'image' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 42000.00,
                                'new_price' => 36500.00,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 3. Insert Categories, Subcategories, and Products
        foreach ($catalogData as $catData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'is_active' => true,
                ]
            );

            foreach ($catData['subcategories'] as $subcatData) {
                $subcategory = Subcategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($subcatData['name']),
                    ],
                    [
                        'name' => $subcatData['name'],
                        'description' => $subcatData['description'],
                        'is_active' => true,
                    ]
                );

                foreach ($subcatData['products'] as $prodData) {
                    Product::updateOrCreate(
                        ['slug' => Str::slug($prodData['name'])],
                        [
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'name' => $prodData['name'],
                            'sku' => Product::generateUniqueSku(),
                            'description' => $prodData['description'],
                            'image' => $prodData['image'],
                            'old_price' => $prodData['old_price'],
                            'new_price' => $prodData['new_price'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
