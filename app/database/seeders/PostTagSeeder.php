<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTagSeeder extends Seeder
{
    public function run(): void
    {
        $postTags = [];

        for ($id = 1; $id <= 5; $id++) {
            $postTags[] = [
                'post_id' => $id,
                'tag_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        DB::table('post_tags')->insert($postTags);
    }
}
