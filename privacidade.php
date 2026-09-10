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
    <title>Política de Privacidade & LGPD — Sorteador Pro Max</title>
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
            <h1><i class="fa-solid fa-shield-halved"></i> Política de Privacidade & LGPD</h1>
            <p class="legal-subtitle">Em total conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018)</p>

            <section class="legal-section">
                <h2>1. Arquitetura de Retenção Zero (Privacy by Design)</h2>
                <p>O <strong>Sorteador Pro Max (4U Sorteio)</strong> foi projetado com o princípio fundamental de <strong>Retenção Zero</strong>. Todas as listas de participantes, números, rifas, bilhetes e resultados sorteados são processados <strong>exclusivamente na memória RAM do seu navegador web</strong>.</p>
                <p>Nenhuma lista digitada, colada ou importada por arquivo é transmitida para servidores externos, bancos de dados em nuvem ou terceiros.</p>
            </section>

            <section class="legal-section">
                <h2>2. Armazenamento Local (LocalStorage)</h2>
                <p>O aplicativo utiliza o <code>localStorage</code> do seu navegador estritamente para sua conveniência pessoal:</p>
                <ul>
                    <li>Salvar suas preferências de interface (Modo Escuro / Claro, Sons ativados/desativados).</li>
                    <li>Guardar os dados opcionais do promotor para evitar redigitação.</li>
                    <li>Manter o histórico local das últimas rodadas de sorteio (com opção de limpar a qualquer momento pelo botão <em>"Limpar Histórico"</em>).</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2>3. Auditoria & Hash Criptográfico SHA-256</h2>
                <p>Os hashes de autenticidade gerados em cada sorteio utilizam a API nativa de criptografia do navegador (<code>Web Cryptography API - SHA-256</code>). Eles funcionam como uma assinatura matemática irreversível para comprovar a lisura e imparcialidade do resultado perante seu público.</p>
            </section>

            <section class="legal-section">
                <h2>4. Amigo Secreto Criptografado</h2>
                <p>No modo Amigo Secreto, a distribuição dos pares é cifrada matematicamente no cliente. Nem mesmo quem opera a tela tem acesso aos pares sorteados, garantindo o sigilo total de cada revelação.</p>
            </section>

            <section class="legal-section">
                <h2>5. Contato do Encarregado de Dados (DPO)</h2>
                <p>Para dúvidas sobre privacidade, segurança ou auditoria técnica:</p>
                <p><strong>E-mail institucional:</strong> <a href="mailto:contato@4u.ia.br" class="text-link">contato@4u.ia.br</a><br>
                <strong>Portal Oficial:</strong> <a href="https://4u.ia.br" target="_blank" class="text-link">4u.ia.br</a></p>
            </section>
        </main>

        <footer class="legal-footer">
            <p>&copy; <?= date('Y') ?> Sorteador Pro Max &bull; 4U.IA.BR &bull; Código Aberto no <a href="https://github.com/4u-Labs" target="_blank" class="text-link">GitHub</a></p>
        </footer>
    </div>
</body>
</html>
