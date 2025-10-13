<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use App\Models\CateqBlog;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

$ownerId = User::where('email', 'superadmin@jinnedu.com')->value('id')
                 ?? User::where('email', 'admin@jinnedu.com')->value('id')
                 ?? User::query()->value('id'); 

        if (!$ownerId) {
           
            $this->command->error('No users found. Run UserSeeder first.');
            return;
        }

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

        // عينات بوستات
        $allCats = CateqBlog::all();
        foreach (range(1, 12) as $i) {
            $title = "Sample Post {$i}";
            Blog::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'categ_blog_id' => $allCats->random()->id,
                    'title'         => $title,
                    'description'   => 'Short excerpt for demo.',
                    'image'         => 'https://picsum.photos/seed/'.$i.'/1200/800',
                    'status'        => 'published',
                    'date'          => now()->subDays(rand(0,60)),
                    
                ]
            );
        }
    }
}
