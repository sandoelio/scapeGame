<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trap;

class TrapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Armadilhas para a Fase 1
        Trap::create([
            'name' => 'Piso Falso',
            'description' => 'Uma seção do piso que cede quando pisada, revelando espinhos afiados.',
            'damage' => 10,
            'phase_id' => 1,
            'trigger_condition' => 'Pisar na seção marcada do piso',
            'is_active' => true,
        ]);

        Trap::create([
            'name' => 'Dardos Envenenados',
            'description' => 'Dardos que disparam da parede quando um fio invisível é tocado.',
            'damage' => 15,
            'phase_id' => 1,
            'trigger_condition' => 'Tocar o fio invisível próximo à estante',
            'is_active' => true,
        ]);

        // Armadilhas para a Fase 2
        Trap::create([
            'name' => 'Gás Venenoso',
            'description' => 'Uma nuvem de gás tóxico que é liberada ao abrir um baú sem desativar o mecanismo de segurança.',
            'damage' => 20,
            'phase_id' => 2,
            'trigger_condition' => 'Abrir o baú sem desativar o mecanismo de segurança',
            'is_active' => true,
        ]);

        Trap::create([
            'name' => 'Lâminas Giratórias',
            'description' => 'Lâminas afiadas que surgem do chão e giram rapidamente quando um peso é colocado em uma plataforma específica.',
            'damage' => 25,
            'phase_id' => 2,
            'trigger_condition' => 'Colocar peso na plataforma marcada com um símbolo de caveira',
            'is_active' => true,
        ]);

        // Armadilhas para a Fase 3
        Trap::create([
            'name' => 'Teto Desabando',
            'description' => 'Parte do teto que desaba quando uma alavanca errada é puxada.',
            'damage' => 30,
            'phase_id' => 3,
            'trigger_condition' => 'Puxar a alavanca vermelha em vez da azul',
            'is_active' => true,
        ]);

        Trap::create([
            'name' => 'Sala em Chamas',
            'description' => 'Uma sala que subitamente se enche de chamas quando um símbolo incorreto é pressionado.',
            'damage' => 35,
            'phase_id' => 3,
            'trigger_condition' => 'Pressionar o símbolo do sol em vez do símbolo da lua',
            'is_active' => true,
        ]);
    }
}
