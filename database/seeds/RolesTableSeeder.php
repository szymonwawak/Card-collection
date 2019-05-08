<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\Role();
        $role->name = 'User';
        $role->save();


        $role = new \App\Role();
        $role->name = 'Moderator';
        $role->save();


        $role = new \App\Role();
        $role->name = 'Administrator';
        $role->save();
    }
}
