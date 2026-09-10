<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Política de Privacidade & LGPD — Sorteador Pro Max</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800;900&family=Poppins:wght@500;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?= $v ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="favicon.png">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
</head>
<body class="legal-body">
    <!-- Animated background particles -->
    <div class="particles" id="particles"></div>

    <div class="legal-container">
        <header class="legal-header">
            <a href="index.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar ao Sorteador</a>
            <div class="brand-mini">
                <i class="fa-solid fa-dice-d20 brand-icon"></i>
                <span>Sorteador Pro Max</span>
            </div>
        </header>

        <main class="legal-card">
            <h1><i class="fa-solid fa-shield-halved"></i> Política de Privacidade & LGPD</h1>
            <p class="legal-subtitle">Em total conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018) e Princípios de Retenção Zero</p>

            <section class="legal-section">
                <h2><i class="fa-solid fa-microchip"></i> 1. Arquitetura de Retenção Zero (Privacy by Design)</h2>
                <p>O <strong>Sorteador Pro Max (4U Sorteio)</strong> foi arquitetado sob a premissa fundamental de <strong>Retenção Zero</strong>. Todas as listas de participantes, dezenas de rifas, números e resultados sorteados são manipulados <strong>exclusivamente na memória RAM do seu navegador</strong>.</p>
                <p>Nenhuma lista digitada, colada ou importada via arquivo (.txt/.csv) é enviada, registrada ou interceptada por servidores externos, bancos de dados em nuvem ou serviços de telemetria.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-database"></i> 2. Armazenamento Local Estritamente Pessoal (LocalStorage)</h2>
                <p>O aplicativo faz uso exclusivo do <code>localStorage</code> interno do seu próprio dispositivo, estritamente para sua comodidade:</p>
                <ul>
                    <li>Preservar suas preferências de interface (Tema Escuro / Claro, Efeitos Sonoros ativados/desativados).</li>
                    <li>Armazenar os dados cadastrais opcionais do promotor para evitar redigitação em rodadas sequenciais.</li>
                    <li>Manter o histórico local das últimas rodadas sorteadas, com autonomia total para expurgar todos os registros a qualquer momento pelo botão <em>"Limpar"</em>.</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-fingerprint"></i> 3. Auditoria & Hash Criptográfico SHA-256</h2>
                <p>Os selos de autenticidade gerados a cada rodada utilizam a <code>Web Cryptography API (SHA-256)</code> nativa do motor do navegador. Esses hashes funcionam como assinaturas matemáticas invioláveis e determinísticas para certificar a transparência do concurso perante participantes e órgãos fiscalizadores.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-user-secret"></i> 4. Amigo Secreto Criptografado (Zero-Knowledge)</h2>
                <p>No módulo Amigo Secreto, a resolução dos pares é calculada através de permutação estocástica (derangement) cifrada. Nem mesmo o operador que conduz o sorteio na tela tem acesso prévio aos pares formados, garantindo o sigilo individual absoluto.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-envelope"></i> 5. Encarregado de Dados (DPO) & Suporte</h2>
                <p>Para solicitações de auditoria técnica ou esclarecimentos sobre nossas diretrizes de privacidade:</p>
                <p>
                    <strong>E-mail Institucional:</strong> <a href="mailto:contato@4u.ia.br" class="text-link">contato@4u.ia.br</a><br>
                    <strong>Portal Oficial:</strong> <a href="https://4u.ia.br" target="_blank" class="text-link">4u.ia.br</a>
                </p>
            </section>
        </main>

        <footer class="legal-footer">
            <p>&copy; <?= date('Y') ?> Sorteador Pro Max &bull; <a href="https://4u.ia.br" target="_blank" class="text-link">4U.IA.BR</a> &bull; Código Aberto no <a href="https://github.com/4u-Labs" target="_blank" class="text-link">GitHub</a></p>
        </footer>
    </div>

    <script>
        // Particles initialization
        (function() {
            const container = document.getElementById('particles');
            if (!container) return;
            for (let i = 0; i < 20; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.left = Math.random() * 100 + '%';
                p.style.animationDelay = (Math.random() * 12) + 's';
                p.style.animationDuration = (8 + Math.random() * 8) + 's';
                p.style.width = (3 + Math.random() * 4) + 'px';
                p.style.height = p.style.width;
                container.appendChild(p);
            }
        })();
    </script>
</body>
</html>
