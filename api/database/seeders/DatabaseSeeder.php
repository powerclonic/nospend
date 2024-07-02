<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->hasExpenses(3, [
                'due_date' => today()
            ])
            ->create([
                'name' => 'Matheus Dresch',
                'email' => 'matheus@dresch.dev',
                'password' => 'password'
            ]);
    }
}
