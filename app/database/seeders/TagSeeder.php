<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::factory(5)->create();
    }
}
