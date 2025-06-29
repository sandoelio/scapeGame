<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PlayerProgress;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    /**
     * Construtor que aplica middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de personagens do usuário.
     */
    public function index()
    {
        $characters = Character::where('user_id', Auth::id())->get();
        return view('characters.index', compact('characters'));
    }

    /**
     * Exibe o formulário para criar um novo personagem.
     */
    public function create()
    {
        return view('characters.create');
    }

    /**
     * Armazena um novo personagem.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $character = Character::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'health_points' => 100,
            'max_health_points' => 100,
            'level' => 1,
            'experience' => 0,
        ]);

        // Adicionar alguns itens iniciais ao inventário do personagem
        $starterItems = [1, 4, 7]; // IDs dos itens iniciais (Chave de Bronze, Lanterna, Poção de Cura)
        
        foreach ($starterItems as $itemId) {
            Inventory::create([
                'character_id' => $character->id,
                'item_id' => $itemId,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('characters.show', $character->id)
            ->with('success', 'Personagem criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um personagem específico.
     */
    public function show(string $id)
    {
        $character = Character::with(['inventory.item', 'progress.phase'])->findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para ver este personagem.');
        }
        
        // Obter o histórico de ações do personagem
        $actionHistory = ActionHistory::where('character_id', $character->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('characters.show', compact('character', 'actionHistory'));
    }

    /**
     * Exibe o formulário para editar um personagem.
     */
    public function edit(string $id)
    {
        $character = Character::findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para editar este personagem.');
        }
        
        return view('characters.edit', compact('character'));
    }

    /**
     * Atualiza um personagem específico.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $character = Character::findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para editar este personagem.');
        }
        
        $character->name = $request->name;
        $character->save();
        
        return redirect()->route('characters.show', $character->id)
            ->with('success', 'Personagem atualizado com sucesso!');
    }

    /**
     * Remove um personagem específico.
     */
    public function destroy(string $id)
    {
        $character = Character::findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para excluir este personagem.');
        }
        
        // Excluir o personagem (as relações serão excluídas em cascata devido às restrições de chave estrangeira)
        $character->delete();
        
        return redirect()->route('characters.index')
            ->with('success', 'Personagem excluído com sucesso!');
    }

    /**
     * Exibe o inventário do personagem.
     */
    public function inventory(string $id)
    {
        $character = Character::with('inventory.item')->findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para ver este inventário.');
        }
        
        return view('characters.inventory', compact('character'));
    }

    /**
     * Exibe o progresso do personagem.
     */
    public function progress(string $id)
    {
        $character = Character::with('progress.phase', 'progress.challenge')->findOrFail($id);
        
        // Verificar se o personagem pertence ao usuário logado
        if ($character->user_id !== Auth::id()) {
            return redirect()->route('characters.index')
                ->with('error', 'Você não tem permissão para ver este progresso.');
        }
        
        return view('characters.progress', compact('character'));
    }
}
