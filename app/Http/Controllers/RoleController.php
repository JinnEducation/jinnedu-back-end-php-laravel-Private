<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;

use App\Http\Requests\Role\RoleRequest;

use Bouncer;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
         $items = Role::query('roles.*')->Roles();
         
         if(!empty($request->q)){
            $items->whereRaw(filterTextDB('roles.title').' like ?',['%'.filterText($request->q).'%']);
            $items->distinct();
        }
         
        //  $items = $items->paginate($limit);
        $items = paginate($items, $limit);

         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function store(RoleRequest $request)
    {
        return $this->storeUpdateRequest($request);
    }
    
    public function update(RoleRequest $request, $id)
    {
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id=0)
    {
        $data = $request->only(['name','title']);
        
        if($id>0){
            $item = Bouncer::role()->find($id);
            if(!$item) return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ] , 200);
            $item->update($data);
        }else {
            $item = Bouncer::role()->create($data);
        }
        
        $this->setPermissions($item,$request);
        
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }

    public function setPermissions($r,$request){
        $role = Bouncer::role()->find($r->id);
        foreach($request->menus as $item){
            $menu =  Menu::find($item['id']);
            if($menu) $this->setSubPermissions($role,$menu,$item);
        }
    }

    public function setSubPermissions($role,$menu,$request){
        Bouncer::disallow($role->name)->to($menu->type);
        $disallow=true;
        foreach($request['childrens'] as $item){
            $child =  Menu::find($item['id']);
            if($child && $item['allow']){
                Bouncer::allow($role->name)->to($child->name, $child->type);
                $disallow=false;
                //echo 'allow >>> Role:'.$role->name.' - name:'.$child->name.' - type:'.$child->type.'$$$';
            }
            else {
                Bouncer::disallow($role->name)->to($child->name, $child->type);
                //echo 'disallow >>> Role:'.$role->name.' - name:'.$child->name.' - type:'.$child->type.'$$$';
            }
        }

        //if($disallow) Bouncer::disallow($role->name)->toManage($menu->type);
    }
    
    
    public function menus($role=0){
        $item=null;
        if($role>0){
            //echo '123';exit;
            $item = Bouncer::role()->find($role);
        } 
        
        $menus=Menu::parents()->get();
        foreach($menus as $menu){
            if($menu->type==''){
                $menu->childrens = $menu->childes()->get();
                foreach($menu->childrens as $submenu){
                    $submenu->childrens = $submenu->childes()->get();
                    foreach($submenu->childrens as $subnav){
                        $subnav->checked = false;
                        if(isset($item)) $subnav->checked = $item->can($subnav->name, $subnav->type);
                        //$item->can($subnav->name, $subnav->type)?'checked="checked"':'':''; 
                    }
                }
            }else{
                $menu->childrens = $menu->childes()->get();
                foreach($menu->childrens as $subnav){
                    $subnav->checked = false;
                    if(isset($item)) $subnav->checked = $item->can($subnav->name, $subnav->type);
                    //$item->can($subnav->name, $subnav->type)?'checked="checked"':'':''; 
                }
            }
        }
        
        return $menus;
    }
    
    public function show($id)
    {
        $item = Role::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $item = Role::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
}
