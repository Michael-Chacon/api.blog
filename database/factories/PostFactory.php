<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {   
        $name = $this->faker->unique()->word(20);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'stract' => $this->faker->text(200),
            'body' => $this->faker->text(1000),
            'status' => (string) $this->faker->randomElement([Post::BORRADOR, Post::PUBLICADO]),
            'Category_id' => Category::all()->random()->id,
            'User_id' => User::all()->random()->id
        ];
    }
}
