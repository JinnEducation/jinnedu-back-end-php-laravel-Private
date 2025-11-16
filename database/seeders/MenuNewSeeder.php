<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\CateqBlog;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;
//use App\Models\Report;
use App\Models\Menu;

use Illuminate\Database\Seeder;

class MenuNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public $id;

    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('menus')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //================================================
        $this->id = Menu::query()->max('id') ?? 0;

        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'menu',
            'title' => 'menu-management',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Menu::class, 
                    'name' => 'menu.index',
                    'title' => 'menu-index',
                    'indexTitle' => ['menu-list',1],
                    'createTitle' => ['add-menu',1],
                    'editTitle' => ['update-menu',1],
                    'showTitle' => ['view-menu',1],
                    'destroyTitle' => ['delete-menu',1],
                    'svg' => '',
                ],
                [
                    'type' => Menu::class, 
                    'name' => 'menu.create',
                    'title' => 'menu-create',
                    'createTitle' => ['add-menu',1],
                    'editTitle' => ['update-menu',1],
                    'svg' => '',
                ],
            ]
        ]);
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'blog',
            'title' => 'blog-management',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Blog::class, 
                    'name' => 'blog.index',
                    'title' => 'blog-index',
                    'indexTitle' => ['blog-list',1],
                    'createTitle' => ['add-blog',1],
                    'editTitle' => ['update-blog',1],
                    'showTitle' => ['view-blog',1],
                    'destroyTitle' => ['delete-blog',1],
                    'svg' => '',
                ],
                [
                    'type' => Blog::class, 
                    'name' => 'blog.create',
                    'title' => 'blog-create',
                    'createTitle' => ['add-blog',1],
                    'editTitle' => ['update-blog',1],
                    'svg' => '',
                ],
                [
                    'type' => CateqBlog::class, 
                    'name' => 'cateqblog.show',
                    'title' => 'cateqblog-show',
                    'indexTitle' => ['cateqblog-list',1],
                    'createTitle' => ['add-cateqblog',1],
                    'editTitle' => ['update-cateqblog',1],
                    'showTitle' => ['view-cateqblog',1],
                    'destroyTitle' => ['delete-cateqblog',1],
                    'svg' => '',
                ],
            ]
        ]);
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'slider',
            'title' => 'slider-management',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Slider::class, 
                    'name' => 'slider.index',
                    'title' => 'slider-index',
                    'indexTitle' => ['slider-list',1],
                    'createTitle' => ['add-slider',1],
                    'editTitle' => ['update-slider',1],
                    'showTitle' => ['view-slider',1],
                    'destroyTitle' => ['delete-slider',1],
                    'svg' => '',
                ],
                [
                    'type' => Slider::class, 
                    'name' => 'slider.create',
                    'title' => 'slider-create',
                    'createTitle' => ['add-slider',1],
                    'editTitle' => ['update-slider',1],
                    'svg' => '',
                ],
                
            ]
        ]);
        //================================================
    }

    public function createMenuSubMenus($data,$p_id=0){
        $parentId = ++$this->id;
        Menu::query()->updateOrCreate(['id' => $parentId], ['invisible' => 0, 'type' => $data['type'], 'title' => $data['title'], 'p_id' => $p_id, 
            'route' => $data['name'], 'name' => $data['name'], 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.create|'.$data['name'].'.show|'.$data['name'].'.edit', 
            'svg' => $data['svg']
        ]);
        //===========================================    
        if(isset($data['indexTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['indexTitle'][1], 'type' => $data['type'], 'title' => $data['indexTitle'][0], 'p_id' => $parentId, 'name' => 'index', 'route' => $data['name'].'.index', 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.show'
        ]);

        //===========================================    
        if(isset($data['createTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['createTitle'][1], 'type' => $data['type'], 'title' => $data['createTitle'][0], 'p_id' => $parentId, 'name' => 'create', 'route' => $data['name'].'.create', 
            'active_routes' => $data['name'].'.create|'.$data['name'].'.edit'        
        ]);
        
        //===========================================    
        if(isset($data['showTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['showTitle'][1], 'type' => $data['type'], 'title' => $data['showTitle'][0], 'p_id' => $parentId, 'name' => 'show', 'route' => $data['name'].'.show', 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.show'
        ]);

        //===========================================    
        if(isset($data['editTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['editTitle'][1], 'type' => $data['type'], 'title' => $data['editTitle'][0], 'p_id' => $parentId, 'name' => 'edit', 'route' => $data['name'].'.edit', 
            'active_routes' => $data['name'].'.create|'.$data['name'].'.edit'
        ]);

        //===========================================    
        if(isset($data['destroyTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['destroyTitle'][1], 'type' => $data['type'], 'title' => $data['destroyTitle'][0], 'p_id' => $parentId, 'name' => 'destroy', 'route' => $data['name'].'.destroy', 
            'active_routes' => $data['name'].'.destroy'        
        ]);

        //===========================================
        if(isset($data['others'])) 
            foreach($data['others'] as $submenu) 
                Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $submenu['invisible'], 'type' => $data['type'], 'title' => $submenu['title'], 'p_id' => $parentId, 'name' => $submenu['name'], 'route' => $data['name'].'.'.$submenu['name'], 
                    'active_routes' => $data['name'].'.'.$submenu['name']        
                ]);
        //===========================================
        if(isset($data['children'])) 
            foreach($data['children'] as $submenu) 
                $this->createMenuSubMenus($submenu,$parentId);

    }
}
//last id 29 new 30