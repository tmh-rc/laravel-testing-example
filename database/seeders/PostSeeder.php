<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!File::isDirectory(storage_path('app/public/upload/image'))) {
            File::makeDirectory(storage_path('app/public/upload/image/'), 0755, true);
        }
        foreach(range(1, 20) as $image) {
            File::put(storage_path('app/public/upload/image/') . $image . '.png', '');
        }

        Post::factory(20)->create();
    }
}
