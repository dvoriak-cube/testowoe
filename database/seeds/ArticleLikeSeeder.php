<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ArticleLike;

class ArticleLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $users = User::all()->pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            ArticleLike::create([
                'user_id' => $faker->randomElement($users),
                'article_id' => $faker->randomElement($users),
            ]);
        }
    }
}
