<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Challenge;
use App\Models\Code;

class ChallengesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desafios para a Fase 1
        $challenge1 = Challenge::create([
            'name' => 'O Enigma da Porta',
            'description' => 'Uma porta antiga com um mecanismo de fechadura complexo. Há uma inscrição que diz: "Sou o início do fim, o começo da eternidade, e o fim do tempo e espaço."',
            'type' => 'puzzle',
            'difficulty' => 'fácil',
            'points' => 100,
            'phase_id' => 1,
            'order' => 1,
        ]);

        Code::create([
            'code' => 'E',
            'challenge_id' => $challenge1->id,
            'hint' => 'Pense na primeira letra de cada palavra mencionada.',
        ]);

        $challenge2 = Challenge::create([
            'name' => 'A Sequência Misteriosa',
            'description' => 'Um painel com números: 2, 3, 5, 7, 11, ?. Qual é o próximo número da sequência?',
            'type' => 'math',
            'difficulty' => 'médio',
            'points' => 150,
            'phase_id' => 1,
            'order' => 2,
        ]);

        Code::create([
            'code' => '13',
            'challenge_id' => $challenge2->id,
            'hint' => 'Estes são números especiais na matemática.',
        ]);

        // Desafios para a Fase 2
        $challenge3 = Challenge::create([
            'name' => 'O Cofre Antigo',
            'description' => 'Um cofre com um teclado numérico. Ao lado, há uma nota: "A soma dos dígitos é 10, o produto é 24."',
            'type' => 'logic',
            'difficulty' => 'médio',
            'points' => 200,
            'phase_id' => 2,
            'order' => 1,
        ]);

        Code::create([
            'code' => '6834',
            'challenge_id' => $challenge3->id,
            'hint' => 'Existem quatro dígitos que satisfazem essas condições.',
        ]);

        $challenge4 = Challenge::create([
            'name' => 'As Estátuas Giratórias',
            'description' => 'Quatro estátuas que precisam ser giradas na ordem correta. Cada uma representa um elemento: Fogo, Água, Terra e Ar.',
            'type' => 'sequence',
            'difficulty' => 'difícil',
            'points' => 250,
            'phase_id' => 2,
            'order' => 2,
        ]);

        Code::create([
            'code' => 'ATFA',
            'challenge_id' => $challenge4->id,
            'hint' => 'A ordem está relacionada ao ciclo natural dos elementos.',
        ]);

        // Desafios para a Fase 3
        $challenge5 = Challenge::create([
            'name' => 'O Labirinto de Espelhos',
            'description' => 'Um labirinto onde as paredes são espelhos. No centro, há um pedestal com um enigma: "Quanto mais você tira, maior eu fico. O que sou eu?"',
            'type' => 'riddle',
            'difficulty' => 'médio',
            'points' => 300,
            'phase_id' => 3,
            'order' => 1,
        ]);

        Code::create([
            'code' => 'BURACO',
            'challenge_id' => $challenge5->id,
            'hint' => 'Pense em algo que cresce quando você remove material dele.',
        ]);

        $challenge6 = Challenge::create([
            'name' => 'A Chave Final',
            'description' => 'Uma porta selada com cinco fechaduras. Cada uma requer uma chave diferente, mas você só tem quatro. Encontre uma maneira de abrir a porta.',
            'type' => 'puzzle',
            'difficulty' => 'difícil',
            'points' => 350,
            'phase_id' => 3,
            'order' => 2,
        ]);

        Code::create([
            'code' => 'FUSÃO',
            'challenge_id' => $challenge6->id,
            'hint' => 'Às vezes, combinar recursos é a solução.',
        ]);
    }
}
