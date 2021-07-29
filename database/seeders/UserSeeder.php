<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $user->name = 'Tam';
        $user->email = 'tambeo@gmail.com';
        $user->password = Hash::make('tam123');
        $user->save();

        $user = new User();
        $user->name = 'Thanh';
        $user->email = 'thanhchuate@gmail.com';
        $user->password = Hash::make('thanh123');
        $user->save();

        $user=new User();
        $user->name='Hai';
        $user->email = 'violet@gmail.com';
        $user->password = Hash::make('hai123');
        $user->save();
    }
}
