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
    <title>Termos de Uso — Sorteador Pro Max</title>
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
            <h1><i class="fa-solid fa-scale-balanced"></i> Termos de Uso</h1>
            <p class="legal-subtitle">Diretrizes gerais de conformidade, imparcialidade e utilização do sistema</p>

            <section class="legal-section">
                <h2><i class="fa-solid fa-bullseye"></i> 1. Objeto e Finalidade</h2>
                <p>O <strong>Sorteador Pro Max</strong> é uma plataforma de utilidade pública desenvolvida para gerar sorteios com máxima imparcialidade, transparência e segurança matemática através de múltiplos módulos: números, nomes, roleta interativa, bingo 3D, amigo secreto, rifas ponderadas, dados de RPG e cara ou coroa.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-dice"></i> 2. Imparcialidade e Aleatoriedade Criptográfica</h2>
                <p>O motor algorítmico utiliza a interface nativa <code>crypto.getRandomValues</code>, garantindo entropia uniforme e ausência estatística de qualquer favorecimento, vício de seleção ou previsibilidade de resultados.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-file-contract"></i> 3. Responsabilidade sobre Promoções e Campanhas</h2>
                <p>A <strong>4U.IA.BR</strong> fornece a infraestrutura tecnológica para geração e auditoria dos sorteios. Aspectos jurídicos, conformidades regulatórias com a SECAP/Ministério da Fazenda (para promoções comerciais com distribuição gratuita de prêmios), recolhimento de tributos e cumprimento de entrega dos prêmios cabem única e exclusivamente aos respectivos promotores.</p>
            </section>

            <section class="legal-section">
                <h2><i class="fa-solid fa-hand-holding-heart"></i> 4. Gratuidade e Disponibilidade Contínua</h2>
                <p>O serviço é ofertado de maneira gratuita e aberta (*as-is*), sem restrições de quantidade de participantes, sem anúncios intrusivos e sem comercialização de dados pessoais de usuários.</p>
            </section>
        </main>

        <footer class="legal-footer">
            <p>&copy; <?= date('Y') ?> Sorteador Pro Max &bull; <a href="https://4u.ia.br" target="_blank" class="text-link">4U.IA.BR</a> &bull; Código Aberto no <a href="https://github.com/4u-Labs" target="_blank" class="text-link">GitHub</a></p>
        </footer>
    </div>

    <script>
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
