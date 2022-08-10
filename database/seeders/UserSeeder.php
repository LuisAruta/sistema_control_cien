<?php

namespace Database\Seeders;

use App\Constant\RolConstant;
use App\Models\Dependencia;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('123456');
        $user->documento_id = Documento::factory()->create()->id;
        $user->dependencia_id = Dependencia::where('cientifica', true)->inRandomOrder(1)->get()[0]->id;
        $user->perito = false;
        $user->nombre_completo = 'Admin Cientifica';
        $user->save();
        $user->assignRole(RolConstant::ADMIN_SISTEMAS);

    }
}
