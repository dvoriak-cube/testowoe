<?php

use Illuminate\Database\Seeder;
use App\Article;
use App\User;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Article::truncate();

        $faker = \Faker\Factory::create();
        $users = User::all()->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            Article::create([
                'title' => $faker->sentence,
                'body' => $faker->paragraph,
                'category' => $faker->word,
                'user_id' => $faker->randomElement($users),
            ]);
        }
    }
}
