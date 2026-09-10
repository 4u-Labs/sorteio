<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso — Sorteador Pro Max</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?= $v ?>">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body class="legal-body">
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
            <p class="legal-subtitle">Condições gerais de utilização da plataforma Sorteador Pro Max</p>

            <section class="legal-section">
                <h2>1. Objeto e Finalidade</h2>
                <p>O <strong>Sorteador Pro Max</strong> é uma ferramenta utilitária online gratuita para geração de sorteios aleatórios, justos e transparentes (números, nomes, rifas, roleta de prêmios, amigo secreto, dados de RPG e cara ou coroa).</p>
            </section>

            <section class="legal-section">
                <h2>2. Imparcialidade e Algoritmos de Aleatoriedade</h2>
                <p>O sistema emprega o gerador de números pseudoaleatórios criptograficamente seguro do navegador (<code>crypto.getRandomValues</code>) para garantir a máxima entropia estatística, imprevisibilidade e impossibilidade de vício ou favorecimento de participantes.</p>
            </section>

            <section class="legal-section">
                <h2>3. Responsabilidade sobre Promoções e Campanhas</h2>
                <p>A 4U.IA.BR fornece a ferramenta tecnológica para operacionalização dos sorteios. A legalidade, autorizações regulatórias (como SECAP/Ministério da Fazenda no caso de promoções comerciais no Brasil), entrega de prêmios e regras estipuladas são de exclusiva responsabilidade do promotor ou organizador da campanha.</p>
            </section>

            <section class="legal-section">
                <h2>4. Gratuidade e Disponibilidade</h2>
                <p>A aplicação é fornecida gratuitamente sob o modelo "como está" (*as-is*), sem anúncios invasivos e sem cobrança por quantidade de participantes ou sorteios efetuados.</p>
            </section>
        </main>

        <footer class="legal-footer">
            <p>&copy; <?= date('Y') ?> Sorteador Pro Max &bull; 4U.IA.BR &bull; Código Aberto no <a href="https://github.com/4u-Labs" target="_blank" class="text-link">GitHub</a></p>
        </footer>
    </div>
</body>
</html>
