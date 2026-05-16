<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Электроника',
                'icon' => 'laptop',
                'description' => 'Компьютеры, телефоны, планшеты и другая техника'
            ],
            [
                'name' => 'Транспорт',
                'icon' => 'car',
                'description' => 'Автомобили, мотоциклы, велосипеды'
            ],
            [
                'name' => 'Недвижимость',
                'icon' => 'home',
                'description' => 'Квартиры, дома, участки'
            ],
            [
                'name' => 'Одежда',
                'icon' => 'tshirt',
                'description' => 'Мужская и женская одежда, обувь, аксессуары'
            ],
            [
                'name' => 'Мебель',
                'icon' => 'couch',
                'description' => 'Мебель для дома и офиса'
            ],
            [
                'name' => 'Спорт',
                'icon' => 'dumbbell',
                'description' => 'Спортивный инвентарь и одежда'
            ],
            [
                'name' => 'Хобби',
                'icon' => 'palette',
                'description' => 'Книги, музыка, коллекционирование'
            ],
            [
                'name' => 'Услуги',
                'icon' => 'handshake',
                'description' => 'Различные услуги'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']], 
                [
                    'icon' => $category['icon'],
                    'description' => $category['description'],
                    'slug' => Str::slug($category['name'])
                ]
            );
        }
    }
} 