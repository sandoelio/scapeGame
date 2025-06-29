<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Npc;

class NpcsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // NPCs para a Fase 1
        Npc::create([
            'name' => 'Velho Bibliotecário',
            'description' => 'Um senhor idoso com óculos grossos e um livro antigo nas mãos.',
            'dialog' => 'Bem-vindo à Biblioteca dos Enigmas. Muitos tentaram escapar, poucos conseguiram. Lembre-se: às vezes, a resposta está na primeira letra de cada palavra.',
            'phase_id' => 1,
        ]);

        Npc::create([
            'name' => 'Aprendiz de Mago',
            'description' => 'Um jovem vestindo um manto azul com símbolos mágicos bordados.',
            'dialog' => 'Os números têm poder! Especialmente aqueles que não podem ser divididos, exceto por 1 e por eles mesmos. Eles são a chave para muitos segredos.',
            'phase_id' => 1,
        ]);

        // NPCs para a Fase 2
        Npc::create([
            'name' => 'Guarda Misterioso',
            'description' => 'Um homem alto usando uma máscara que cobre metade do rosto e uma armadura reluzente.',
            'dialog' => 'Para abrir o cofre, você precisa encontrar os números certos. Pense em combinações onde a soma e o produto têm valores específicos.',
            'phase_id' => 2,
        ]);

        Npc::create([
            'name' => 'Sacerdotisa Elemental',
            'description' => 'Uma mulher com vestes coloridas representando os quatro elementos.',
            'dialog' => 'Os elementos seguem um ciclo natural. A Terra nutre o Ar, o Ar alimenta o Fogo, o Fogo aquece a Água, e a Água nutre a Terra.',
            'phase_id' => 2,
        ]);

        // NPCs para a Fase 3
        Npc::create([
            'name' => 'Espectro do Espelho',
            'description' => 'Uma figura etérea que aparece apenas como um reflexo nos espelhos do labirinto.',
            'dialog' => 'No labirinto de espelhos, a verdade se distorce. Procure pelo enigma que cresce quando você remove partes dele, não quando adiciona.',
            'phase_id' => 3,
        ]);

        Npc::create([
            'name' => 'Guardião das Chaves',
            'description' => 'Um ser antigo com chaves de todos os tipos penduradas em seu corpo.',
            'dialog' => 'Cinco fechaduras, quatro chaves. O impossível se torna possível quando você pensa além do óbvio. Às vezes, unir é melhor que separar.',
            'phase_id' => 3,
        ]);
    }
}
