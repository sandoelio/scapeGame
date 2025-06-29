<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Phase;
use App\Models\Challenge;
use App\Models\Code;
use App\Models\Trap;
use App\Models\Npc;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\PlayerProgress;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Exibe a página inicial do jogo.
     */
    public function index()
    {
        $user = Auth::user();
        $characters = Character::where('user_id', $user->id)->get();
        $phases = Phase::all();

        // Pega o personagem ativo (o mesmo método usado nos outros lugares)
        $character = $this->getCurrentCharacter();

        // Carrega os progressos desse personagem
        $playerProgress = PlayerProgress::where('character_id', $character->id)->get();

        // Carrega histórico de ações, se precisar
        $actionHistory = ActionHistory::where('character_id', $character->id)->latest()->get();

        return view('game.index', compact('characters', 'phases', 'playerProgress', 'actionHistory'));
    }


    /**
     * Exibe a fase atual do jogador.
     */
    public function showPhase($phaseId)
    {
        $phase = Phase::with(['challenges', 'npcs', 'traps'])->findOrFail($phaseId);
        $character = $this->getCurrentCharacter();

        // Registrar ou atualizar o progresso do jogador nesta fase
        $progress = PlayerProgress::firstOrCreate(
            ['character_id' => $character->id, 'phase_id' => $phase->id],
            ['status' => 'in_progress']
        );

        // Registrar ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'phase_id' => $phase->id,
            'action_type' => 'enter_phase',
            'description' => 'Entrou na fase ' . $phase->name,
            'result' => 'Fase iniciada com sucesso',
        ]);

        // Buscar itens do personagem nessa fase via inventory
        $items = Inventory::with('item')
            ->where('character_id', $character->id)
            ->get()
            ->pluck('item');

        // Verificar se todos os desafios foram concluídos
        $allChallengesCompleted = $phase->challenges->every(function ($challenge) use ($character) {
            return PlayerProgress::where('character_id', $character->id)
                                ->where('challenge_id', $challenge->id)
                                ->where('status', 'completed')
                                ->exists();
        });

        // Variáveis que você precisa enviar para a view
        $npcs = $phase->npcs;
        $traps = $phase->traps;

        return view('game.phase', compact(
            'phase',
            'character',
            'progress',
            'allChallengesCompleted',
            'npcs',
            'traps',
            'items'
        ));
    }


    /**
     * Processa a tentativa de resolver um desafio.
     */
    public function solveChallenge(Request $request, $challengeId)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $challenge = Challenge::with('codes')->findOrFail($challengeId);
        $character = $this->getCurrentCharacter();
        $inputCode = $request->input('code');
        
        // Verificar se o código está correto
        $correctCode = $challenge->codes->where('code', $inputCode)->first();

        if ($correctCode) {
            // Atualizar progresso do jogador
            $progress = PlayerProgress::firstOrCreate(
                ['character_id' => $character->id, 'challenge_id' => $challenge->id],
                ['phase_id' => $challenge->phase_id, 'status' => 'in_progress']
            );
            
            $progress->status = 'completed';
            $progress->completed_at = now();
            $progress->save();
            
            // Adicionar pontos de experiência ao personagem
            $character->experience += $challenge->points;
            
            // Verificar se o personagem subiu de nível (a cada 1000 pontos)
            if ($character->experience >= $character->level * 1000) {
                $character->level += 1;
                $character->max_health_points += 10;
                $character->health_points = $character->max_health_points;
            }
            
            $character->save();
            
            // Registrar ação no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'phase_id' => $challenge->phase_id,
                'action_type' => 'solve_challenge',
                'description' => 'Resolveu o desafio ' . $challenge->name,
                'result' => 'Desafio completado com sucesso',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Código correto! Desafio completado.',
                'points' => $challenge->points,
                'character' => $character,
            ]);
        } else {
            // Registrar tentativa falha no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'phase_id' => $challenge->phase_id,
                'action_type' => 'attempt_challenge',
                'description' => 'Tentou resolver o desafio ' . $challenge->name,
                'result' => 'Código incorreto',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Código incorreto. Tente novamente.',
            ]);
        }
    }

    /**
     * Processa a interação com uma armadilha.
     */
    public function triggerTrap($trapId)
    {
        $trap = Trap::findOrFail($trapId);
        $character = $this->getCurrentCharacter();
        
        // Aplicar dano ao personagem
        $character->health_points -= $trap->damage;
        
        // Verificar se o personagem morreu
        if ($character->health_points <= 0) {
            $character->health_points = 0;
            $character->save();
            
            // Registrar morte no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'phase_id' => $trap->phase_id,
                'action_type' => 'death',
                'description' => 'Morreu ao ativar a armadilha ' . $trap->name,
                'result' => 'Personagem morto',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Você ativou uma armadilha e morreu!',
                'damage' => $trap->damage,
                'character' => $character,
            ]);
        }
        
        $character->save();
        
        // Registrar ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'phase_id' => $trap->phase_id,
            'action_type' => 'trigger_trap',
            'description' => 'Ativou a armadilha ' . $trap->name,
            'result' => 'Sofreu ' . $trap->damage . ' de dano',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Você ativou uma armadilha!',
            'damage' => $trap->damage,
            'character' => $character,
        ]);
    }

    /**
     * Processa a interação com um NPC.
     */
    public function talkToNpc($npcId)
    {
        $npc = Npc::findOrFail($npcId);
        $character = $this->getCurrentCharacter();
        
        // Registrar ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'phase_id' => $npc->phase_id,
            'action_type' => 'talk_to_npc',
            'description' => 'Conversou com ' . $npc->name,
            'result' => 'Diálogo concluído',
        ]);
        
        return response()->json([
            'success' => true,
            'npc' => $npc,
            'dialog' => $npc->dialog,
        ]);
    }

    /**
     * Processa o uso de um item.
     */
    public function useItem(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|integer',
        ]);
        
        $inventoryItem = Inventory::with('item')->findOrFail($request->input('inventory_id'));
        $character = $this->getCurrentCharacter();
        
        // Verificar se o item pertence ao personagem
        if ($inventoryItem->character_id !== $character->id) {
            return response()->json([
                'success' => false,
                'message' => 'Este item não pertence ao seu personagem.',
            ], 403);
        }
        
        $item = $inventoryItem->item;
        $result = '';
        
        // Processar o efeito do item com base no tipo
        switch ($item->type) {
            case 'consumível':
                if ($item->name === 'Poção de Cura') {
                    $healAmount = 20;
                    $character->health_points = min($character->health_points + $healAmount, $character->max_health_points);
                    $character->save();
                    $result = 'Recuperou ' . $healAmount . ' pontos de vida';
                    
                    // Consumir o item
                    if ($inventoryItem->quantity > 1) {
                        $inventoryItem->quantity -= 1;
                        $inventoryItem->save();
                    } else {
                        $inventoryItem->delete();
                    }
                }
                break;
                
            case 'chave':
                $result = 'Usou a chave';
                break;
                
            default:
                $result = 'Usou o item';
                break;
        }
        
        // Registrar ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'phase_id' => $character->progress->first()->phase_id ?? 1,
            'action_type' => 'use_item',
            'description' => 'Usou o item ' . $item->name,
            'result' => $result,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Item usado com sucesso.',
            'effect' => $item->effect,
            'result' => $result,
            'character' => $character,
            'inventory' => $inventoryItem,
        ]);
    }

    /**
     * Obtém o personagem atual do usuário logado.
     */
    private function getCurrentCharacter()
    {
        $user = Auth::user();
        $characterId = session('active_character_id');

        if ($characterId) {
            $character = Character::where('user_id', $user->id)->where('id', $characterId)->first();
            if ($character) {
                return $character;
            }
        }

        // Se não tem personagem ativo salvo, pega o primeiro e salva na sessão
        $character = Character::where('user_id', $user->id)->first();
        
        if (!$character) {
            $character = Character::create([
                'user_id' => $user->id,
                'name' => $user->name . '\'s Character',
                'health_points' => 100,
                'max_health_points' => 100,
                'level' => 1,
                'experience' => 0,
            ]);
        }
        
        session(['active_character_id' => $character->id]);
        
        return $character;
    }

}
