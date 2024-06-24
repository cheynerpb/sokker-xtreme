<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\SystemUser;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemUser::create([
            'name' => 'Administrador',
            'email' => 'cheynerpb@gmail.com',
            'password' => Hash::make('123456789'),
            'active' => true
        ]);
    }
}
