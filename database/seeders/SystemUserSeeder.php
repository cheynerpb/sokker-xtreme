<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            'password' => '$2y$10$DNg6fKRaNJu7SDbe0tpuReNf6ZM5hRMSwUx57yhYNswqde883Y4mS',
            'active' => true
        ]);
    }
}
