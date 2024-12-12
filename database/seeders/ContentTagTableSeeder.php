<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class ContentTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // First, clear the content_tag table to prevent duplicate associations
        DB::table('content_tag')->delete();

        $contents = Content::all();
        $tagIds = Tag::pluck('id')->toArray();

        foreach ($contents as $content) {
            // Decide how many tags you want to assign to each content, for example 1 to 3.
            $tagsCount = rand(1, 3);

            // Randomly pick tag IDs to associate with content
            $randomTagIds = array_rand(array_flip($tagIds), $tagsCount);

            // Ensure $randomTagIds is always an array
            $randomTagIds = is_array($randomTagIds) ? $randomTagIds : [$randomTagIds];

            foreach ($randomTagIds as $tagId) {
                DB::table('content_tag')->insert([
                    'content_id' => $content->id,
                    'tag_id' => $tagId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
