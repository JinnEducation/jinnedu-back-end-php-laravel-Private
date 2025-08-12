<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Menu;
use App\Models\LoginSessionLog;

use Bouncer;
use Mail;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::where('p_id', intval($request->p_id))->get();
        $data['menus'] = $menus;
        return view('dashboard.menus.index', $data);
    }

    public function create()
    {
        $parents = Menu::query()->parents()->get();
        $data['parents'] = $parents;
        return view('dashboard.menus.create', $data);
    }

    public function store(MenuRequest $request)
    {
        $data = $request->only(['p_id','name','title','type','route','svg','sortable','status','visible']);
        $data['p_id'] = intval($request->p_id);
        $data['status'] = intval($request->status);
        $data['visible'] = intval($request->visible);
        $data['sortable'] = intval($request->sortable);
        Menu::create($data);
        return response_web(true, 'Create Success', 200);
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return redirect()->back()->withErrors(['errorMsg' => 'menu Dose Not Exist']);
        }
        $data['item'] = $menu;

        $parents = Menu::query()->parents()->get();
        $data['parents'] = $parents;

        return view('dashboard.menus.create', $data);
    }

    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return redirect()->back()->withErrors(['errorMsg' => 'menu Dose Not Exist']);
        }
        $data = $request->only(['p_id','name','title','type','route','svg','sortable','status','visible']);
        $data['p_id'] = intval($request->p_id);
        $data['status'] = intval($request->status);
        $data['visible'] = intval($request->visible);
        $data['sortable'] = intval($request->sortable);
        $menu->update($data);
        return response_web(true, 'Update Success', 200);
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return redirect()->back()->withErrors(['errorMsg' => 'menu Dose Not Exist']);
        }
        $menu->delete();
        return response_web(true, 'Update Success', 200);
    }

    public function abilities($id)
    {
        /** @var User $user */
        $user = Auth::user();

        $menu = Menu::find($id);
        $menu->childrens = $menu->childes()->get();
        foreach($menu->childrens as $submenu) {
            $submenu->checked = Bouncer::can($submenu->name, $submenu->type);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $menu
        ], 200);

    }

    public function navigation()
    {
        /** @var User $user */
        $user = Auth::user();
        //$abilities = $user->getAbilities();
        //var_dump($user);exit;
        //$bouncer = Bouncer::create($user);

        $navigation = [];
        //$navigation[] = [ "name" =>  "Dashboard", "icon" => "HomeIcon", "current" => true, "href" => "#" ];

        $menus = Menu::parents()->get();

        foreach($menus as $menu) {
            $isCanAny = false;
            $children = [];
            if(!empty($menu->type)) {
                $submenus = $menu->childrens()->where('invisible', 0)->where('status', 0)->get();
                //var_dump($submenus);exit;
                $menu->submenuCount = count($submenus);
                if($menu->submenuCount > 0) {
                    foreach($submenus as $submenu) {
                        //echo '>>>'.$submenu->name.', '.$submenu->type;
                        if(Bouncer::can($submenu->name, $submenu->type)) {
                            //echo 'true';
                            $isCanAny = true;
                        } else {
                            //$isCanAny=true;
                            //echo 'false';
                            continue;
                        }
                        //echo '<<<'.$submenu->name.', '.$submenu->type;exit;
                        $routes = explode('|', $submenu->active_routes);
                        $isActive = false;
                        /*if(Route::is('dashboard.'.$menu->route.'.'.$submenu->route)){
                                $isActive=true;
                                $isOpen=true;
                        }*/
                        $submenu->isActive = $isActive;
                        $children [] = [  "id" => $submenu->id, "title" =>  "global." . $submenu->title, "type" => 'child', "link" => $submenu->route, "abilities" => $menu->id ];
                    }
                    $menu->href = 'javascript:;';
                    $menu->submenuClass = ' menu-item-submenu ';
                } else {
                    //$menu->href='javascript:;';
                    //$routes = explode('|',$menu->active_routes);
                    continue;
                }

                if(!$isCanAny) {
                    continue;
                }

                /*foreach($submenus as $submenu){
                  if(Bouncer::cannot($submenu->name, $submenu->type)) continue;
                  $children [] = [ "title" =>  "global.".$submenu->title, "type"=> 'child', "link" => $submenu->route ];
                }*/
                $navigation[] = [
                    "id" => $menu->id,
                    "title" => "global." . $menu->title,
                    "type" => 'parent',
                    "abilities" => $menu->id,
                    "link" => '#',
                    "icon" => $menu->svg,
                    "current" => false,
                    "children" => $children,
                ];
            } else {
                $submenus = $menu->childrens()->where('invisible', 0)->where('status', 0)->get();
                foreach($submenus as $submenu) {
                    $subsubmenus = $submenu->childes()->where('status', 0)->get();
                    $isCanAnySubmenu = false;
                    $submenu->submenuCount = count($subsubmenus);

                    if($submenu->submenuCount > 0) {
                        foreach($subsubmenus as $subsubmenu) {

                            if(Bouncer::can($subsubmenu->name, $subsubmenu->type)) {
                                $isCanAnySubmenu = true;
                                $isCanAny = true;
                            } else {

                                continue;
                            }

                        }
                    }
                    if(!$isCanAnySubmenu) {
                        continue;
                    }
                    $children [] = [ "id" => $submenu->id, "title" =>  "global." . $submenu->title, "type" => 'child', "link" => $submenu->route, "abilities" => $submenu->id ];
                }
                //var_dump($children);exit;
                if(!$isCanAny) {
                    continue;
                }

                $navigation[] = [
                    "id" => $menu->id,
                    "title" => "global." . $menu->title,
                    "type" => 'parent',
                    "abilities" => 0,
                    "link" => '#',
                    "icon" => $menu->svg,
                    "current" => false,
                    "children" => $children,
                    "submenu"=>$subsubmenus

                ];
            }
        }
        return $navigation;
    }

}