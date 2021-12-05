<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Category;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_name'=> $faker->word,
        'category_id'=> function(){
            return Category::all()->random();
        },
        
        'product_short_description'=> $faker->text,
        'product_long_description'=> $faker->text,
        'product_price'=> $faker->randomDigitNot(200,1000),
        'publication_status'=> rand(0,3),
         'product_image'=> $faker->imageUrl
        
    ];
});
