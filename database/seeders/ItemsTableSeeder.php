<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chaves
        Item::create([
            'name' => 'Chave de Bronze',
            'description' => 'Uma chave antiga feita de bronze, com símbolos estranhos gravados.',
            'type' => 'chave',
            'effect' => 'Abre a porta da Sala de Entrada.',
        ]);

        Item::create([
            'name' => 'Chave de Prata',
            'description' => 'Uma chave brilhante feita de prata, com um cristal azul na ponta.',
            'type' => 'chave',
            'effect' => 'Abre a porta do Laboratório Abandonado.',
        ]);

        Item::create([
            'name' => 'Chave de Ouro',
            'description' => 'Uma chave ornamentada feita de ouro, com runas antigas gravadas.',
            'type' => 'chave',
            'effect' => 'Abre a porta da Biblioteca Secreta.',
        ]);

        // Ferramentas
        Item::create([
            'name' => 'Lanterna',
            'description' => 'Uma lanterna portátil que ilumina áreas escuras.',
            'type' => 'ferramenta',
            'effect' => 'Permite ver em salas escuras e revelar pistas ocultas.',
        ]);

        Item::create([
            'name' => 'Kit de Arrombamento',
            'description' => 'Um conjunto de ferramentas para abrir fechaduras simples.',
            'type' => 'ferramenta',
            'effect' => 'Permite tentar abrir portas trancadas sem a chave correta, com chance de falha.',
        ]);

        Item::create([
            'name' => 'Detector de Armadilhas',
            'description' => 'Um dispositivo que emite um bipe quando armadilhas estão próximas.',
            'type' => 'ferramenta',
            'effect' => 'Alerta sobre a presença de armadilhas em um raio de 2 metros.',
        ]);

        // Consumíveis
        Item::create([
            'name' => 'Poção de Cura',
            'description' => 'Um frasco com líquido vermelho brilhante que restaura a saúde.',
            'type' => 'consumível',
            'effect' => 'Restaura 20 pontos de vida quando consumido.',
        ]);

        Item::create([
            'name' => 'Antídoto',
            'description' => 'Um frasco com líquido verde que neutraliza venenos.',
            'type' => 'consumível',
            'effect' => 'Remove efeitos de veneno e previne dano por 5 minutos.',
        ]);

        Item::create([
            'name' => 'Pergaminho de Dica',
            'description' => 'Um pergaminho mágico que revela dicas sobre desafios.',
            'type' => 'consumível',
            'effect' => 'Fornece uma dica para o desafio atual quando usado.',
        ]);

        // Artefatos
        Item::create([
            'name' => 'Amuleto de Proteção',
            'description' => 'Um amuleto antigo que brilha com uma luz azul suave.',
            'type' => 'artefato',
            'effect' => 'Reduz o dano recebido de armadilhas em 50%.',
        ]);

        Item::create([
            'name' => 'Anel da Sabedoria',
            'description' => 'Um anel de prata com uma pedra roxa que pulsa com energia.',
            'type' => 'artefato',
            'effect' => 'Fornece dicas adicionais para enigmas e quebra-cabeças.',
        ]);

        Item::create([
            'name' => 'Medalhão do Tempo',
            'description' => 'Um medalhão dourado com um relógio de areia no centro.',
            'type' => 'artefato',
            'effect' => 'Permite voltar no tempo por 30 segundos uma vez por fase.',
        ]);
    }
}
