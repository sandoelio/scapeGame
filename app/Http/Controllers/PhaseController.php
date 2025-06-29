<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phase;
use App\Models\PlayerProgress;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PhaseController extends Controller
{
    /**
     * Construtor que aplica middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de todas as fases disponíveis.
     */
    public function index()
    {
        
        $phases = Phase::orderBy('order')->get();

        // Obter os personagens atuais do usuário para verificar o progresso
        $characters = Auth::user()->characters;
        $character = $characters->first(); // Considera o primeiro personagem do usuário

        // Se o personagem não existir, inicializar progresso como vazio
        $progress = [];
        
        if ($character) {
            $progress = PlayerProgress::where('character_id', $character->id)
                ->get()
                ->keyBy('phase_id');
        }
        
        return view('phases.index', compact('phases', 'character', 'progress'));
    }

    /**
     * Exibe o formulário para criar uma nova fase (apenas para administradores).
     */
    public function create()
    {
        // Verificar se o usuário é administrador
        if (!Auth::user()->is_admin) {
            return redirect()->route('phases.index')
                ->with('error', 'Você não tem permissão para criar fases.');
        }
        
        return view('phases.create');
    }

    /**
     * Armazena uma nova fase (apenas para administradores).
     */
    public function store(Request $request)
    {
        // Verificar se o usuário é administrador
        if (!Auth::user()->is_admin) {
            return redirect()->route('phases.index')
                ->with('error', 'Você não tem permissão para criar fases.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $phase = Phase::create([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('phases.show', $phase->id)
            ->with('success', 'Fase criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma fase específica.
     */
    public function show(string $id)
    {
        
        $phase = Phase::with(['challenges', 'traps', 'npcs'])->findOrFail($id);
        
        // Verificar se a fase está ativa
        if (!$phase->is_active) {
            return redirect()->route('phases.index')
                ->with('error', 'Esta fase não está disponível no momento.');
        }
        
        // Obter o personagem atual do usuário
        $characters = Auth::user()->characters;
        $character = $characters->first(); // Considera o primeiro personagem do usuário
        
        // Verificar se o personagem tem acesso a esta fase
        // (deve ter completado a fase anterior, exceto para a primeira fase)
        if ($phase->order > 1 && $character) {
            $previousPhase = Phase::where('order', $phase->order - 1)->first();
            
            if ($previousPhase) {
                $previousProgress = PlayerProgress::where('character_id', $character->id)
                    ->where('phase_id', $previousPhase->id)
                    ->where('status', 'completed')
                    ->first();
                
                if (!$previousProgress) {
                    return redirect()->route('phases.index')
                        ->with('error', 'Você precisa completar a fase anterior primeiro.');
                }
            }
        }
        
        // Registrar ou atualizar o progresso do jogador nesta fase
        if ($character) {
            $progress = PlayerProgress::firstOrCreate(
                ['character_id' => $character->id, 'phase_id' => $phase->id],
                ['status' => 'in_progress']
            );
        } else {
            $progress = null;
        }
        
        return view('phases.show', compact('phase', 'character', 'progress'));
    }

    /**
     * Exibe o formulário para editar uma fase (apenas para administradores).
     */
    public function edit(string $id)
    {
        // Verificar se o usuário é administrador
        if (!Auth::user()->is_admin) {
            return redirect()->route('phases.index')
                ->with('error', 'Você não tem permissão para editar fases.');
        }
        
        $phase = Phase::findOrFail($id);
        return view('phases.edit', compact('phase'));
    }

    /**
     * Atualiza uma fase específica (apenas para administradores).
     */
    public function update(Request $request, string $id)
    {
        // Verificar se o usuário é administrador
        if (!Auth::user()->is_admin) {
            return redirect()->route('phases.index')
                ->with('error', 'Você não tem permissão para editar fases.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $phase = Phase::findOrFail($id);
        
        $phase->update([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
            'is_active' => $request->is_active ?? $phase->is_active,
        ]);
        
        return redirect()->route('phases.show', $phase->id)
            ->with('success', 'Fase atualizada com sucesso!');
    }

    /**
     * Remove uma fase específica (apenas para administradores).
     */
    public function destroy(string $id)
    {
        // Verificar se o usuário é administrador
        if (!Auth::user()->is_admin) {
            return redirect()->route('phases.index')
                ->with('error', 'Você não tem permissão para excluir fases.');
        }
        
        $phase = Phase::findOrFail($id);
        $phase->delete();
        
        return redirect()->route('phases.index')
            ->with('success', 'Fase excluída com sucesso!');
    }
}
