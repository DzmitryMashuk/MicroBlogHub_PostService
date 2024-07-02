<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    private int $userIdCounter     = 1;
    private int $categoryIdCounter = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'user_id'     => 'int',
        'title'       => 'string',
        'slug'        => 'string',
        'content'     => 'string',
        'category_id' => 'int',
    ])] public function definition(): array
    {
        $title = $this->faker->word;

        return [
            'user_id'     => $this->userIdCounter++,
            'title'       => $title,
            'slug'        => $title,
            'content'     => $this->faker->text,
            'category_id' => $this->categoryIdCounter++,
        ];
    }
}
