<?php

use App\Models\Menu;
use App\Models\Permission;


function menu()
{
    $menu = new Menu();
    if (session()->get('user')->role_id == 1) {
        $data = $menu->where(['type' => 'primario', 'status' => 'active'])
            ->orderBy('position', 'ASC')
            ->get()
            ->getResult();
    } else {
        $permission = new Permission();
        $data = $permission->select('menus.*')
            ->where('role_id', session()->get('user')->role_id)
            ->where('menus.type', 'primario')
            ->join('menus', 'menus.id = permissions.menu_id')
            ->join('roles', 'roles.id = permissions.role_id')
            ->get()
            ->getResult();
    }
    return $data;
}

function menus(){
    $m_model = new Menu();
    $permission = new Permission();
    if (session()->get('user')->role_id == 1) {
        $data = $m_model->where(['type' => 'primario', 'status' => 'active'])
        ->orderBy('position', 'ASC')->findAll();
    } else {
        $data = $permission->select('menus.*')
            ->where([
                'role_id'       => session('user')->role_id,
                'menus.type'    => 'primario'
            ])
            ->join('menus', 'menus.id = permissions.menu_id')
            ->join('roles', 'roles.id = permissions.role_id')
            
            ->orderBy('position', 'ASC')
            ->findAll();
    }

    foreach ($data as $key => $menu) {
        if (session('user')->role_id == 1) {
            $menu->sub_menu = $m_model->where([
                'type'          => 'secundario',
                'status'        => 'active',
                'references'    => $menu->id
            ])
            ->orderBy('position', 'ASC')->findAll();
            foreach ($menu->sub_menu as $key => $sub_menu) {
                $sub_menu->base_url = urlOption($sub_menu->id);
            }
        }else {
            $menu->sub_menu = $permission->select('menus.*')
            ->where([
                'role_id'       => session('user')->role_id,
                'menus.type'    => 'secundario',
                'references'    => $menu->id
            ])
            ->join('menus', 'menus.id = permissions.menu_id')
            ->join('roles', 'roles.id = permissions.role_id')
            
            ->orderBy('position', 'ASC')
            ->findAll();
            foreach ($menu->sub_menu as $key => $sub_menu) {
                $sub_menu->base_url = urlOption($sub_menu->id);
            }
        }
        $menu->base_url = count($menu->sub_menu) > 0 ? urlOption() : urlOption($menu->id);
    }

    return $data;
}

function submenu($refences)
{
    $menu = new Menu();
    if (session()->get('user')->role_id == 1) {
        $data = $menu->where(['type' => 'secundario', 'status' => 'active', 'references' => $refences])
            ->get()
                ->getResult();
    } else {
        $permission = new Permission();
        $data = $permission->select('menus.*')
            ->where([
                'role_id'       => session('user')->role_id,
                'menus.type'    => 'secundario',
                'references'    => $refences
            ])
            ->where()
            ->join('menus', 'menus.id = permissions.menu_id')
            ->join('roles', 'roles.id = permissions.role_id')
            ->get()
            ->getResult();
    }
    return $data;
}

function countMenu($references)
{
    $menu = new Menu();
    $data = $menu->where(['type' => 'secundario', 'status' => 'active', 'references' => $references])
        ->get()
        ->getResult();
    if (count($data) > 0) {
        return true;
    }
    return false;
}

function urlOption($references = null)
{
    if ($references) {
        $menu = new Menu();
        $data = $menu->find($references);
        if ($data->component == 'table') {
            return base_url(["table", $data->url]);
        } else if ($data->component == 'controller') {
            return base_url(["dashboard", $data->url]);
        }
    } else {
        return 'JavaScript:void(0)';
    }

}

function isActive($data)
{
    if(base_url(uri_string()) == $data) {
        return 'active';
    }
}

function subActive($id){
    $m_model = new Menu();
    $data = $m_model->where([
        'type'          => 'secundario',
        'status'        => 'active',
        'references'    => $id
    ])->findAll();
    $valid = '';
    foreach($data as $menu){
        if(base_url(uri_string()) == urlOption($menu->id))
            $valid = 'active open';
    }
    return $valid;
}

