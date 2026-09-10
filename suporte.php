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
    
    $msg_status = 'Mensagem enviada com sucesso! Responderemos o mais breve possível.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte & FAQ — Sorteador Pro Max</title>
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
            <h1><i class="fa-solid fa-headset"></i> Central de Suporte & Perguntas Frequentes</h1>
            <p class="legal-subtitle">Tire suas dúvidas técnicas sobre modalidades, auditoria e funcionamento</p>

            <?php if (!empty($msg_status)): ?>
                <div class="alert-success-box">
                    <i class="fa-solid fa-circle-check"></i> <?= $msg_status ?>
                </div>
            <?php endif; ?>

            <section class="legal-section">
                <h2>Perguntas Frequentes (FAQ)</h2>
                
                <div class="faq-item">
                    <h3><i class="fa-solid fa-circle-question"></i> Como comprovar a transparência do sorteio para os participantes?</h3>
                    <p>Ao concluir o sorteio, o sistema gera automaticamente um <strong>Hash SHA-256 de Autenticidade</strong> e habilita o botão <strong>"Gerar Certificado (PDF)"</strong> e <strong>"Exportar Card Stories"</strong>. Esses documentos contêm o ID exclusivo da rodada, horário exato, participantes e o hash criptográfico inviolável.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-circle-question"></i> Como funciona a Roleta Interativa (Spin Wheel)?</h3>
                    <p>Basta digitar ou colar a lista de opções na aba Roleta e clicar em "Girar Roleta". A roda utiliza simulação física de inércia e desaceleração progressiva, com som mecânico a cada setor transposto e confetes ao apontar a opção vencedora.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-circle-question"></i> É possível fazer sorteio com bilhetes de rifa ou chances extras?</h3>
                    <p>Sim! Na aba Nomes ou Rifas, você pode inserir participantes no formato <code>Nome, 5</code> ou colar uma lista com o mesmo nome repetido. O algoritmo contabilizará o peso proporcional de cada cota.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fa-solid fa-circle-question"></i> Meus dados ou contatos ficam salvos no servidor?</h3>
                    <p>Não! A plataforma opera sob a política de <strong>Retenção Zero</strong>. Todo o processamento é executado diretamente na memória RAM do seu navegador.</p>
                </div>
            </section>

            <section class="legal-section">
                <h2>Fale Conosco Diretamente</h2>
                <form method="POST" action="suporte.php" class="support-form">
                    <div class="form-row-2">
                        <div class="form-field">
                            <label for="nome">Seu Nome:</label>
                            <input type="text" id="nome" name="nome" required placeholder="Ex: Ana Clara" class="support-input">
                        </div>
                        <div class="form-field">
                            <label for="email">Seu E-mail:</label>
                            <input type="email" id="email" name="email" required placeholder="exemplo@email.com" class="support-input">
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="assunto">Assunto:</label>
                        <input type="text" id="assunto" name="assunto" required placeholder="Dúvida sobre modalidades ou sugestão" class="support-input">
                    </div>
                    <div class="form-field">
                        <label for="mensagem">Mensagem:</label>
                        <textarea id="mensagem" name="mensagem" rows="4" required placeholder="Descreva como podemos te ajudar..." class="support-input"></textarea>
                    </div>
                    <button type="submit" class="btn btn-glow"><i class="fa-solid fa-paper-plane"></i> Enviar Mensagem</button>
                </form>
            </section>
        </main>

        <footer class="legal-footer">
            <p>&copy; <?= date('Y') ?> Sorteador Pro Max &bull; 4U.IA.BR &bull; Código Aberto no <a href="https://github.com/4u-Labs" target="_blank" class="text-link">GitHub</a></p>
        </footer>
    </div>
</body>
</html>
