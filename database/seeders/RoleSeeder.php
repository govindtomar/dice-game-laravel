<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles() as $key => $value) {
            $role = new Role;
            $role->name = $value['name'];
            $role->slug = $value['slug'];
            $role->save();
        }
    }

    public function roles(){
        return [
            // [
            //     "name"  =>  "Super Administrator",
            //     "slug"  =>  "super-administrator"
            // ],
            [
                "name"  =>  "Administrator",
                "slug"  =>  "administrator"
            ],
            [
                "name"  =>  "Manager",
                "slug"  =>  "manager"
            ],
            [
                "name"  =>  "Supervisor",
                "slug"  =>  "supervisor"
            ],
            [
                "name"  =>  "Agent",
                "slug"  =>  "agent"
            ]
        ];
    }
}