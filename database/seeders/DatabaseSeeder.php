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

        $users = \App\Models\User::get();
        foreach($users as $user){
          \App\Models\Account::create([
            'user_id' => $user->id,
            'remaining_balance' => rand(111111, 999999) / 100,
            'dummy_string1' => fake()->sentence,
            'dummy_string2' => fake()->sentence,
            'dummy_string3' => fake()->sentence,
            'dummy_string4' => fake()->sentence,
            'dummy_string5' => fake()->sentence,
            'dummy_string6' => fake()->sentence,
            'dummy_string7' => fake()->sentence,
            'dummy_string8' => fake()->sentence,
          ]);
        }

        for($i = 0; $i < 100; $i++){
          \App\Models\Transaction::create([
              'amount' => rand(100, 1000),
              'is_paid' => rand(0,1), 
          ]);
        }
    }
}
