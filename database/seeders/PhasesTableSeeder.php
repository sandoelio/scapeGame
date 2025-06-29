<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Phase;
use App\Models\Challenge;
use App\Models\Code;
use App\Models\Trap;
use App\Models\Npc;

class PhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fase 1: Sala de Entrada
        $phase1 = Phase::create([
            'name' => 'Sala de Entrada',
            'description' => 'Uma sala escura com uma porta trancada. Há uma mesa com um enigma e uma estante com livros.',
            'order' => 1,
            'is_active' => true,
        ]);

        // Desafios da Fase 1
        $challenge1 = Challenge::create([
            'phase_id' => $phase1->id,
            'name' => 'Enigma da Porta',
            'description' => 'Decifre o enigma para obter o código da porta.',
            'type' => 'enigma',
            'difficulty' => 'fácil',
            'points' => 100,
            'order' => 1,
        ]);

        // Código para o desafio
        Code::create([
            'challenge_id' => $challenge1->id,
            'code' => '1234',
            'hint' => 'Procure nos livros da estante por números destacados.',
        ]);

        // Armadilha da Fase 1
        Trap::create([
            'phase_id' => $phase1->id,
            'name' => 'Piso Falso',
            'description' => 'Um piso que cede ao pisar, causando dano.',
            'damage' => 10,
            'trigger_condition' => 'Pisar no centro da sala sem verificar o piso.',
        ]);

        // NPC da Fase 1
        Npc::create([
            'phase_id' => $phase1->id,
            'name' => 'Guardião da Entrada',
            'description' => 'Um velho senhor que guarda a entrada do labirinto.',
            'dialog' => 'Bem-vindo ao Labirinto dos Enigmas. Cuidado com onde pisa e preste atenção aos detalhes.',
            'is_friendly' => true,
        ]);

        // Fase 2: Laboratório Abandonado
        $phase2 = Phase::create([
            'name' => 'Laboratório Abandonado',
            'description' => 'Um laboratório antigo com equipamentos quebrados e frascos de substâncias coloridas.',
            'order' => 2,
            'is_active' => true,
        ]);

        // Desafios da Fase 2
        $challenge2 = Challenge::create([
            'phase_id' => $phase2->id,
            'name' => 'Mistura Química',
            'description' => 'Combine as substâncias corretas para criar a chave química.',
            'type' => 'puzzle',
            'difficulty' => 'médio',
            'points' => 200,
            'order' => 1,
        ]);

        // Código para o desafio
        Code::create([
            'challenge_id' => $challenge2->id,
            'code' => 'AZUL-VERMELHO-VERDE',
            'hint' => 'A ordem das cores está relacionada aos elementos na tabela periódica.',
        ]);

        // Armadilha da Fase 2
        Trap::create([
            'phase_id' => $phase2->id,
            'name' => 'Gás Tóxico',
            'description' => 'Gás que é liberado ao misturar substâncias erradas.',
            'damage' => 20,
            'trigger_condition' => 'Misturar as substâncias amarela e roxa.',
        ]);

        // NPC da Fase 2
        Npc::create([
            'phase_id' => $phase2->id,
            'name' => 'Dr. Quimera',
            'description' => 'Um cientista louco que vive no laboratório.',
            'dialog' => 'Ah, um visitante! Cuidado com minhas experiências. Algumas são... explosivas! Hahaha!',
            'is_friendly' => true,
        ]);

        // Fase 3: Biblioteca Secreta
        $phase3 = Phase::create([
            'name' => 'Biblioteca Secreta',
            'description' => 'Uma vasta biblioteca com estantes até o teto e um pedestal central com um livro antigo.',
            'order' => 3,
            'is_active' => true,
        ]);

        // Desafios da Fase 3
        $challenge3 = Challenge::create([
            'phase_id' => $phase3->id,
            'name' => 'Livro dos Segredos',
            'description' => 'Encontre a página correta no livro antigo para revelar a passagem secreta.',
            'type' => 'busca',
            'difficulty' => 'difícil',
            'points' => 300,
            'order' => 1,
        ]);

        // Código para o desafio
        Code::create([
            'challenge_id' => $challenge3->id,
            'code' => 'PÁGINA 394',
            'hint' => 'O número da página está escondido em um anagrama nas lombadas dos livros.',
        ]);

        // Armadilha da Fase 3
        Trap::create([
            'phase_id' => $phase3->id,
            'name' => 'Livro Amaldiçoado',
            'description' => 'Um livro que causa dano ao ser aberto.',
            'damage' => 15,
            'trigger_condition' => 'Abrir o livro com capa vermelha na terceira estante.',
        ]);

        // NPC da Fase 3
        Npc::create([
            'phase_id' => $phase3->id,
            'name' => 'Bibliotecário Fantasma',
            'description' => 'O espírito de um antigo bibliotecário que ainda cuida dos livros.',
            'dialog' => 'Silêncio na biblioteca! Os livros guardam segredos para aqueles que sabem ler nas entrelinhas.',
            'is_friendly' => true,
        ]);
    }
}
