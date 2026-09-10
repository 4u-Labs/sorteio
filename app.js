/**
 * Sorteador Pro Max v6.0 - Engine
 * 4U.IA.BR Labs - https://github.com/4u-Labs
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- HELPER SELECTORS ---
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    // --- STATE ---
    let currentTab = 'numeros';
    let soundEnabled = true;
    let darkMode = true;
    let selectedDie = 6;
    let lastDrawData = null;
    let diceStats = { rolls: 0, total: 0, max: 0 };
    let coinStats = { flips: 0, heads: 0, tails: 0 };
    let bingoRemaining = [];
    let bingoDrawn = [];
    let bingoInterval = null;

    // Keys
    const STORAGE_PREFIX = 'sorteador_pro_';
    const PROMOTOR_KEY = STORAGE_PREFIX + 'promotor';
    const HISTORY_KEY = STORAGE_PREFIX + 'history_v6';
    const THEME_KEY = STORAGE_PREFIX + 'theme';
    const SOUND_KEY = STORAGE_PREFIX + 'sound';

    // --- WEB AUDIO API ENGINE ---
    let audioCtx = null;
    function playAudio(type) {
        if (!soundEnabled) return;
        try {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            const now = audioCtx.currentTime;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'tick') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(900, now);
                gain.gain.setValueAtTime(0.08, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.04);
                osc.start(now);
                osc.stop(now + 0.04);
            } else if (type === 'countdown') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(520, now);
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.18);
                osc.start(now);
                osc.stop(now + 0.18);
            } else if (type === 'fanfare') {
                const notes = [523.25, 659.25, 783.99, 1046.50];
                notes.forEach((freq, i) => {
                    const o = audioCtx.createOscillator();
                    const g = audioCtx.createGain();
                    o.connect(g);
                    g.connect(audioCtx.destination);
                    o.type = 'sine';
                    o.frequency.setValueAtTime(freq, now + i * 0.1);
                    g.gain.setValueAtTime(0.12, now + i * 0.1);
                    g.gain.exponentialRampToValueAtTime(0.001, now + i * 0.1 + 0.35);
                    o.start(now + i * 0.1);
                    o.stop(now + i * 0.1 + 0.35);
                });
            } else if (type === 'coin') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(1400, now);
                gain.gain.setValueAtTime(0.1, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.2);
                osc.start(now);
                osc.stop(now + 0.2);
            } else if (type === 'dice') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(220, now);
                gain.gain.setValueAtTime(0.15, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
                osc.start(now);
                osc.stop(now + 0.12);
            }
        } catch (e) {
            console.warn('Audio error:', e);
        }
    }

    // --- PARTICLES & CONFETTI ---
    function initParticles() {
        const container = $('#particles');
        if (!container) return;
        container.innerHTML = '';
        for (let i = 0; i < 24; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDelay = (Math.random() * 12) + 's';
            p.style.animationDuration = (8 + Math.random() * 8) + 's';
            p.style.width = (3 + Math.random() * 4) + 'px';
            p.style.height = p.style.width;
            container.appendChild(p);
        }
    }

    function launchConfetti() {
        const container = $('#confettiContainer');
        if (!container) return;
        container.innerHTML = '';
        const colors = ['#38ef7d', '#11998e', '#58a6ff', '#ffd60a', '#a371f7', '#f778ba', '#ff3b30'];
        for (let i = 0; i < 110; i++) {
            const piece = document.createElement('div');
            piece.className = 'confetti-piece';
            piece.style.left = Math.random() * 100 + '%';
            piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            piece.style.width = (6 + Math.random() * 8) + 'px';
            piece.style.height = (8 + Math.random() * 12) + 'px';
            piece.style.animationDuration = (2 + Math.random() * 2.5) + 's';
            piece.style.animationDelay = (Math.random() * 0.4) + 's';
            container.appendChild(piece);
        }
        setTimeout(() => { container.innerHTML = ''; }, 5000);
    }

    // --- TOAST NOTIFICATIONS ---
    function showToast(message, type = 'info') {
        const container = $('#toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast';
        if (type === 'error') toast.style.borderLeftColor = 'var(--danger-color)';
        if (type === 'success') toast.style.borderLeftColor = 'var(--primary-color)';
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    // --- CRYPTO AUTHENTICITY HASH (SHA-256) ---
    async function generateSha256(text) {
        try {
            const msgBuffer = new TextEncoder().encode(text);
            const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        } catch (e) {
            // Fallback
            let h = 0;
            for (let i = 0; i < text.length; i++) {
                h = Math.imul(31, h) + text.charCodeAt(i) | 0;
            }
            return Math.abs(h).toString(16).padStart(16, '0') + '00000000';
        }
    }

    // --- PROMOTOR STORAGE ---
    function loadPromotor() {
        try {
            const raw = localStorage.getItem(PROMOTOR_KEY);
            if (!raw) return {};
            const data = JSON.parse(raw);
            if ($('#promotorNome')) $('#promotorNome').value = data.nome || '';
            if ($('#promotorWhatsapp')) $('#promotorWhatsapp').value = data.whatsapp || '';
            if ($('#promotorUrl')) $('#promotorUrl').value = data.url || '';
            return data;
        } catch (e) { return {}; }
    }

    function savePromotor() {
        const data = {
            nome: $('#promotorNome') ? $('#promotorNome').value.trim() : '',
            whatsapp: $('#promotorWhatsapp') ? $('#promotorWhatsapp').value.trim() : '',
            url: $('#promotorUrl') ? $('#promotorUrl').value.trim() : ''
        };
        localStorage.setItem(PROMOTOR_KEY, JSON.stringify(data));
        return data;
    }

    // --- TABS CONTROLLER ---
    function switchTab(tabId) {
        currentTab = tabId;
        $$('.tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabId);
        });
        $$('.tab-pane').forEach(pane => {
            pane.classList.toggle('active', pane.id === `tab-${tabId}`);
        });

        // Clear error banners
        $$('.error-banner').forEach(b => { b.style.display = 'none'; b.textContent = ''; });

        if (tabId === 'historico') renderHistory();
        if (tabId === 'roleta') drawWheelPreview();
        playAudio('tick');
    }

    // --- UTILITIES ---
    function shuffleArray(arr) {
        const a = [...arr];
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    function formatDateTime(date) {
        return new Intl.DateTimeFormat('pt-BR', {
            dateStyle: 'short',
            timeStyle: 'medium'
        }).format(new Date(date));
    }

    // --- MODE 1: NÚMEROS ---
    function executeNumeros() {
        const err = $('#erro-numeros');
        const nome = $('#sorteioNomeNumeros').value.trim();
        const qtd = parseInt($('#quantidadeNumeros').value);
        const min = parseInt($('#minimo').value);
        const max = parseInt($('#maximo').value);
        const allowRepeat = $('#repetirNumeros').checked;
        const sortAsc = $('#ordenarNumeros').checked;
        const exclusionsRaw = $('#excluirNumeros').value.trim();

        if (isNaN(qtd) || qtd < 1) return showErr(err, 'Informe uma quantidade válida.');
        if (isNaN(min) || isNaN(max)) return showErr(err, 'Informe o intervalo mínimo e máximo.');
        if (min > max) return showErr(err, 'O valor mínimo não pode ser maior que o máximo.');

        const exclusions = exclusionsRaw ? exclusionsRaw.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n)) : [];
        const available = [];
        for (let i = min; i <= max; i++) {
            if (!exclusions.includes(i)) available.push(i);
        }

        if (available.length === 0) return showErr(err, 'Nenhum número disponível no intervalo com as exclusões.');
        if (!allowRepeat && qtd > available.length) {
            return showErr(err, `Não é possível sortear ${qtd} números sem repetição (${available.length} disponíveis).`);
        }

        startCountdown(() => {
            let results = [];
            if (!allowRepeat) {
                results = shuffleArray(available).slice(0, qtd);
            } else {
                for (let i = 0; i < qtd; i++) {
                    results.push(available[Math.floor(Math.random() * available.length)]);
                }
            }

            if (sortAsc) results.sort((a, b) => a - b);

            finalizeDraw({
                type: 'numeros',
                title: nome || 'Sorteio de Números',
                results,
                details: `Intervalo: ${min} a ${max} | Repetição: ${allowRepeat ? 'Sim' : 'Não'}`
            });
        });
    }

    // --- MODE 2: NOMES & GRUPOS ---
    function executeNomes() {
        const err = $('#erro-nomes');
        const nome = $('#sorteioNomeNomes').value.trim();
        const rawText = $('#listaNomes').value.trim();
        const qtd = parseInt($('#quantidadeNomes').value);
        const numGrupos = parseInt($('#numGrupos').value) || 0;
        const sortAlpha = $('#ordenarNomes').checked;
        const exclusionsRaw = $('#excluirNomes').value.trim();

        if (!rawText) return showErr(err, 'Insira ao menos um nome na lista.');
        let names = [...new Set(rawText.split(/[\n,]+/).map(s => s.trim()).filter(Boolean))];

        if (exclusionsRaw) {
            const excl = exclusionsRaw.split(',').map(s => s.trim().toLowerCase());
            names = names.filter(n => !excl.includes(n.toLowerCase()));
        }

        if (names.length === 0) return showErr(err, 'Nenhum nome disponível após exclusões.');
        if (isNaN(qtd) || qtd < 1) return showErr(err, 'Informe uma quantidade válida de sorteados.');
        if (qtd > names.length) return showErr(err, `Quantidade solicitada (${qtd}) maior que nomes disponíveis (${names.length}).`);

        startCountdown(() => {
            let drawn = shuffleArray(names).slice(0, qtd);
            if (sortAlpha) drawn.sort((a, b) => a.localeCompare(b, 'pt-BR'));

            let groups = null;
            if (numGrupos > 1) {
                groups = [];
                for (let i = 0; i < numGrupos; i++) groups.push([]);
                drawn.forEach((item, idx) => {
                    groups[idx % numGrupos].push(item);
                });
            }

            finalizeDraw({
                type: 'nomes',
                title: nome || 'Sorteio de Nomes',
                results: drawn,
                groups,
                details: `Total de participantes: ${names.length}${numGrupos > 1 ? ` | ${numGrupos} Grupos Formados` : ''}`
            });
        });
    }

    // --- MODE 3: ROLETA INTERATIVA DA FORTUNA ---
    let wheelCanvas, wheelCtx;
    let wheelAngle = 0;
    let wheelVelocity = 0;
    let wheelIsSpinning = false;
    let wheelItems = [];

    function getWheelItems() {
        const text = $('#roletaItems').value.trim();
        if (!text) return ['Prêmio 1', 'Prêmio 2', 'Prêmio 3', 'Tente Novamente', 'Prêmio 4', 'Passa a Vez'];
        return text.split(/[\n,]+/).map(s => s.trim()).filter(Boolean);
    }

    function drawWheelPreview() {
        wheelCanvas = $('#wheelCanvas');
        if (!wheelCanvas) return;
        wheelCtx = wheelCanvas.getContext('2d');
        wheelItems = getWheelItems();

        const size = wheelCanvas.width;
        const center = size / 2;
        const radius = center - 12;
        const numSlices = wheelItems.length;
        const arc = (2 * Math.PI) / numSlices;

        const colors = [
            '#ff3b30', '#ff9500', '#ffd60a', '#34c759', '#00c7be',
            '#30b0c7', '#32ade6', '#007aff', '#5856d6', '#af52de'
        ];

        wheelCtx.clearRect(0, 0, size, size);

        for (let i = 0; i < numSlices; i++) {
            const angle = wheelAngle + i * arc;
            wheelCtx.beginPath();
            wheelCtx.fillStyle = colors[i % colors.length];
            wheelCtx.moveTo(center, center);
            wheelCtx.arc(center, center, radius, angle, angle + arc);
            wheelCtx.lineTo(center, center);
            wheelCtx.fill();
            wheelCtx.strokeStyle = 'rgba(255,255,255,0.3)';
            wheelCtx.lineWidth = 2;
            wheelCtx.stroke();

            // Text
            wheelCtx.save();
            wheelCtx.translate(center, center);
            wheelCtx.rotate(angle + arc / 2);
            wheelCtx.textAlign = 'right';
            wheelCtx.fillStyle = '#ffffff';
            wheelCtx.font = 'bold 15px Roboto, sans-serif';
            wheelCtx.shadowColor = 'rgba(0,0,0,0.8)';
            wheelCtx.shadowBlur = 4;
            let label = wheelItems[i];
            if (label.length > 14) label = label.slice(0, 12) + '...';
            wheelCtx.fillText(label, radius - 20, 5);
            wheelCtx.restore();
        }
    }

    function spinWheel() {
        if (wheelIsSpinning) return;
        wheelItems = getWheelItems();
        if (wheelItems.length < 2) {
            return showToast('Insira ao menos 2 itens para girar a roleta!', 'error');
        }

        wheelIsSpinning = true;
        $('#spinWheelBtn').disabled = true;
        wheelVelocity = Math.random() * 0.18 + 0.35; // initial angular velocity

        let lastSliceIndex = -1;
        const arc = (2 * Math.PI) / wheelItems.length;

        function animateSpin() {
            wheelAngle += wheelVelocity;
            wheelVelocity *= 0.987; // friction deceleration

            // Pointer tick sound
            // Pointer is at the top (angle 3*PI/2)
            const normalizedAngle = (2 * Math.PI - (wheelAngle % (2 * Math.PI))) % (2 * Math.PI);
            const currentSlice = Math.floor(normalizedAngle / arc) % wheelItems.length;
            if (currentSlice !== lastSliceIndex) {
                lastSliceIndex = currentSlice;
                playAudio('tick');
            }

            drawWheelPreview();

            if (wheelVelocity > 0.002) {
                requestAnimationFrame(animateSpin);
            } else {
                wheelIsSpinning = false;
                $('#spinWheelBtn').disabled = false;
                const winnerIndex = (wheelItems.length - 1 - Math.floor(((wheelAngle + Math.PI / 2) % (2 * Math.PI)) / arc)) % wheelItems.length;
                const winner = wheelItems[(winnerIndex + wheelItems.length) % wheelItems.length] || wheelItems[0];

                finalizeDraw({
                    type: 'roleta',
                    title: $('#sorteioNomeRoleta').value.trim() || 'Roleta da Fortuna',
                    results: [winner],
                    details: `Itens na roleta: ${wheelItems.length}`
                });
            }
        }
        animateSpin();
    }

    // --- MODE 4: GLOBO DE BINGO ---
    function initBingo() {
        const max = parseInt($('#bingoMax').value) || 75;
        bingoRemaining = [];
        bingoDrawn = [];
        for (let i = 1; i <= max; i++) bingoRemaining.push(i);
        $('#bingoActiveBall').textContent = '--';
        $('#bingoHistoryTray').innerHTML = '';
        $('#bingoRemainingCount').textContent = bingoRemaining.length;
        $('#bingoDrawnCount').textContent = '0';
        showToast(`Globo de Bingo configurado com 1 a ${max}.`, 'info');
    }

    function drawBingoBall() {
        if (bingoRemaining.length === 0) {
            return showToast('Todas as bolinhas já foram sorteadas!', 'info');
        }

        const idx = Math.floor(Math.random() * bingoRemaining.length);
        const ball = bingoRemaining.splice(idx, 1)[0];
        bingoDrawn.unshift(ball);

        playAudio('coin');

        // Animation
        const ballEl = $('#bingoActiveBall');
        ballEl.textContent = ball;
        ballEl.style.animation = 'none';
        void ballEl.offsetWidth; // trigger reflow
        ballEl.style.animation = 'ballPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';

        // Tray
        const tray = $('#bingoHistoryTray');
        const mini = document.createElement('div');
        mini.className = 'mini-ball';
        mini.textContent = ball;
        tray.prepend(mini);

        $('#bingoRemainingCount').textContent = bingoRemaining.length;
        $('#bingoDrawnCount').textContent = bingoDrawn.length;

        if (bingoRemaining.length === 0) {
            launchConfetti();
            playAudio('fanfare');
        }
    }

    // --- MODE 5: AMIGO SECRETO (ZERO KNOWLEDGE DERANGEMENT) ---
    function executeAmigoSecreto() {
        const err = $('#erro-amigo');
        const raw = $('#listaAmigoSecreto').value.trim();
        if (!raw) return showErr(err, 'Insira a lista de participantes.');

        const names = [...new Set(raw.split(/[\n,]+/).map(s => s.trim()).filter(Boolean))];
        if (names.length < 3) return showErr(err, 'O Amigo Secreto requer ao menos 3 participantes.');

        // Derangement algorithm (P(i) != i)
        let pairs = [];
        let valid = false;
        let attempts = 0;

        while (!valid && attempts < 500) {
            attempts++;
            const receivers = shuffleArray(names);
            valid = true;
            for (let i = 0; i < names.length; i++) {
                if (names[i] === receivers[i]) {
                    valid = false;
                    break;
                }
            }
            if (valid) {
                pairs = names.map((giver, idx) => ({ giver, receiver: receivers[idx] }));
            }
        }

        if (!valid) return showErr(err, 'Erro ao calcular combinações sem repetição. Tente novamente.');

        // Render Secret Cards
        const grid = $('#amigoCardsGrid');
        grid.innerHTML = '';
        $('#amigoResultContainer').style.display = 'block';

        pairs.forEach((p, index) => {
            const card = document.createElement('div');
            card.className = 'secret-card';

            const encodedMsg = encodeURIComponent(`🎁 Olá *${p.giver}*! Seu amigo secreto sorteado no Sorteador Pro Max é: 🤫 *${p.receiver}*! Guarde segredo!`);
            const waLink = `https://wa.me/?text=${encodedMsg}`;

            card.innerHTML = `
                <div class="secret-card-header">
                    <span class="secret-giver-name"><i class="fas fa-user-secret"></i> ${p.giver}</span>
                    <span class="form-hint">Cartão #${index + 1}</span>
                </div>
                <div class="scratch-box" id="scratch-${index}">
                    <span class="scratch-hint"><i class="fas fa-hand-pointer"></i> Toque para revelar</span>
                    <span class="scratch-content">${p.receiver}</span>
                </div>
                <div class="secret-card-actions">
                    <a href="${waLink}" target="_blank" class="btn-whatsapp-direct">
                        <i class="fab fa-whatsapp"></i> Enviar p/ ${p.giver}
                    </a>
                </div>
            `;

            const box = card.querySelector('.scratch-box');
            box.addEventListener('click', () => {
                box.classList.toggle('revealed');
                playAudio('tick');
            });

            grid.appendChild(card);
        });

        // Save history with encrypted/anonymized log
        saveHistory({
            type: 'amigo',
            title: $('#sorteioNomeAmigo').value.trim() || 'Amigo Secreto',
            timestamp: new Date().toISOString(),
            results: pairs.map(p => `${p.giver} ➔ [Segredo Revelado]`),
            promotor: savePromotor(),
            details: `Participantes: ${names.length}`
        });

        showToast('Amigo secreto gerado! Envie os links ou use os cartões raspadinha.', 'success');
        playAudio('fanfare');
    }

    // --- MODE 6: SORTEIO PONDERADO / RIFAS ---
    function executePonderado() {
        const err = $('#erro-rifas');
        const raw = $('#listaRifas').value.trim();
        const qtd = parseInt($('#quantidadeRifas').value) || 1;
        if (!raw) return showErr(err, 'Insira as entradas de bilhetes/pesos.');

        // Parse: "Nome, 5" or "Nome [10]" or "Nome (3)"
        const lines = raw.split('\n').map(s => s.trim()).filter(Boolean);
        const pool = [];
        const summary = [];

        lines.forEach(line => {
            const match = line.match(/^([^,;(\[]+)(?:[,;(\[]+(\d+)[)\]]*)?$/);
            if (match) {
                const name = match[1].trim();
                const weight = match[2] ? parseInt(match[2]) : 1;
                if (name && weight > 0) {
                    summary.push({ name, weight });
                    for (let w = 0; w < weight; w++) pool.push(name);
                }
            }
        });

        if (pool.length === 0) return showErr(err, 'Formato inválido. Exemplo: Maria, 5 ou João [10].');

        startCountdown(() => {
            const winners = [];
            const tempPool = [...pool];
            const maxDraw = Math.min(qtd, new Set(pool).size);

            for (let i = 0; i < maxDraw; i++) {
                if (tempPool.length === 0) break;
                const pick = tempPool[Math.floor(Math.random() * tempPool.length)];
                winners.push(pick);
                // Remove all occurrences of picked name to prevent duplicate winners
                for (let k = tempPool.length - 1; k >= 0; k--) {
                    if (tempPool[k] === pick) tempPool.splice(k, 1);
                }
            }

            finalizeDraw({
                type: 'rifas',
                title: $('#sorteioNomeRifas').value.trim() || 'Sorteio Ponderado / Rifa',
                results: winners,
                details: `Total de bilhetes concorrendo: ${pool.length} | Participantes: ${summary.length}`
            });
        });
    }

    // --- MODE 7: DADOS & MOEDA ---
    function rollDice() {
        const err = $('#erro-dados');
        const count = parseInt($('#quantidadeDados').value);
        const mod = parseInt($('#modificadorDados').value) || 0;

        if (isNaN(count) || count < 1 || count > 30) {
            return showErr(err, 'Quantidade deve ser entre 1 e 30.');
        }

        playAudio('dice');
        const results = [];
        let sum = 0;
        for (let i = 0; i < count; i++) {
            const val = Math.floor(Math.random() * selectedDie) + 1;
            results.push(val);
            sum += val;
        }

        const total = sum + mod;
        diceStats.rolls += count;
        diceStats.total += sum;
        diceStats.max = Math.max(diceStats.max, ...results);

        const tray = $('#diceResultsTray');
        tray.innerHTML = '';
        results.forEach(val => {
            const d = document.createElement('div');
            d.className = 'die-item';
            d.textContent = val;
            tray.appendChild(d);
        });

        $('#diceTotalText').innerHTML = count > 1 || mod !== 0
            ? `Soma: <strong>${sum}</strong> ${mod !== 0 ? `(${mod >= 0 ? '+' : ''}${mod}) = <strong>${total}</strong>` : ''}`
            : `Resultado: <strong>${total}</strong>`;

        $('#diceResultsBox').style.display = 'block';
        $('#diceStatsBox').style.display = 'block';

        $('#statRolls').textContent = diceStats.rolls;
        $('#statTotal').textContent = diceStats.total;
        $('#statAvg').textContent = (diceStats.total / diceStats.rolls).toFixed(1);
        $('#statMax').textContent = diceStats.max;

        saveHistory({
            type: 'dados',
            title: `${count}d${selectedDie}${mod ? (mod > 0 ? '+' + mod : mod) : ''}`,
            timestamp: new Date().toISOString(),
            results,
            promotor: savePromotor(),
            details: `Total: ${total}`
        });
    }

    function flipCoin() {
        const coin = $('#coinObject');
        const btn = $('#flipCoinBtn');
        if (!coin || btn.disabled) return;

        btn.disabled = true;
        playAudio('coin');

        const isHeads = Math.random() < 0.5;
        coinStats.flips++;
        if (isHeads) coinStats.heads++;
        else coinStats.tails++;

        // Random multi-rotation
        const rotations = 5 + Math.floor(Math.random() * 4);
        const finalDeg = rotations * 360 + (isHeads ? 0 : 180);
        coin.style.transform = `rotateY(${finalDeg}deg)`;

        setTimeout(() => {
            btn.disabled = false;
            $('#coinResultText').innerHTML = isHeads
                ? '<i class="fas fa-crown" style="color:#ffd60a"></i> CARA!'
                : '<i class="fas fa-dragon" style="color:#c9d1d9"></i> COROA!';

            $('#coinFlips').textContent = coinStats.flips;
            $('#coinHeads').textContent = coinStats.heads;
            $('#coinTails').textContent = coinStats.tails;
            $('#coinPercent').textContent = ((coinStats.heads / coinStats.flips) * 100).toFixed(0) + '%';

            playAudio('fanfare');

            saveHistory({
                type: 'moeda',
                title: 'Lançamento de Moeda',
                timestamp: new Date().toISOString(),
                results: [isHeads ? 'Cara' : 'Coroa'],
                promotor: savePromotor()
            });
        }, 1200);
    }

    // --- COUNTDOWN & DISPLAY RESULTS ---
    function startCountdown(onComplete) {
        const countdownSec = $('#countdownSection');
        const timerEl = $('#countdownTimer');
        const activePane = $(`#tab-${currentTab}`);

        if (activePane) activePane.style.display = 'none';
        $('#resultsSection').style.display = 'none';
        countdownSec.style.display = 'block';

        let count = 3;
        timerEl.textContent = count;
        playAudio('countdown');

        const timer = setInterval(() => {
            count--;
            if (count > 0) {
                timerEl.textContent = count;
                playAudio('countdown');
            } else {
                clearInterval(timer);
                countdownSec.style.display = 'none';
                if (activePane) activePane.style.display = 'block';
                onComplete();
            }
        }, 1000);
    }

    async function finalizeDraw(data) {
        const resultsSec = $('#resultsSection');
        const flow = $('#resultsItemsFlow');
        const groupsDiv = $('#resultsGroupsDiv');
        const promotorBox = $('#resultsPromotorBox');
        const hashEl = $('#resultsSha256');

        flow.innerHTML = '';
        groupsDiv.innerHTML = '';
        groupsDiv.style.display = 'none';

        const now = new Date();
        const promotor = savePromotor();

        // Calculate SHA-256 Authenticity Hash
        const payloadString = `${data.type}|${data.title}|${data.results.join(',')}|${now.toISOString()}|${promotor.nome || 'anon'}`;
        const sha256 = await generateSha256(payloadString);

        lastDrawData = {
            ...data,
            timestamp: now.toISOString(),
            sha256,
            promotor
        };

        $('#resultsTitle').textContent = data.title;
        $('#resultsTimestamp').textContent = `Realizado em: ${formatDateTime(now)}`;

        // Promotor display
        if (promotor.nome) {
            let pTxt = `<strong><i class="fas fa-bullhorn"></i> Promotor:</strong> ${promotor.nome}`;
            if (promotor.whatsapp) pTxt += ` | <i class="fab fa-whatsapp"></i> ${promotor.whatsapp}`;
            if (promotor.url) pTxt += ` | <i class="fas fa-globe"></i> ${promotor.url}`;
            promotorBox.innerHTML = pTxt;
            promotorBox.style.display = 'block';
        } else {
            promotorBox.style.display = 'none';
        }

        // SHA-256 Badge
        hashEl.textContent = sha256;

        // Display results / groups
        if (data.groups && data.groups.length > 1) {
            data.groups.forEach((grp, idx) => {
                const gb = document.createElement('div');
                gb.className = 'group-box';
                gb.innerHTML = `<strong>Grupo ${idx + 1}:</strong> ${grp.join(', ')}`;
                groupsDiv.appendChild(gb);
            });
            groupsDiv.style.display = 'block';
        } else {
            data.results.forEach((item, idx) => {
                const b = document.createElement('div');
                b.className = 'result-badge';
                b.textContent = item;
                b.style.animationDelay = (idx * 0.08) + 's';
                flow.appendChild(b);
            });
        }

        resultsSec.style.display = 'block';
        resultsSec.scrollIntoView({ behavior: 'smooth', block: 'start' });

        launchConfetti();
        playAudio('fanfare');
        saveHistory(lastDrawData);
    }

    // --- INSTAGRAM STORIES GENERATOR (1080x1920 CANVAS) ---
    function generateInstagramStories() {
        if (!lastDrawData) return showToast('Nenhum resultado recente para gerar Story.', 'error');

        const canvas = document.createElement('canvas');
        canvas.width = 1080;
        canvas.height = 1920;
        const ctx = canvas.getContext('2d');

        // Dark Cyber Gradient Background
        const bgGrad = ctx.createLinearGradient(0, 0, 1080, 1920);
        bgGrad.addColorStop(0, '#0d1117');
        bgGrad.addColorStop(0.5, '#161b22');
        bgGrad.addColorStop(1, '#090d12');
        ctx.fillStyle = bgGrad;
        ctx.fillRect(0, 0, 1080, 1920);

        // Glowing Ambient Circles
        ctx.save();
        ctx.filter = 'blur(80px)';
        ctx.fillStyle = 'rgba(56, 239, 125, 0.25)';
        ctx.beginPath();
        ctx.arc(200, 300, 250, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = 'rgba(163, 113, 247, 0.2)';
        ctx.beginPath();
        ctx.arc(880, 1500, 300, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();

        // Neon Border Frame
        ctx.strokeStyle = '#38ef7d';
        ctx.lineWidth = 8;
        ctx.strokeRect(40, 40, 1000, 1840);

        // Header Title
        ctx.textAlign = 'center';
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 54px Poppins, sans-serif';
        ctx.fillText('SORTEADOR PRO MAX', 540, 160);

        ctx.font = '32px Orbitron, monospace';
        ctx.fillStyle = '#38ef7d';
        ctx.fillText('CERTIFICADO DIGITAL DE RESULTADO', 540, 220);

        // Draw Name / Title
        ctx.font = 'bold 64px Poppins, sans-serif';
        ctx.fillStyle = '#ffd60a';
        ctx.fillText(lastDrawData.title || 'Resultado Oficial', 540, 380);

        // Date & Timestamp
        ctx.font = '32px Roboto, sans-serif';
        ctx.fillStyle = '#8b949e';
        ctx.fillText(formatDateTime(lastDrawData.timestamp), 540, 440);

        // Winner Card Container
        ctx.fillStyle = 'rgba(33, 38, 45, 0.9)';
        ctx.strokeStyle = 'rgba(56, 239, 125, 0.4)';
        ctx.lineWidth = 4;
        ctx.roundRect(100, 520, 880, 860, 28);
        ctx.fill();
        ctx.stroke();

        // Winners List
        ctx.fillStyle = '#38ef7d';
        ctx.font = 'bold 36px Orbitron, sans-serif';
        ctx.fillText('★ SORTEADO(S) ★', 540, 600);

        const maxWinnersToShow = 8;
        const res = lastDrawData.results.slice(0, maxWinnersToShow);
        const startY = 700;
        const spacing = Math.min(100, 680 / (res.length || 1));

        res.forEach((item, idx) => {
            const y = startY + idx * spacing;
            ctx.fillStyle = 'rgba(56, 239, 125, 0.15)';
            ctx.roundRect(160, y - 48, 760, 72, 16);
            ctx.fill();

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 44px Roboto, sans-serif';
            let str = `${idx + 1}º  ${item}`;
            if (str.length > 24) str = str.slice(0, 22) + '...';
            ctx.fillText(str, 540, y);
        });

        if (lastDrawData.results.length > maxWinnersToShow) {
            ctx.fillStyle = '#8b949e';
            ctx.font = 'italic 30px Roboto, sans-serif';
            ctx.fillText(`+ ${lastDrawData.results.length - maxWinnersToShow} outros resultados`, 540, 1340);
        }

        // Promoter Box
        if (lastDrawData.promotor && lastDrawData.promotor.nome) {
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 36px Roboto, sans-serif';
            ctx.fillText(`Promovido por: ${lastDrawData.promotor.nome}`, 540, 1470);
            if (lastDrawData.promotor.whatsapp) {
                ctx.font = '30px Roboto, sans-serif';
                ctx.fillStyle = '#8b949e';
                ctx.fillText(`WhatsApp: ${lastDrawData.promotor.whatsapp}`, 540, 1520);
            }
        }

        // SHA-256 Stamp
        ctx.fillStyle = 'rgba(13, 17, 23, 0.9)';
        ctx.roundRect(80, 1620, 920, 100, 14);
        ctx.fill();
        ctx.fillStyle = '#58a6ff';
        ctx.font = 'bold 24px Orbitron, monospace';
        ctx.fillText('HASH SHA-256 DE AUTENTICIDADE:', 540, 1655);
        ctx.fillStyle = '#e6edf3';
        ctx.font = '20px monospace';
        ctx.fillText(lastDrawData.sha256 || 'N/A', 540, 1695);

        // Watermark
        ctx.fillStyle = '#8b949e';
        ctx.font = '28px Poppins, sans-serif';
        ctx.fillText('4u.ia.br/app/sorteio • @4U-Labs', 540, 1810);

        // Show Modal Preview
        const previewCanvas = $('#storiesCanvasPreview');
        const modal = $('#modalStories');
        if (previewCanvas && modal) {
            previewCanvas.width = canvas.width;
            previewCanvas.height = canvas.height;
            const pCtx = previewCanvas.getContext('2d');
            pCtx.drawImage(canvas, 0, 0);

            $('#downloadStoriesBtn').onclick = () => {
                const link = document.createElement('a');
                link.download = `sorteio_story_${Date.now()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                showToast('Card para Instagram Stories baixado!', 'success');
            };

            modal.classList.add('active');
        }
    }

    // --- A4 AUDIT CERTIFICATE PRINT TRIGGER ---
    function triggerPrintCertificate() {
        if (!lastDrawData) return showToast('Nenhum resultado recente para imprimir.', 'error');

        const cert = $('#printCertificate');
        if (!cert) return window.print();

        const p = lastDrawData.promotor || {};
        let resultsRows = '';
        lastDrawData.results.forEach((r, idx) => {
            resultsRows += `<tr><td><strong>#${idx + 1}</strong></td><td>${r}</td></tr>`;
        });

        cert.innerHTML = `
            <div class="cert-header">
                <h1>Certificado Oficial de Sorteio</h1>
                <p>Auditoria Digital de Concurso e Distribuição Aleatória</p>
            </div>
            <div class="cert-body">
                <p>Certificamos para os devidos fins legais e de auditoria que foi realizado com total transparência e imparcialidade o sorteio intitulado <strong>"${lastDrawData.title || 'Sorteio Oficial'}"</strong> através da plataforma <strong>Sorteador Pro Max (4U.IA.BR)</strong>.</p>
                
                <table style="width:100%; margin: 15px 0;">
                    <tr><td><strong>Data e Hora:</strong></td><td>${formatDateTime(lastDrawData.timestamp)}</td></tr>
                    <tr><td><strong>Modalidade:</strong></td><td>${lastDrawData.type.toUpperCase()}</td></tr>
                    ${p.nome ? `<tr><td><strong>Promotor Responsável:</strong></td><td>${p.nome}</td></tr>` : ''}
                    ${p.whatsapp ? `<tr><td><strong>WhatsApp de Contato:</strong></td><td>${p.whatsapp}</td></tr>` : ''}
                    ${lastDrawData.details ? `<tr><td><strong>Parâmetros:</strong></td><td>${lastDrawData.details}</td></tr>` : ''}
                </table>

                <h3 style="margin-top:20px;">Resultados Homologados:</h3>
                <table class="cert-results-table">
                    <thead><tr><th style="width:80px;">Posição</th><th>Sorteado / Bilhete</th></tr></thead>
                    <tbody>${resultsRows}</tbody>
                </table>
            </div>

            <div class="cert-signatures">
                <div class="cert-sig-line">
                    ${p.nome || 'Promotor do Evento'}<br>
                    <small>Assinatura do Responsável</small>
                </div>
                <div class="cert-sig-line">
                    Sorteador Pro Max Engine<br>
                    <small>Validação Criptográfica do Sistema</small>
                </div>
            </div>

            <div class="cert-footer-hash">
                SELO CRIPTOGRÁFICO SHA-256: ${lastDrawData.sha256}<br>
                Autenticidade verificável em https://4u.ia.br/app/sorteio/
            </div>
        `;

        window.print();
    }

    // --- HISTORY STORAGE & RENDER ---
    function loadHistory() {
        try {
            return JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
        } catch (e) { return []; }
    }

    function saveHistory(item) {
        if (!item) return;
        const hist = loadHistory();
        hist.unshift(item);
        if (hist.length > 25) hist.pop();
        localStorage.setItem(HISTORY_KEY, JSON.stringify(hist));
    }

    function renderHistory() {
        const hist = loadHistory();
        const container = $('#historyContainer');
        if (!container) return;
        container.innerHTML = '';

        if (hist.length === 0) {
            container.innerHTML = '<p class="form-hint" style="text-align:center; padding: 20px;">Nenhum sorteio registrado no histórico.</p>';
            return;
        }

        hist.forEach((h, idx) => {
            const card = document.createElement('div');
            card.className = 'history-card';
            card.innerHTML = `
                <div class="history-header">
                    <span class="history-type-badge">${(h.type || 'Sorteio').toUpperCase()}</span>
                    <span>${formatDateTime(h.timestamp)}</span>
                </div>
                <h4 style="margin-bottom:6px; color: var(--text-color);">${h.title || 'Sorteio'}</h4>
                <p style="font-size:0.9rem; color:var(--primary-color); word-break:break-all;"><strong>Resultados:</strong> ${Array.isArray(h.results) ? h.results.join(', ') : h.results}</p>
                ${h.sha256 ? `<p style="font-size:0.75rem; color:var(--subtitle-color); font-family:monospace; margin-top:4px;">SHA-256: ${h.sha256.slice(0, 16)}...</p>` : ''}
            `;
            container.appendChild(card);
        });
    }

    function exportHistoryCSV() {
        const hist = loadHistory();
        if (hist.length === 0) return showToast('Histórico vazio.', 'info');

        let csv = 'Data,Tipo,Titulo,Resultados,SHA256\n';
        hist.forEach(h => {
            const res = Array.isArray(h.results) ? h.results.join('; ') : h.results;
            csv += `"${formatDateTime(h.timestamp)}","${h.type}","${h.title || ''}","${res}","${h.sha256 || ''}"\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `historico_sorteador_${Date.now()}.csv`;
        link.click();
        showToast('Histórico exportado em CSV!', 'success');
    }

    // --- SHARE FUNCTIONS ---
    function shareWhatsApp() {
        if (!lastDrawData) return;
        const msg = encodeURIComponent(`🎉 *${lastDrawData.title || 'Resultado do Sorteio'}*\n\nResultados: ${lastDrawData.results.join(', ')}\n📅 Data: ${formatDateTime(lastDrawData.timestamp)}\n🛡️ Hash SHA-256: ${lastDrawData.sha256}\n\nFeito no Sorteador Pro Max: https://4u.ia.br/app/sorteio/`);
        window.open(`https://wa.me/?text=${msg}`, '_blank');
    }

    function shareTwitter() {
        if (!lastDrawData) return;
        const msg = encodeURIComponent(`🎉 ${lastDrawData.title}: ${lastDrawData.results.join(', ')} - Sorteio verificado via Sorteador Pro Max! https://4u.ia.br/app/sorteio/`);
        window.open(`https://twitter.com/intent/tweet?text=${msg}`, '_blank');
    }

    function copyResultText() {
        if (!lastDrawData) return;
        const text = `🎉 ${lastDrawData.title}\nResultados: ${lastDrawData.results.join(', ')}\nData: ${formatDateTime(lastDrawData.timestamp)}\nSHA-256: ${lastDrawData.sha256}\nhttps://4u.ia.br/app/sorteio/`;
        navigator.clipboard.writeText(text).then(() => {
            showToast('Resultados copiados com sucesso!', 'success');
        });
    }

    function showErr(el, msg) {
        if (!el) return showToast(msg, 'error');
        el.textContent = msg;
        el.style.display = 'flex';
    }

    // --- BIND EVENT LISTENERS ---
    function bindEvents() {
        // Theme Toggle
        $('#themeToggle').addEventListener('click', () => {
            darkMode = !darkMode;
            document.body.classList.toggle('light-mode', !darkMode);
            localStorage.setItem(THEME_KEY, darkMode ? 'dark' : 'light');
            $('#themeToggle i').className = darkMode ? 'fas fa-moon' : 'fas fa-sun';
            playAudio('tick');
        });

        // Sound Toggle
        $('#soundToggle').addEventListener('click', () => {
            soundEnabled = !soundEnabled;
            localStorage.setItem(SOUND_KEY, soundEnabled ? '1' : '0');
            $('#soundToggle i').className = soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
        });

        // Promotor Header Toggle
        $('#promotorToggle').addEventListener('click', () => {
            const fields = $('#promotorFields');
            const chev = $('#promotorChevron');
            fields.classList.toggle('collapsed');
            chev.style.transform = fields.classList.contains('collapsed') ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        // Tabs
        $$('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => switchTab(btn.dataset.tab));
        });

        // Numeros
        $('#sortearNumerosBtn').addEventListener('click', executeNumeros);
        $('#limparNumerosBtn').addEventListener('click', () => {
            $('#sorteioNomeNumeros').value = '';
            $('#quantidadeNumeros').value = '1';
            $('#minimo').value = '1';
            $('#maximo').value = '100';
            $('#excluirNumeros').value = '';
        });

        // Nomes
        $('#sortearNomesBtn').addEventListener('click', executeNomes);
        $('#listaNomes').addEventListener('input', () => {
            const count = $('#listaNomes').value.trim().split(/[\n,]+/).filter(Boolean).length;
            $('#nomeCountSpan').textContent = `(${count} nomes)`;
        });
        $('#importNomesFile').addEventListener('change', (e) => {
            const f = e.target.files[0];
            if (!f) return;
            const r = new FileReader();
            r.onload = (evt) => {
                $('#listaNomes').value = evt.target.result;
                const count = evt.target.result.split(/[\n,]+/).filter(Boolean).length;
                $('#nomeCountSpan').textContent = `(${count} nomes)`;
                showToast(`${count} nomes importados!`, 'success');
            };
            r.readAsText(f);
        });

        // Roleta
        $('#spinWheelBtn').addEventListener('click', spinWheel);
        $('#roletaItems').addEventListener('input', drawWheelPreview);

        // Bingo
        $('#initBingoBtn').addEventListener('click', initBingo);
        $('#drawBingoBtn').addEventListener('click', drawBingoBall);

        // Amigo Secreto
        $('#sortearAmigoBtn').addEventListener('click', executeAmigoSecreto);

        // Rifas
        $('#sortearRifasBtn').addEventListener('click', executePonderado);

        // Dados
        $$('.dice-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                $$('.dice-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedDie = parseInt(btn.dataset.sides);
                playAudio('tick');
            });
        });
        $('#rolarDadosBtn').addEventListener('click', rollDice);

        // Moeda
        $('#flipCoinBtn').addEventListener('click', flipCoin);

        // Results Actions
        $('#shareWhatsAppBtn').addEventListener('click', shareWhatsApp);
        $('#shareTwitterBtn').addEventListener('click', shareTwitter);
        $('#copyResultBtn').addEventListener('click', copyResultText);
        $('#storyResultBtn').addEventListener('click', generateInstagramStories);
        $('#printCertBtn').addEventListener('click', triggerPrintCertificate);
        $('#newDrawBtn').addEventListener('click', () => {
            $('#resultsSection').style.display = 'none';
            switchTab(currentTab);
        });

        // Modals close
        $$('.modal-close').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.modal-backdrop').classList.remove('active');
            });
        });

        // History
        $('#exportHistoryBtn').addEventListener('click', exportHistoryCSV);
        $('#clearHistoryBtn').addEventListener('click', () => {
            if (confirm('Deseja realmente limpar todo o histórico de sorteios?')) {
                localStorage.removeItem(HISTORY_KEY);
                renderHistory();
                showToast('Histórico apagado.', 'info');
            }
        });

        // Shortcuts Modal
        $('#shortcutsBtn').addEventListener('click', () => $('#modalShortcuts').classList.add('active'));

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return;
            const key = e.key.toLowerCase();
            if (key === 't') $('#themeToggle').click();
            if (key === 's') $('#soundToggle').click();
            if (key === '1') switchTab('numeros');
            if (key === '2') switchTab('nomes');
            if (key === '3') switchTab('roleta');
            if (key === '4') switchTab('bingo');
            if (key === '5') switchTab('amigo');
            if (key === '6') switchTab('rifas');
            if (key === '7') switchTab('dados');
            if (key === '8') switchTab('moeda');
            if (key === '9') switchTab('historico');
            if (key === 'n') $('#newDrawBtn').click();
            if (key === 'escape') $$('.modal-backdrop').forEach(m => m.classList.remove('active'));
        });
    }

    // --- SERVICE WORKER REGISTRATION (Network-First & Cache Invalidation) ---
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('./service-worker.js', { updateViaCache: 'none' })
                .then(reg => console.log('SW Registered:', reg.scope))
                .catch(err => console.warn('SW Error:', err));
        });
    }

    // --- INITIALIZE ---
    loadPromotor();
    initParticles();
    bindEvents();

    // Stored theme
    const savedTheme = localStorage.getItem(THEME_KEY);
    if (savedTheme === 'light') {
        darkMode = false;
        document.body.classList.add('light-mode');
        $('#themeToggle i').className = 'fas fa-sun';
    }

    // Stored sound
    const savedSound = localStorage.getItem(SOUND_KEY);
    if (savedSound === '0') {
        soundEnabled = false;
        $('#soundToggle i').className = 'fas fa-volume-mute';
    }

    drawWheelPreview();
    initBingo();
});
