<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GameplayController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rotas do jogo (protegidas por autenticação)
Route::middleware(['auth'])->group(function () {
    // Rotas de personagens
    Route::resource('characters', CharacterController::class);
    Route::get('characters/{character}/inventory', [CharacterController::class, 'inventory'])->name('characters.inventory');
    Route::get('characters/{character}/progress', [CharacterController::class, 'progress'])->name('characters.progress');
    
    // Rotas de fases
    Route::resource('phases', PhaseController::class);
    
    // Rotas de jogo
    Route::get('game', [GameController::class, 'index'])->name('game.index');
    Route::get('game/phase/{phase}', [GameController::class, 'showPhase'])->name('game.phase');
    
    // Rotas de gameplay
    Route::post('gameplay/challenge/{challenge}/solve', [GameplayController::class, 'solveChallenge'])->name('gameplay.solve_challenge');
    Route::post('gameplay/trap/{trap}/trigger', [GameplayController::class, 'triggerTrap'])->name('gameplay.trigger_trap');
    Route::get('gameplay/npc/{npc}/talk', [GameplayController::class, 'talkToNpc'])->name('gameplay.talk_to_npc');
    Route::post('gameplay/item/{item}/use', [GameplayController::class, 'useItem'])->name('gameplay.use_item');
    Route::post('gameplay/item/{item}/collect', [GameplayController::class, 'collectItem'])->name('gameplay.collect_item');
    Route::post('gameplay/phase/{phase}/advance', [GameplayController::class, 'advancePhase'])->name('gameplay.advance_phase');
    Route::post('gameplay/character/revive', [GameplayController::class, 'reviveCharacter'])->name('gameplay.revive_character');
});

// Rotas de administração (protegidas por middleware de admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('challenges', ChallengeController::class);
    Route::resource('items', ItemController::class);
});
