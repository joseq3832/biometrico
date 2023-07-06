<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  
    public function run()
    {
        $useradmin=User::create([
            'name' => 'admin paul',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'rol' => '1',
            ]);
                    
        $user1=User::create([
            'name' => 'usuario Marcos',
            'email' => 'user@gmail.com',
            'password' => Hash::make('admin'),
            'rol' => '2',
            ]);
        $user1=User::create([
            'name' => 'usuario profesor',
            'email' => 'moderador@gmail.com',
            'password' => Hash::make('admin'),
            'rol' => '3',
            ]);
    }
}
