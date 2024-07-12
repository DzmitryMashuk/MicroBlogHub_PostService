<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['name' => 'string'])] public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
