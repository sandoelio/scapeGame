/**
 * Escape Room Game - JavaScript principal
 */

// Variáveis globais
let currentPhaseId = null;
let currentCharacterId = null;
let playerHealth = 100;
let playerMaxHealth = 100;

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Obter IDs da página atual
    currentPhaseId = document.getElementById('phase-container')?.dataset.phaseId;
    currentCharacterId = document.getElementById('character-info')?.dataset.characterId;
    
    // Inicializar barras de vida
    updateHealthBar();
    
    // Adicionar listeners para modais
    setupModalListeners();
});

/**
 * Atualiza a barra de vida do personagem
 */
function updateHealthBar() {
    const healthBar = document.getElementById('health-bar');
    if (!healthBar) return;
    
    const healthPercent = (playerHealth / playerMaxHealth) * 100;
    healthBar.style.width = healthPercent + '%';
    
    // Mudar cor com base na vida
    if (healthPercent > 70) {
        healthBar.classList.remove('bg-warning', 'bg-danger');
        healthBar.classList.add('bg-success');
    } else if (healthPercent > 30) {
        healthBar.classList.remove('bg-success', 'bg-danger');
        healthBar.classList.add('bg-warning');
    } else {
        healthBar.classList.remove('bg-success', 'bg-warning');
        healthBar.classList.add('bg-danger');
    }
    
    // Atualizar texto
    document.getElementById('health-text').textContent = `${playerHealth}/${playerMaxHealth}`;
}

/**
 * Configura os listeners para os modais
 */
function setupModalListeners() {
    // Modal de desafio
    const challengeModal = document.getElementById('challenge-modal');
    if (challengeModal) {
        challengeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const challengeId = button.getAttribute('data-challenge-id');
            const challengeName = button.getAttribute('data-challenge-name');
            const challengeDesc = button.getAttribute('data-challenge-description');
            
            // Atualizar o modal com os dados do desafio
            document.getElementById('challenge-title').textContent = challengeName;
            document.getElementById('challenge-description').textContent = challengeDesc;
            document.getElementById('challenge-form').setAttribute('data-challenge-id', challengeId);
        });
    }
    
    // Modal de NPC
    const npcModal = document.getElementById('npc-modal');
    if (npcModal) {
        npcModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const npcId = button.getAttribute('data-npc-id');
            
            // Carregar dados do NPC via AJAX
            fetch(`/gameplay/npc/${npcId}/talk`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('npc-name').textContent = data.npc.name;
                        document.getElementById('npc-description').textContent = data.npc.description;
                        document.getElementById('npc-dialog').textContent = data.npc.dialog;
                    }
                })
                .catch(error => console.error('Erro ao carregar dados do NPC:', error));
        });
    }
    
    // Modal de inventário
    const inventoryModal = document.getElementById('inventory-modal');
    if (inventoryModal) {
        inventoryModal.addEventListener('show.bs.modal', function () {
            // Atualizar o inventário quando o modal for aberto
            updateInventory();
        });
    }
    
    // Formulário de desafio
    const challengeForm = document.getElementById('challenge-form');
    if (challengeForm) {
        challengeForm.addEventListener('submit', function (event) {
            event.preventDefault();
            
            const challengeId = this.getAttribute('data-challenge-id');
            const codeInput = document.getElementById('challenge-code');
            const code = codeInput.value.trim();
            
            if (!code) {
                showAlert('Por favor, insira um código.', 'warning');
                return;
            }
            
            // Enviar o código para o servidor
            fetch(`/gameplay/challenge/${challengeId}/solve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`${data.message} Você ganhou ${data.points} pontos de experiência!`, 'success');
                    
                    // Fechar o modal
                    bootstrap.Modal.getInstance(document.getElementById('challenge-modal')).hide();
                    
                    // Atualizar a interface se necessário
                    if (data.level_up) {
                        showAlert('Parabéns! Você subiu de nível!', 'success');
                    }
                    
                    // Marcar o desafio como concluído na interface
                    const challengeElement = document.querySelector(`[data-challenge-id="${challengeId}"]`);
                    if (challengeElement) {
                        challengeElement.classList.add('completed');
                        challengeElement.querySelector('.badge').textContent = 'Concluído';
                    }
                    
                    // Verificar se todos os desafios foram concluídos
                    checkPhaseCompletion();
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erro ao enviar código:', error);
                showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
            });
        });
    }
}

/**
 * Verifica se todos os desafios da fase foram concluídos
 */
function checkPhaseCompletion() {
    const challenges = document.querySelectorAll('.challenge-card');
    const completedChallenges = document.querySelectorAll('.challenge-card.completed');
    
    if (challenges.length > 0 && challenges.length === completedChallenges.length) {
        // Todos os desafios foram concluídos
        document.getElementById('advance-phase-btn').classList.remove('d-none');
    }
}

/**
 * Avança para a próxima fase
 */
function advancePhase() {
    if (!currentPhaseId) return;
    
    fetch(`/gameplay/phase/${currentPhaseId}/advance`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Redirecionar para a próxima fase ou para a tela de conclusão do jogo
            if (data.game_completed) {
                setTimeout(() => {
                    window.location.href = '/game';
                }, 2000);
            } else if (data.next_phase) {
                setTimeout(() => {
                    window.location.href = `/game/phase/${data.next_phase.id}`;
                }, 2000);
            }
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro ao avançar fase:', error);
        showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
    });
}

/**
 * Ativa uma armadilha
 */
function triggerTrap(trapId) {
    fetch(`/gameplay/trap/${trapId}/trigger`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'danger');
            
            // Atualizar a vida do personagem
            playerHealth = data.health;
            updateHealthBar();
            
            // Se o personagem morreu
            if (data.died) {
                setTimeout(() => {
                    showReviveModal();
                }, 1500);
            }
        }
    })
    .catch(error => {
        console.error('Erro ao ativar armadilha:', error);
        showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
    });
}

/**
 * Usa um item do inventário
 */
function useItem(itemId) {
    fetch(`/gameplay/item/${itemId}/use`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Atualizar a vida do personagem se necessário
            if (data.health !== undefined) {
                playerHealth = data.health;
                updateHealthBar();
            }
            
            // Atualizar o inventário
            updateInventory();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro ao usar item:', error);
        showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
    });
}

/**
 * Coleta um item
 */
function collectItem(itemId) {
    fetch(`/gameplay/item/${itemId}/collect`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Atualizar o inventário
            updateInventory();
            
            // Remover o item da interface
            const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            }
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro ao coletar item:', error);
        showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
    });
}

/**
 * Atualiza o inventário do personagem
 */
function updateInventory() {
    const inventoryContainer = document.getElementById('inventory-items');
    if (!inventoryContainer || !currentCharacterId) return;
    
    // Limpar o inventário atual
    inventoryContainer.innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Carregando...</span></div>';
    
    // Carregar o inventário via AJAX
    fetch(`/characters/${currentCharacterId}/inventory`)
        .then(response => response.text())
        .then(html => {
            // Extrair apenas a parte do HTML que contém os itens
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const items = doc.querySelector('#inventory-items');
            
            if (items) {
                inventoryContainer.innerHTML = items.innerHTML;
                
                // Adicionar listeners para os botões de usar item
                document.querySelectorAll('.use-item-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.getAttribute('data-item-id');
                        useItem(itemId);
                    });
                });
            } else {
                inventoryContainer.innerHTML = '<p>Nenhum item no inventário.</p>';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar inventário:', error);
            inventoryContainer.innerHTML = '<p class="text-danger">Erro ao carregar inventário.</p>';
        });
}

/**
 * Mostra o modal de reviver personagem
 */
function showReviveModal() {
    const modal = new bootstrap.Modal(document.getElementById('revive-modal'));
    modal.show();
}

/**
 * Revive o personagem
 */
function reviveCharacter() {
    fetch('/gameplay/character/revive', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Atualizar a vida do personagem
            playerHealth = data.health;
            updateHealthBar();
            
            // Fechar o modal
            bootstrap.Modal.getInstance(document.getElementById('revive-modal')).hide();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro ao reviver personagem:', error);
        showAlert('Ocorreu um erro ao processar sua solicitação.', 'danger');
    });
}

/**
 * Exibe um alerta na tela
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;
    
    const alertElement = document.createElement('div');
    alertElement.className = `alert alert-${type} alert-dismissible fade show`;
    alertElement.role = 'alert';
    alertElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    `;
    
    alertContainer.appendChild(alertElement);
    
    // Remover o alerta após 5 segundos
    setTimeout(() => {
        alertElement.classList.remove('show');
        setTimeout(() => {
            alertElement.remove();
        }, 150);
    }, 5000);
}

