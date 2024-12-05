<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PearlsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            [
                'username' => '@hannies',
                'name' => 'Hannies',
                'user_id' => 2,
                'status' => 'active',
                'image_url' => '/images/static/hannies-logo.jpg'
            ],
            [
                'username' => '@oden',
                'name' => 'Oden',
                'user_id' => 3,
                'status' => 'active',
                'image_url' => '/images/static/oden-logo.jpg'
            ],
            [
                'username' => '@Chamz',
                'name' => 'Chamz',
                'user_id' => 4,
                'status' => 'active',
                'image_url' => '/images/static/chamz-logo.jpg'
            ],
        ];
        foreach ($shops as $shop) {
            $image_url = $shop['image_url'];
            unset($shop['image_url']);
            $pearlShop = Shop::query()->firstOrCreate($shop);
            $pearlShop->clearMediaCollection('logos');
            $pearlShop->addMediaFromUrl(url($image_url))->toMediaCollection('logos');
        }
        // $hans = Shop::query()->where('username', '@hans')->first();
        // $oden = Shop::query()->where('username', '@oden')->first();
        // $chamz = Shop::query()->where('username', '@chamz')->first();

        $items = [
            [
                'title' => '14 Karat Gold Pearl Earrings',
                'shop' => [
                    'name' => 'Hannies',
                    'username' => '@hannies'
                ],
                'is_new' => true,
                'rating' => 5,
                'price' => 6800,
                'image_url' => '/images/static/sample-earring.jpg',
                'tags' => ['Earrings'],
                'is_featured' => true
            ],
            [
                'title' => 'Stunning Pearl Necklace',
                'shop' => [
                    'name' => 'Oden',
                    'username' => '@oden'
                ],
                'rating' => 5,
                'price' => 1500,
                'image_url' => '/images/static/oden-neck.jpg',
                'tags' => ['Necklace'],
                'is_featured' => true
            ],
            [
                'title' => 'Southsea Necklace',
                'shop' => [
                    'name' => 'Hannies',
                    'username' => '@hannies'
                ],
                'rating' => 5,
                'price' => 75000,
                'image_url' => '/images/static/ss-set.png',
                'tags' => ['Necklace'],
                'is_featured' => true
            ],
            [
                'title' => 'Southsea Pearl Earring',
                'shop' => [
                    'name' => 'Hannies',
                    'username' => '@hannies'
                ],
                'rating' => 5,
                'price' => 7500,
                'image_url' => '/images/static/pearl-hannies.png',
                'tags' => ['Earrings'],
                'is_featured' => true
            ],
            [
                'title' => 'Southsea Bracelet',
                'shop' => [
                    'name' => 'Chamz',
                    'username' => '@chamz'
                ],
                'rating' => 4,
                'price' => 23000,
                'image_url' => '/images/static/chamz-bracelet.png',
                'tags' => ['Bracelet'],
                'is_featured' => true
            ],
        ];

        $filters = ['Category', 'Type'];
        $pearlTypes = ['Southsea', 'Freshwater'];
        $pearlCategories = ['Earrings', 'Bracelet', 'Rings', 'Necklace', 'Sets', 'Loose'];
        foreach ($filters as $filter) {
            $itemCategory = ItemCategory::query()->firstOrCreate(
                [
                    'name' => $filter,
                    'status' => 'active',
                ]
            );
            if ($filter == 'Category') {
                foreach ($pearlCategories as $pearlCategory) {
                    $itemCategory->subcategories()->firstOrCreate([
                        'name' => $pearlCategory,
                        'status' => 'active',
                    ]);
                }
            }
            if ($filter == 'Type') {
                foreach ($pearlTypes as $pearlType) {
                    $itemCategory->subcategories()->firstOrCreate([
                        'name' => $pearlType,
                        'status' => 'active',
                    ]);
                }
            }
        }

        foreach ($items as $item) {
            $shopItem = Item::query()->firstOrCreate([
                'name' => $item['title'],
                'shop_id' => Shop::query()->where('username', $item['shop']['username'])->first()->id,
                'price' => $item['price'],
                'is_featured' => $item['is_featured']
            ]);
            foreach ($item['tags'] as $tag) {
                $shopItem->categories()->attach(
                    ItemCategory::where('name', $tag)->first()->id,
                    ['shop_id' => $shopItem->shop_id]
                );
            }
            $shopItem->clearMediaCollection("images");
            $shopItem->addMediaFromUrl(
                url($item['image_url'])
            )->toMediaCollection("images");
        }


        dd(ItemCategory::whereNull('parent_id')->get()->toArray());
    }
}