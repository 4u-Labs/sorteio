<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
$version = time();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=5.0" name="viewport"/>
    <title>Sorteador Pro Max | 4U.IA.BR</title>
    <meta name="description" content="Plataforma profissional de sorteios: números, nomes, roleta interativa, globo de bingo, amigo secreto criptografado, rifas com pesos, dados e moeda. Com certificado PDF e verificação SHA-256."/>
    
    <!-- PWA & Mobile -->
    <meta name="theme-color" content="#0d1117"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <link rel="manifest" href="manifest.json"/>
    <link rel="icon" type="image/png" sizes="64x64" href="favicon.png"/>
    <link rel="apple-touch-icon" href="apple-touch-icon.png"/>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800;900&family=Poppins:wght@500;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <!-- Anti-cache Stylesheet -->
    <link rel="stylesheet" href="style.css?v=<?= $version ?>"/>
</head>
<body>
    <!-- Background Particles -->
    <div class="particles" id="particles"></div>
    <div id="confettiContainer"></div>
    <div id="toast-container"></div>

    <div class="container">
        <!-- Header Controls -->
        <div class="header-controls">
            <button class="icon-btn" id="shortcutsBtn" title="Atalhos de Teclado">
                <i class="fas fa-keyboard"></i>
            </button>
            <button class="icon-btn" id="soundToggle" title="Alternar Som">
                <i class="fas fa-volume-up"></i>
            </button>
            <button class="icon-btn" id="themeToggle" title="Alternar Tema (Claro/Escuro)">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <!-- Main Header -->
        <header>
            <h1>
                <i class="fas fa-dice-d20"></i> SORTEADOR PRO <span class="version-badge">MAX 6.0</span>
            </h1>
            <p class="subtitulo">Sorteios transparentes, roleta interativa, bingo 3D, amigo secreto e certificação auditável SHA-256.</p>
        </header>

        <!-- Promotor Section -->
        <section class="promotor-section">
            <div class="promotor-header" id="promotorToggle">
                <h3><i class="fas fa-bullhorn"></i> Dados do Promotor do Sorteio (Opcional)</h3>
                <i class="fas fa-chevron-down chevron" id="promotorChevron"></i>
            </div>
            <div class="promotor-fields collapsed" id="promotorFields">
                <div class="form-group">
                    <label for="promotorNome"><i class="fas fa-user"></i> Nome ou Empresa:</label>
                    <input id="promotorNome" type="text" placeholder="Ex: Comunidade 4U / Fabiano"/>
                </div>
                <div class="form-group">
                    <label for="promotorWhatsapp"><i class="fab fa-whatsapp"></i> WhatsApp para Contato:</label>
                    <input id="promotorWhatsapp" type="tel" placeholder="(11) 99999-9999"/>
                </div>
                <div class="form-group">
                    <label for="promotorUrl"><i class="fas fa-globe"></i> Site ou Instagram:</label>
                    <input id="promotorUrl" type="url" placeholder="https://instagram.com/seuperfil"/>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <nav class="tabs">
            <button class="tab-btn active" data-tab="numeros"><i class="fas fa-hashtag"></i> Números</button>
            <button class="tab-btn" data-tab="nomes"><i class="fas fa-users"></i> Nomes</button>
            <button class="tab-btn" data-tab="roleta"><i class="fas fa-dharmachakra"></i> Roleta</button>
            <button class="tab-btn" data-tab="bingo"><i class="fas fa-circle-dot"></i> Bingo 3D</button>
            <button class="tab-btn" data-tab="amigo"><i class="fas fa-gift"></i> Amigo Secreto</button>
            <button class="tab-btn" data-tab="rifas"><i class="fas fa-ticket"></i> Rifas/Pesos</button>
            <button class="tab-btn" data-tab="dados"><i class="fas fa-dice"></i> Dados</button>
            <button class="tab-btn" data-tab="moeda"><i class="fas fa-coins"></i> Moeda</button>
            <button class="tab-btn" data-tab="historico"><i class="fas fa-clock-rotate-left"></i> Histórico</button>
        </nav>

        <!-- TAB: NÚMEROS -->
        <div class="tab-pane active" id="tab-numeros">
            <div class="card-panel">
                <h2><i class="fas fa-hashtag"></i> Sorteio de Números</h2>
                <div class="form-group">
                    <label for="sorteioNomeNumeros">Título do Sorteio:</label>
                    <input id="sorteioNomeNumeros" type="text" placeholder="Ex: Rifa de Natal, Concurso Oficial"/>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantidadeNumeros">Quantidade a Sortear:</label>
                        <input id="quantidadeNumeros" type="number" min="1" max="1000" value="1"/>
                    </div>
                    <div class="form-group">
                        <label for="minimo">Intervalo Mínimo:</label>
                        <input id="minimo" type="number" value="1"/>
                    </div>
                    <div class="form-group">
                        <label for="maximo">Intervalo Máximo:</label>
                        <input id="maximo" type="number" value="100"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="excluirNumeros">Excluir Números (separados por vírgula):</label>
                    <input id="excluirNumeros" type="text" placeholder="Ex: 7, 13, 21"/>
                </div>
                <div class="form-row">
                    <label class="checkbox-label">
                        <input id="repetirNumeros" type="checkbox"/> Permitir números repetidos
                    </label>
                    <label class="checkbox-label">
                        <input id="ordenarNumeros" type="checkbox" checked/> Exibir em ordem crescente
                    </label>
                </div>
                <div class="error-banner" id="erro-numeros"></div>
                <div class="action-row" style="margin-top:16px;">
                    <button class="btn-secondary" id="limparNumerosBtn"><i class="fas fa-eraser"></i> Limpar</button>
                    <button class="btn-primary" id="sortearNumerosBtn"><i class="fas fa-play"></i> Realizar Sorteio</button>
                </div>
            </div>
        </div>

        <!-- TAB: NOMES & GRUPOS -->
        <div class="tab-pane" id="tab-nomes">
            <div class="card-panel">
                <h2><i class="fas fa-users"></i> Sorteio de Nomes e Formação de Grupos</h2>
                <div class="form-group">
                    <label for="sorteioNomeNomes">Título do Sorteio:</label>
                    <input id="sorteioNomeNomes" type="text" placeholder="Ex: Sorteio do Instagram, Times de Futebol"/>
                </div>
                <div class="form-group">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <label for="listaNomes">Lista de Participantes: <span id="nomeCountSpan" class="form-hint">(0 nomes)</span></label>
                        <label for="importNomesFile" class="btn-secondary" style="cursor:pointer; padding: 4px 10px; font-size:0.8rem;">
                            <i class="fas fa-file-arrow-up"></i> Importar .txt / .csv
                        </label>
                        <input id="importNomesFile" type="file" accept=".txt,.csv" style="display:none;"/>
                    </div>
                    <textarea id="listaNomes" placeholder="Cole os nomes aqui (um por linha ou separados por vírgula)..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantidadeNomes">Quantidade de Ganhadores:</label>
                        <input id="quantidadeNomes" type="number" min="1" value="1"/>
                    </div>
                    <div class="form-group">
                        <label for="numGrupos">Dividir em Grupos / Equipes:</label>
                        <input id="numGrupos" type="number" min="0" value="0"/>
                        <span class="form-hint">0 = Não dividir em grupos</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="excluirNomes">Excluir Nomes:</label>
                    <input id="excluirNomes" type="text" placeholder="Ex: Fulano, Ciclano"/>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input id="ordenarNomes" type="checkbox"/> Ordenar lista sorteada em ordem alfabética
                    </label>
                </div>
                <div class="error-banner" id="erro-nomes"></div>
                <button class="btn-primary" id="sortearNomesBtn" style="margin-top:16px;">
                    <i class="fas fa-trophy"></i> Sortear Nomes
                </button>
            </div>
        </div>

        <!-- TAB: ROLETA DA FORTUNA -->
        <div class="tab-pane" id="tab-roleta">
            <div class="card-panel">
                <h2><i class="fas fa-dharmachakra"></i> Roleta Interativa da Fortuna</h2>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="sorteioNomeRoleta">Título da Roleta:</label>
                        <input id="sorteioNomeRoleta" type="text" placeholder="Ex: Roleta de Prêmios"/>
                        <label for="roletaItems" style="margin-top:10px;">Opções da Roleta (um por linha):</label>
                        <textarea id="roletaItems" rows="8" placeholder="Prêmio 1&#10;Prêmio 2&#10;Tente Outra Vez&#10;Prêmio Especial&#10;Brinde Surpresa&#10;Passa a Vez">Camiseta Oficial
Desconto 50%
Brinde Surpresa
Tente Novamente
Ingresso VIP
Vale Compras R$ 100</textarea>
                    </div>
                    <div class="wheel-wrapper" style="flex: 1;">
                        <div class="wheel-container">
                            <div class="wheel-pointer"></div>
                            <div class="wheel-center-pin"></div>
                            <canvas id="wheelCanvas" class="wheel-canvas" width="400" height="400"></canvas>
                        </div>
                        <button class="btn-primary" id="spinWheelBtn" style="max-width:300px;">
                            <i class="fas fa-rotate"></i> GIRAR ROLETA!
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: GLOBO DE BINGO 3D -->
        <div class="tab-pane" id="tab-bingo">
            <div class="card-panel">
                <h2><i class="fas fa-circle-dot"></i> Globo de Bingo e Bolinhas 3D</h2>
                <div class="form-row" style="align-items: flex-end;">
                    <div class="form-group" style="flex: 1;">
                        <label for="bingoMax">Padrão de Bolinhas:</label>
                        <select id="bingoMax">
                            <option value="75">Bingo Tradicional (1 a 75)</option>
                            <option value="90">Bingo Rápido (1 a 90)</option>
                            <option value="60">Mega Sorteio (1 a 60)</option>
                            <option value="50">Mini Bingo (1 a 50)</option>
                        </select>
                    </div>
                    <div style="margin-bottom:14px;">
                        <button class="btn-secondary" id="initBingoBtn"><i class="fas fa-arrows-rotate"></i> Reiniciar Globo</button>
                    </div>
                </div>

                <div class="bingo-cage-container">
                    <div class="bingo-sphere">
                        <div class="bingo-ball-active" id="bingoActiveBall">--</div>
                    </div>
                    <button class="btn-primary" id="drawBingoBtn" style="max-width: 320px; margin-bottom: 20px;">
                        <i class="fas fa-hand-holding-hand"></i> RETIRAR BOLINHA
                    </button>
                    
                    <div style="display:flex; justify-content:space-between; width:100%; margin-bottom:8px; font-size:0.85rem; color:var(--subtitle-color);">
                        <span>Bolinhas restantes: <strong id="bingoRemainingCount" style="color:var(--text-color);">0</strong></span>
                        <span>Sorteadas: <strong id="bingoDrawnCount" style="color:var(--primary-color);">0</strong></span>
                    </div>
                    <div class="bingo-historyTray" id="bingoHistoryTray"></div>
                </div>
            </div>
        </div>

        <!-- TAB: AMIGO SECRETO -->
        <div class="tab-pane" id="tab-amigo">
            <div class="card-panel">
                <h2><i class="fas fa-gift"></i> Amigo Secreto Criptografado (Zero-Knowledge)</h2>
                <p class="form-hint" style="margin-bottom:16px;">
                    O sorteio gera combinações onde ninguém tira a si mesmo. Os resultados ficam protegidos em cartões raspadinha e você pode enviar individualmente via WhatsApp sem que o promotor veja!
                </p>
                <div class="form-group">
                    <label for="sorteioNomeAmigo">Título do Evento:</label>
                    <input id="sorteioNomeAmigo" type="text" placeholder="Ex: Amigo Secreto Família Silva 2026"/>
                </div>
                <div class="form-group">
                    <label for="listaAmigoSecreto">Lista de Participantes (ao menos 3 nomes):</label>
                    <textarea id="listaAmigoSecreto" rows="6" placeholder="Carlos&#10;Mariana&#10;Fabiano&#10;Beatriz&#10;Lucas"></textarea>
                </div>
                <div class="error-banner" id="erro-amigo"></div>
                <button class="btn-primary" id="sortearAmigoBtn" style="margin-top:16px;">
                    <i class="fas fa-shuffle"></i> Gerar Amigo Secreto
                </button>

                <div id="amigoResultContainer" style="display:none; margin-top:24px;">
                    <h3 style="color:var(--title-color); font-size:1.1rem; margin-bottom:12px;">
                        <i class="fas fa-envelope-open-text"></i> Cartões Individuais Criptografados
                    </h3>
                    <div class="secret-cards-grid" id="amigoCardsGrid"></div>
                </div>
            </div>
        </div>

        <!-- TAB: RIFAS / SORTEIO PONDERADO -->
        <div class="tab-pane" id="tab-rifas">
            <div class="card-panel">
                <h2><i class="fas fa-ticket"></i> Sorteio Ponderado e Rifas com Bilhetes</h2>
                <p class="form-hint" style="margin-bottom:14px;">
                    Defina quantos bilhetes/chances cada participante tem. Exemplo: <code>Maria, 5</code> ou <code>João [10]</code> ou <code>Pedro (2)</code>.
                </p>
                <div class="form-group">
                    <label for="sorteioNomeRifas">Título da Rifa:</label>
                    <input id="sorteioNomeRifas" type="text" placeholder="Ex: Rifa Automotiva 2026"/>
                </div>
                <div class="form-group">
                    <label for="listaRifas">Participantes e Pesos / Bilhetes:</label>
                    <textarea id="listaRifas" rows="7" placeholder="Carlos, 3&#10;Mariana, 10&#10;Fabiano [15]&#10;Beatriz (5)"></textarea>
                </div>
                <div class="form-group">
                    <label for="quantidadeRifas">Quantidade de Ganhadores Únicos:</label>
                    <input id="quantidadeRifas" type="number" min="1" value="1"/>
                </div>
                <div class="error-banner" id="erro-rifas"></div>
                <button class="btn-primary" id="sortearRifasBtn" style="margin-top:16px;">
                    <i class="fas fa-ticket-simple"></i> Sortear Rifa
                </button>
            </div>
        </div>

        <!-- TAB: DADOS -->
        <div class="tab-pane" id="tab-dados">
            <div class="card-panel">
                <h2><i class="fas fa-dice-d20"></i> Rolagem de Dados RPG e Jogos</h2>
                <div class="form-group">
                    <label>Escolha o Dado:</label>
                    <div class="dice-selector">
                        <button class="dice-btn" data-sides="4">D4</button>
                        <button class="dice-btn active" data-sides="6">D6</button>
                        <button class="dice-btn" data-sides="8">D8</button>
                        <button class="dice-btn" data-sides="10">D10</button>
                        <button class="dice-btn" data-sides="12">D12</button>
                        <button class="dice-btn" data-sides="20">D20</button>
                        <button class="dice-btn" data-sides="100">D100</button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantidadeDados">Quantidade de Dados:</label>
                        <input id="quantidadeDados" type="number" min="1" max="30" value="1"/>
                    </div>
                    <div class="form-group">
                        <label for="modificadorDados">Modificador (+ ou -):</label>
                        <input id="modificadorDados" type="number" value="0"/>
                    </div>
                </div>
                <div class="error-banner" id="erro-dados"></div>
                <button class="btn-primary" id="rolarDadosBtn" style="margin-top:16px;">
                    <i class="fas fa-dice"></i> Rolar Dados
                </button>

                <div class="dice-results-box" id="diceResultsBox" style="display:none;">
                    <h3>Resultados da Rolagem</h3>
                    <div class="dice-tray" id="diceResultsTray"></div>
                    <p id="diceTotalText" style="font-size:1.2rem; margin-top:10px;"></p>
                </div>

                <div class="stats-grid" id="diceStatsBox" style="display:none;">
                    <div class="stat-card">
                        <div class="stat-val" id="statRolls">0</div>
                        <div class="stat-lbl">Rolagens</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="statTotal">0</div>
                        <div class="stat-lbl">Soma Total</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="statAvg">0</div>
                        <div class="stat-lbl">Média</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="statMax">0</div>
                        <div class="stat-lbl">Maior Valor</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: MOEDA -->
        <div class="tab-pane" id="tab-moeda">
            <div class="card-panel">
                <h2><i class="fas fa-coins"></i> Cara ou Coroa</h2>
                <div class="coin-scene">
                    <div class="coin-object" id="coinObject">
                        <div class="coin-side coin-heads">
                            <i class="fas fa-crown"></i>
                            <span>CARA</span>
                        </div>
                        <div class="coin-side coin-tails">
                            <i class="fas fa-dragon"></i>
                            <span>COROA</span>
                        </div>
                    </div>
                    <div id="coinResultText" style="font-size:1.4rem; font-weight:800; min-height:36px; margin-bottom:16px; color:var(--text-color);"></div>
                    <button class="btn-primary" id="flipCoinBtn" style="max-width:300px;">
                        <i class="fas fa-arrows-spin"></i> Lançar Moeda
                    </button>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-val" id="coinFlips">0</div>
                        <div class="stat-lbl">Lançamentos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="coinHeads">0</div>
                        <div class="stat-lbl">Cara</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="coinTails">0</div>
                        <div class="stat-lbl">Coroa</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-val" id="coinPercent">0%</div>
                        <div class="stat-lbl">% Cara</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: HISTÓRICO -->
        <div class="tab-pane" id="tab-historico">
            <div class="card-panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h2><i class="fas fa-clock-rotate-left"></i> Histórico de Sorteios</h2>
                    <div style="display:flex; gap:8px;">
                        <button class="btn-secondary" id="exportHistoryBtn"><i class="fas fa-download"></i> Exportar CSV</button>
                        <button class="btn-danger" id="clearHistoryBtn"><i class="fas fa-trash"></i> Limpar</button>
                    </div>
                </div>
                <div class="history-list" id="historyContainer"></div>
            </div>
        </div>

        <!-- COUNTDOWN SECTION -->
        <section class="countdown-section" id="countdownSection">
            <p style="font-size:1.1rem; color:var(--subtitle-color); margin-bottom:12px;">
                <i class="fas fa-hourglass-half"></i> Processando sorteio com aleatoriedade segura...
            </p>
            <div class="countdown-timer" id="countdownTimer">3</div>
        </section>

        <!-- RESULTS SECTION -->
        <section class="results-section" id="resultsSection">
            <h2 id="resultsTitle"><i class="fas fa-trophy"></i> Resultado Oficial</h2>
            <div class="promotor-display" id="resultsPromotorBox" style="display:none;"></div>
            
            <div class="results-items-flow" id="resultsItemsFlow"></div>
            <div id="resultsGroupsDiv" style="display:none; margin: 20px 0;"></div>
            
            <div style="text-align:center; font-size:0.85rem; color:var(--subtitle-color); margin-top:8px;">
                <span id="resultsTimestamp"></span>
            </div>

            <!-- SHA-256 Stamp -->
            <div class="sha256-seal">
                <span><i class="fas fa-shield-halved"></i> AUTENTICIDADE:</span>
                <span class="hash" id="resultsSha256"></span>
            </div>

            <!-- Share & Export Buttons -->
            <div class="share-grid">
                <button class="share-btn wa" id="shareWhatsAppBtn"><i class="fab fa-whatsapp"></i> WhatsApp</button>
                <button class="share-btn tw" id="shareTwitterBtn"><i class="fab fa-x-twitter"></i> Twitter / X</button>
                <button class="share-btn stories" id="storyResultBtn"><i class="fab fa-instagram"></i> Instagram Story</button>
                <button class="share-btn cert" id="printCertBtn"><i class="fas fa-file-pdf"></i> Certificado A4</button>
                <button class="share-btn copy" id="copyResultBtn"><i class="fas fa-copy"></i> Copiar</button>
            </div>

            <button class="btn-primary" id="newDrawBtn" style="margin-top:14px;">
                <i class="fas fa-rotate-left"></i> Realizar Novo Sorteio
            </button>
        </section>

    </div>

    <!-- MODAL: ATALHOS DE TECLADO -->
    <div class="modal-backdrop" id="modalShortcuts">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fas fa-keyboard"></i> Atalhos de Teclado</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div style="display:grid; grid-template-columns: 1fr auto; gap:12px; font-size:0.9rem;">
                <span>Alternar Modo Escuro / Claro</span><code>T</code>
                <span>Alternar Efeitos Sonoros</span><code>S</code>
                <span>Aba Números</span><code>1</code>
                <span>Aba Nomes</span><code>2</code>
                <span>Aba Roleta</span><code>3</code>
                <span>Aba Bingo</span><code>4</code>
                <span>Aba Amigo Secreto</span><code>5</code>
                <span>Aba Rifas / Pesos</span><code>6</code>
                <span>Aba Dados</span><code>7</code>
                <span>Aba Moeda</span><code>8</code>
                <span>Aba Histórico</span><code>9</code>
                <span>Novo Sorteio</span><code>N</code>
                <span>Fechar Janelas</span><code>ESC</code>
            </div>
        </div>
    </div>

    <!-- MODAL: INSTAGRAM STORIES PREVIEW -->
    <div class="modal-backdrop" id="modalStories">
        <div class="modal-box" style="text-align:center;">
            <div class="modal-header">
                <h3><i class="fab fa-instagram"></i> Card para Instagram Stories</h3>
                <button class="modal-close">&times;</button>
            </div>
            <canvas id="storiesCanvasPreview" class="stories-preview-canvas"></canvas>
            <div style="display:flex; justify-content:center; gap:10px; margin-top:14px;">
                <button class="btn-primary" id="downloadStoriesBtn" style="max-width:240px;">
                    <i class="fas fa-download"></i> Baixar Imagem (PNG)
                </button>
            </div>
        </div>
    </div>

    <!-- A4 PRINT CERTIFICATE TEMPLATE -->
    <div class="print-certificate" id="printCertificate"></div>

    <!-- Institutional Footer -->
    <footer class="footer-standard">
        <nav class="footer-nav">
            <a href="privacidade.php">Privacidade</a>
            <a href="termos.php">Termos de Uso</a>
            <a href="suporte.php">Suporte & FAQ</a>
            <a href="https://github.com/4u-Labs" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-github"></i> GitHub
            </a>
        </nav>
        <div class="footer-credit">
            &copy; 2026 <a href="https://4u.ia.br" target="_blank">4U.IA.BR</a> • Todos os direitos reservados.
        </div>
    </footer>

    <!-- Anti-cache Script -->
    <script src="app.js?v=<?= $version ?>"></script>
</body>
</html>
