<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Bouncer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    private array $menuUserTypeOverrides = [
        'orders.my-index' => [0,1, 2],
        'chats.private-chat' => [0,1, 2],
        'favorites' => [1, 2],
        'favorites.index' => [1, 2],
        'payout.create' => [2],
        'accounting.balance-report.*' => [2],
        'my-courses.all' => [1],
        'my-courses.completed' => [1],
        'my-courses.unfinished' => [1],
        'my-courses.certificates' => [1],
        'conferences.student-index' => [1],
        'conferences.student-recordings' => [1],
        'complaints.index' => [0,1],
    ];

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
        $data = $request->only(['p_id', 'name', 'title', 'type', 'route', 'svg', 'sortable', 'status', 'visible']);
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
        if (! $menu) {
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
        if (! $menu) {
            return redirect()->back()->withErrors(['errorMsg' => 'menu Dose Not Exist']);
        }
        $data = $request->only(['p_id', 'name', 'title', 'type', 'route', 'svg', 'sortable', 'status', 'visible']);
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
        if (! $menu) {
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
        foreach ($menu->childrens as $submenu) {
            $submenu->checked = Bouncer::can($submenu->name, $submenu->type);
        }

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $menu,
        ], 200);

    }

    public function navigation()
    {
        /** @var User $user */
        $user = Auth::user();
        $userType = (int) $user->type;
        $menuUserTypeOverrides = $this->menuUserTypeOverrides;

        $resolveAllowedTypes = function (?string $route) use ($menuUserTypeOverrides) {
            if (! $route) {
                return null;
            }

            if (array_key_exists($route, $menuUserTypeOverrides)) {
                return $menuUserTypeOverrides[$route];
            }

            foreach ($menuUserTypeOverrides as $ruleRoute => $ruleTypes) {
                if (str_ends_with($ruleRoute, '.*')) {
                    $prefix = substr($ruleRoute, 0, -1);
                    if (str_starts_with($route, $prefix)) {
                        return $ruleTypes;
                    }
                }

                if (! str_contains($ruleRoute, '.') && str_starts_with($route, $ruleRoute.'.')) {
                    return $ruleTypes;
                }
            }

            return null;
        };

        // $abilities = $user->getAbilities();
        // var_dump($user);exit;
        // $bouncer = Bouncer::create($user);

        $navigation = [];
        // $navigation[] = [ "name" =>  "Dashboard", "icon" => "HomeIcon", "current" => true, "href" => "#" ];

        $menus = Menu::parents()->get();
        foreach ($menus as $menu) {
            $menuAllowedTypes = $resolveAllowedTypes($menu->route);
            if (is_array($menuAllowedTypes) && ! in_array($userType, $menuAllowedTypes, true)) {
                continue;
            }

            $isCanAny = false;
            $children = [];
            if (! empty($menu->type)) {
                $submenus = $menu->childrens()->where('invisible', 0)->where('status', 0)->get();
                // var_dump($submenus);exit;
                $menu->submenuCount = count($submenus);
                if ($menu->submenuCount > 0) {
                    foreach ($submenus as $submenu) {
                        $allowedTypes = $resolveAllowedTypes($submenu->route);
                        if (is_array($allowedTypes) && ! in_array($userType, $allowedTypes, true)) {
                            continue;
                        }

                        // echo '>>>'.$submenu->name.', '.$submenu->type;
                        if (is_array($allowedTypes) || Bouncer::can($submenu->name, $submenu->type)) {
                            // echo 'true';
                            $isCanAny = true;
                        } else {
                            // $isCanAny=true;
                            // echo 'false';
                            continue;
                        }
                        // echo '<<<'.$submenu->name.', '.$submenu->type;exit;
                        $routes = explode('|', $submenu->active_routes);
                        $isActive = false;
                        /*if(Route::is('dashboard.'.$menu->route.'.'.$submenu->route)){
                                $isActive=true;
                                $isOpen=true;
                        }*/
                        $submenu->isActive = $isActive;
                        $children[] = ['id' => $submenu->id, 'title' => 'global.'.$submenu->title, 'type' => 'child', 'link' => $submenu->route, 'abilities' => $menu->id];
                    }
                    $menu->href = 'javascript:;';
                    $menu->submenuClass = ' menu-item-submenu ';
                } else {
                    // $menu->href='javascript:;';
                    // $routes = explode('|',$menu->active_routes);
                    continue;
                }

                if (! $isCanAny) {
                    continue;
                }

                /*foreach($submenus as $submenu){
                  if(Bouncer::cannot($submenu->name, $submenu->type)) continue;
                  $children [] = [ "title" =>  "global.".$submenu->title, "type"=> 'child', "link" => $submenu->route ];
                }*/
                $navigation[] = [
                    'id' => $menu->id,
                    'title' => 'global.'.$menu->title,
                    'type' => 'parent',
                    'abilities' => $menu->id,
                    'link' => '#',
                    'icon' => $menu->svg,
                    'current' => false,
                    'children' => $children,
                ];
            } else {
                $submenus = $menu->childrens()->where('invisible', 0)->where('status', 0)->get();
                foreach ($submenus as $submenu) {
                    $submenuAllowedTypes = $resolveAllowedTypes($submenu->route);
                    if (is_array($submenuAllowedTypes) && ! in_array($userType, $submenuAllowedTypes, true)) {
                        continue;
                    }

                    $subsubmenus = $submenu->childes()->where('status', 0)->get();
                    $isCanAnySubmenu = false;
                    $submenu->submenuCount = count($subsubmenus);

                    if ($submenu->submenuCount > 0) {
                        foreach ($subsubmenus as $subsubmenu) {
                            $allowedTypes = $resolveAllowedTypes($subsubmenu->route);
                            if (is_array($allowedTypes) && ! in_array($userType, $allowedTypes, true)) {
                                continue;
                            }

                            if (is_array($allowedTypes) || Bouncer::can($subsubmenu->name, $subsubmenu->type)) {
                                $isCanAnySubmenu = true;
                                $isCanAny = true;
                            } else {

                                continue;
                            }

                        }
                    }
                    if (! $isCanAnySubmenu) {
                        continue;
                    }
                    $children[] = ['id' => $submenu->id, 'title' => 'global.'.$submenu->title, 'type' => 'child', 'link' => $submenu->route, 'abilities' => $submenu->id];
                }
                // var_dump($children);exit;
                if (! $isCanAny) {
                    continue;
                }

                $navigation[] = [
                    'id' => $menu->id,
                    'title' => 'global.'.$menu->title,
                    'type' => 'parent',
                    'abilities' => 0,
                    'link' => '#',
                    'icon' => $menu->svg,
                    'current' => false,
                    'children' => $children,
                    'submenu' => $subsubmenus,

                ];
            }
        }

        // dd($navigation);
        return $navigation;
    }
}
