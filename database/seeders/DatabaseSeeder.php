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
        // Criar um usuário de teste se não existir
        if (!\App\Models\User::where('email', 'jogador@teste.com')->exists()) {
            \App\Models\User::factory()->create([
                'name' => 'Jogador Teste',
                'email' => 'jogador@teste.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Executar os seeders
        $this->call([
            PhasesTableSeeder::class,
            ItemsTableSeeder::class,
            ChallengesTableSeeder::class,
            TrapsTableSeeder::class,
            NpcsTableSeeder::class,
        ]);
    }
}
