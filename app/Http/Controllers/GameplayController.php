<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Character;
use App\Models\Code;
use App\Models\Trap;
use App\Models\Npc;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\Phase;
use App\Models\PlayerProgress;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\Auth;

class GameplayController extends Controller
{
    /**
     * Construtor do controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Resolver um desafio
     */
    public function solveChallenge(Request $request, $challengeId)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Verificar se o código está correto
        $code = Code::where('challenge_id', $challengeId)->first();
        
        if ($code->code === $request->input('code')) {
            // Atualizar o progresso do jogador
            $progress = PlayerProgress::updateOrCreate(
                [
                    'character_id' => $character->id,
                    'challenge_id' => $challengeId
                ],
                [
                    'status' => 'completed',
                    'completed_at' => now()
                ]
            );
            
            // Adicionar pontos de experiência ao personagem
            $character->experience += $challenge->points;
            
            // Verificar se o personagem subiu de nível
            $experienceNeeded = $character->level * 1000;
            if ($character->experience >= $experienceNeeded) {
                $character->level += 1;
                $character->max_health_points += 10;
                $character->health_points = $character->max_health_points;
            }
            
            $character->save();
            
            // Registrar a ação no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'action_type' => 'challenge_completed',
                'description' => 'Completou o desafio: ' . $challenge->name,
                'points' => $challenge->points
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Desafio resolvido com sucesso!',
                'points' => $challenge->points,
                'level_up' => $character->experience >= $experienceNeeded
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Código incorreto. Tente novamente.'
            ]);
        }
    }
    
    /**
     * Interagir com uma armadilha
     */
    public function triggerTrap(Request $request, $trapId)
    {
        $trap = Trap::findOrFail($trapId);
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Verificar se a armadilha está ativa
        if ($trap->is_active) {
            // Causar dano ao personagem
            $character->health_points -= $trap->damage;
            
            // Verificar se o personagem morreu
            if ($character->health_points <= 0) {
                $character->health_points = 0;
                $character->save();
                
                // Registrar a morte no histórico
                ActionHistory::create([
                    'character_id' => $character->id,
                    'action_type' => 'character_died',
                    'description' => 'Morreu ao cair na armadilha: ' . $trap->name
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Você caiu na armadilha e morreu! Seu personagem foi enviado de volta ao início da fase.',
                    'damage' => $trap->damage,
                    'health' => $character->health_points,
                    'died' => true
                ]);
            }
            
            $character->save();
            
            // Registrar a ação no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'action_type' => 'trap_triggered',
                'description' => 'Caiu na armadilha: ' . $trap->name,
                'damage' => $trap->damage
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Você caiu na armadilha: ' . $trap->name . '. Sofreu ' . $trap->damage . ' pontos de dano.',
                'damage' => $trap->damage,
                'health' => $character->health_points,
                'died' => false
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Você evitou a armadilha com sucesso!'
            ]);
        }
    }
    
    /**
     * Interagir com um NPC
     */
    public function talkToNpc($npcId)
    {
        $npc = Npc::findOrFail($npcId);
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Registrar a interação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'action_type' => 'npc_interaction',
            'description' => 'Conversou com: ' . $npc->name
        ]);
        
        return response()->json([
            'success' => true,
            'npc' => $npc
        ]);
    }
    
    /**
     * Usar um item
     */
    public function useItem(Request $request, $itemId)
    {
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Verificar se o personagem possui o item
        $inventory = Inventory::where('character_id', $character->id)
            ->where('item_id', $itemId)
            ->first();
        
        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Você não possui este item.'
            ]);
        }
        
        $item = Item::find($itemId);
        
        // Aplicar efeito do item com base no tipo
        switch ($item->type) {
            case 'health_potion':
                $healAmount = $item->effect_value;
                $character->health_points = min($character->health_points + $healAmount, $character->max_health_points);
                $character->save();
                
                $message = 'Você usou ' . $item->name . ' e recuperou ' . $healAmount . ' pontos de vida.';
                break;
                
            case 'key':
                $message = 'Você usou ' . $item->name . '. Agora pode acessar novas áreas.';
                break;
                
            case 'tool':
                $message = 'Você usou ' . $item->name . ' para interagir com o ambiente.';
                break;
                
            case 'artifact':
                $message = 'Você ativou ' . $item->name . ' e sentiu seu poder mágico.';
                break;
                
            default:
                $message = 'Você usou ' . $item->name . '.';
        }
        
        // Se o item for consumível, remover do inventário
        if ($item->is_consumable) {
            if ($inventory->quantity > 1) {
                $inventory->quantity -= 1;
                $inventory->save();
            } else {
                $inventory->delete();
            }
        }
        
        // Registrar a ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'action_type' => 'item_used',
            'description' => 'Usou o item: ' . $item->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'health' => $character->health_points
        ]);
    }
    
    /**
     * Coletar um item
     */
    public function collectItem($itemId)
    {
        $character = Character::where('user_id', Auth::id())->first();
        $item = Item::findOrFail($itemId);
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Verificar se o personagem já possui o item
        $inventory = Inventory::where('character_id', $character->id)
            ->where('item_id', $itemId)
            ->first();
        
        if ($inventory) {
            // Se já possui, aumentar a quantidade
            $inventory->quantity += 1;
            $inventory->save();
        } else {
            // Se não possui, adicionar ao inventário
            Inventory::create([
                'character_id' => $character->id,
                'item_id' => $itemId,
                'quantity' => 1
            ]);
        }
        
        // Registrar a ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'action_type' => 'item_collected',
            'description' => 'Coletou o item: ' . $item->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Você coletou: ' . $item->name,
            'item' => $item
        ]);
    }
    
    /**
     * Avançar para a próxima fase
     */
    public function advancePhase($phaseId)
    {
        $phase = Phase::findOrFail($phaseId);
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        // Verificar se todos os desafios da fase atual foram completados
        $challenges = Challenge::where('phase_id', $phaseId)->get();
        $completedChallenges = PlayerProgress::where('character_id', $character->id)
            ->whereIn('challenge_id', $challenges->pluck('id'))
            ->where('status', 'completed')
            ->count();
        
        if ($completedChallenges < $challenges->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa completar todos os desafios desta fase antes de avançar.'
            ]);
        }
        
        // Atualizar o progresso da fase atual
        PlayerProgress::updateOrCreate(
            [
                'character_id' => $character->id,
                'phase_id' => $phaseId
            ],
            [
                'status' => 'completed',
                'completed_at' => now()
            ]
        );
        
        // Verificar se existe uma próxima fase
        $nextPhase = Phase::where('order', $phase->order + 1)->first();
        
        if ($nextPhase) {
            // Desbloquear a próxima fase
            PlayerProgress::updateOrCreate(
                [
                    'character_id' => $character->id,
                    'phase_id' => $nextPhase->id
                ],
                [
                    'status' => 'in_progress'
                ]
            );
            
            // Registrar a ação no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'action_type' => 'phase_completed',
                'description' => 'Completou a fase: ' . $phase->name . ' e desbloqueou: ' . $nextPhase->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Parabéns! Você completou a fase ' . $phase->name . ' e desbloqueou a fase ' . $nextPhase->name . '.',
                'next_phase' => $nextPhase
            ]);
        } else {
            // Registrar a ação no histórico
            ActionHistory::create([
                'character_id' => $character->id,
                'action_type' => 'game_completed',
                'description' => 'Completou o jogo!'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Parabéns! Você completou a última fase e venceu o jogo!',
                'game_completed' => true
            ]);
        }
    }
    
    /**
     * Reviver o personagem
     */
    public function reviveCharacter()
    {
        $character = Character::where('user_id', Auth::id())->first();
        
        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa criar um personagem primeiro.'
            ]);
        }
        
        if ($character->health_points > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Seu personagem não está morto.'
            ]);
        }
        
        // Reviver o personagem com metade da vida
        $character->health_points = ceil($character->max_health_points / 2);
        $character->save();
        
        // Registrar a ação no histórico
        ActionHistory::create([
            'character_id' => $character->id,
            'action_type' => 'character_revived',
            'description' => 'Personagem revivido com ' . $character->health_points . ' pontos de vida.'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Seu personagem foi revivido com ' . $character->health_points . ' pontos de vida.',
            'health' => $character->health_points
        ]);
    }
}
