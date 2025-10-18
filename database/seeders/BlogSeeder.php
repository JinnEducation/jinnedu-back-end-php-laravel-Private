<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\CateqBlog;
use App\Models\User; // <<<
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // <<<
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run()
    {
        
        $owner = User::where('name', 'Super Admin')->first();

      
        if (!$owner) {
            $this->command->error('لم يتم العثور على المستخدم "Super Admin" في جدول users.');
            return;
        }

        $ownerId = $owner->id;

      
        $cats = [
            ['name' => 'English', 'slug' => 'english'],
            ['name' => 'Tech', 'slug' => 'tech'],
            ['name' => 'Arabic', 'slug' => 'arabic'],
            ['name' => 'Math', 'slug' => 'math'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Physics', 'slug' => 'physics'],
            ['name' => 'Chemistry', 'slug' => 'chemistry'],
        ];

        foreach ($cats as $i => $c) {
            CateqBlog::firstOrCreate(
                ['slug' => $c['slug']],
                [
                    'name'       => $c['name'],
                    'sort_order' => $i,
                    'is_active'  => true,
                    'user_id'    => $ownerId, 
                ]
            );
        }

        
        $allCats = CateqBlog::all();
        foreach (range(1, 12) as $i) {
            $title = "Sample Post {$i}";

            Blog::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'user_id'       => $ownerId,
                    'categ_blog_id' => $allCats->random()->id,
                    'title'         => $title,
                    'description'   => 'Short excerpt for demo.',
                    'image'         => 'https://picsum.photos/seed/'.$i.'/1200/800',
                    'status'        => 'published',
                    'date'          => now()->subDays(rand(0, 60)),
                ]
            );
        }

        $this->command->info(' BlogSeeder تم تنفيذه بنجاح، والمستخدم هو Super Admin');
    }
}
