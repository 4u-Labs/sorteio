<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
$msg_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome']) && !empty($_POST['email'])) {
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $assunto = htmlspecialchars(trim($_POST['assunto'] ?? 'Suporte Sorteador Pro Max'));
    $mensagem = htmlspecialchars(trim($_POST['mensagem']));
    
    $log_data = [
        'timestamp' => date('c'),
        'app' => 'sorteio',
        'nome' => $nome,
        'email' => $email,
        'assunto' => $assunto,
        'mensagem' => $mensagem,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconhecido'
    ];
    
    $log_dir = __DIR__ . '/uploads';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    @file_put_contents($log_dir . '/messages_log.json', json_encode($log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    
    $to = 'contato@4u.ia.br';
    $headers = "From: contato@4u.ia.br\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    $body = "Novo contato via Suporte Sorteador Pro Max:\n\nNome: {$nome}\nE-mail: {$email}\nAssunto: {$assunto}\nMensagem:\n{$mensagem}\n";
    @mail($to, "Sorteador Pro - " . $assunto, $body, $headers);
    
    $msg_status = 'Mensagem enviada com sucesso! Nossa equipe responderá em breve.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Suporte & FAQ — Sorteador Pro Max</title>
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
            <h1><i class="fa-solid fa-headset"></i> Central de Suporte & FAQ</h1>
            <p class="legal-subtitle">Perguntas frequentes sobre modalidades, auditoria e canal direto com a equipe</p>

            <?php if (!empty($msg_status)): ?>
                <div class="alert-success-box">
                    <i class="fa-solid fa-circle-check" style="font-size:1.2rem;"></i> <?= $msg_status ?>
                </div>
            <?php endif; ?>

            <section class="legal-section">
                <h2><i class="fa-solid fa-circle-question"></i> Perguntas Frequentes</h2>
                
                <div class="faq-item">
                    <h3><i class="fa-solid fa-shield-halved"></i> Como comprovar a transparência e lisura do sorteio?</h3>
                    <p>Ao concluir o sorteio, o sistema calcula em tempo real o <strong>Hash SHA-256 de Autenticidade</strong> e disponibiliza os botões <strong>"Certificado A4"</strong> e <strong>"Instagram Story"</strong>. Esses documentos contêm o identificador irreversível da rodada, data e hora precisas, lista completa de participantes e o selo de validação criptográfica.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-dharmachakra"></i> Como funciona a Roleta Interativa da Fortuna?</h3>
                    <p>Insira ou personalize suas opções na aba Roleta e clique em "Girar Roleta". A roleta é simulada fisicamente em Canvas 2D de alta definição com desaceleração angular contínua por atrito, cliques de áudio por setor e confetes ao atingir a casa vencedora.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-ticket"></i> Como realizar sorteios com cotas ou pesos diferentes por pessoa?</h3>
                    <p>Na aba <strong>Rifas/Pesos</strong>, informe os participantes no formato <code>Nome, 5</code> ou <code>Nome [10]</code>. O algoritmo aloca a quantidade correspondente de bilhetes no globo virtual proporcionalmente.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-user-lock"></i> Meus dados ou contatos ficam retidos no servidor?</h3>
                    <p>Não! A plataforma opera sob o protocolo de <strong>Retenção Zero</strong>. Todo o processamento de nomes, números e cálculos é executado localmente na memória RAM do seu dispositivo.</p>
                </div>
            </section>

            <section class="legal-section" style="margin-bottom:0;">
                <h2><i class="fa-solid fa-paper-plane"></i> Fale Conosco Diretamente</h2>
                <p style="color:var(--subtitle-color); font-size:0.9rem; margin-bottom:14px;">Tem dúvidas técnicas, sugestões de novos recursos ou precisa de suporte comercial? Preencha o formulário abaixo:</p>
                
                <form method="POST" action="suporte.php" class="support-form">
                    <div class="form-row-2">
                        <div class="form-field">
                            <label for="nome"><i class="fa-solid fa-user"></i> Seu Nome:</label>
                            <input type="text" id="nome" name="nome" required placeholder="Ex: Ana Clara" class="support-input">
                        </div>
                        <div class="form-field">
                            <label for="email"><i class="fa-solid fa-envelope"></i> Seu E-mail:</label>
                            <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com" class="support-input">
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="assunto"><i class="fa-solid fa-tag"></i> Assunto:</label>
                        <input type="text" id="assunto" name="assunto" required placeholder="Ex: Dúvida sobre auditoria de concurso" class="support-input">
                    </div>
                    <div class="form-field">
                        <label for="mensagem"><i class="fa-solid fa-message"></i> Mensagem detalhada:</label>
                        <textarea id="mensagem" name="mensagem" rows="4" required placeholder="Descreva sua solicitação com detalhes..." class="support-input"></textarea>
                    </div>
                    <button type="submit" class="btn-glow">
                        <i class="fa-solid fa-paper-plane"></i> Enviar Mensagem
                    </button>
                </form>
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
