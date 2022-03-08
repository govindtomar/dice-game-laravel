<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Route;
use App\Models\PermissionRoute;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $block_modules = [
            'login',
            'register',
            'logout',
            'routes',
        ];


        $filter_modules = [];
        foreach ($this->routes() as $key => $route) {
            $mod = explode("/", $route['uri'])[0];
            if(!in_array($mod, $filter_modules)){
                $filter_modules[] = $mod;
            }
        }
        
        $modules = [];
        foreach ($filter_modules as $key => $module) {
            $r = [];
            if (!in_array($module, $block_modules)) {
                foreach ($this->routes() as $rkey => $route) {
                    if ($module == explode("/", $route['uri'])[0]) {
                       $r[] = [
                            'uri' => $route['uri'],
                            'name' => $route['name']
                        ];
                    }
                }
                $modules[] = [
                    "name"  =>  $module,
                    "route" =>  $r
                ];
            }
        }

        foreach ($modules as $key => $module) {
            $permission = new Permission;
            $permission->name = ucfirst($module['name']) . ' Module';
            $permission->slug = $module['name'];
            $permission->save();
            foreach ($module['route'] as $key => $route) {
                $pr = new PermissionRoute;
                $pr->api = $route['uri'];
                $pr->url = $this->permission_url($route['uri'], $module['name']);
                $pr->route = $route['name'];
                $pr->name = ucfirst($this->permission_name($route['uri'], $module['name']));
                $pr->permission_id = $permission->id;
                $pr->save();
            }
        }        
    }

    public function routes(){
        $routes = collect(\Route::getRoutes())->map(function ($route) { 
            return[
                "uri"   =>  $route->uri(),
                "name"  =>  $route->getName()
            ];
        });

        $route = [];
        foreach ($routes as $r) {
            if (strpos($r['uri'], 'api') !== false) {
                $route[] = [
                    "uri"   =>  str_replace("api/", "", $r['uri']),
                    "name"  =>  $r['name']
                ];
            }
        }
        return $route;
    }

    public function permission_name($uri, $module_name){
        foreach (['create', 'view', 'update', 'delete', 'status'] as $value) {
            if (strpos($uri, $value) !== false) {
                return $value;
            }
            if (strpos($uri, '/{') !== false) {
                return 'more details';
            }
            if ($module_name == $uri) {
                return 'view';
            }
        }
        return $uri;
    }

    public function permission_url($uri, $module_name){
        if (strpos($uri, 'update') !== false) {
            return str_replace("update", "edit/:id", $uri);           
        }
        if (strpos($uri, '{slug}') !== false) {
            $uri = str_replace("{slug}", ":slug", $uri);
        }
        if (strpos($uri, '{id}') !== false) {
            $uri = str_replace("{id}", ":id", $uri);
        }
        return $uri;
    }
}
