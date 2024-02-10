<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10000)->create();

        \App\Models\User::factory()->create([
            'name' => fake()->name,
            'email' => fake()->email,
        ]);


        $users = \App\Models\User::get();
        foreach($users as $user){
          \App\Models\Account::create([
            'user_id' => $user->id,
            'remaining_balance' => rand (111111, 999999) / 100,
            'frozen' => false
          ]);
        }
    }
}
