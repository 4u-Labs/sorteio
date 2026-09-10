# 🎲 Sorteador Pro Max 6.0

Plataforma completa, moderna e auditável de sorteios e geração aleatória desenvolvida pela **4U.IA.BR Labs**.

🔗 **Acesse online:** [https://4u.ia.br/app/sorteio/](https://4u.ia.br/app/sorteio/)

---

## 🚀 Funcionalidades Principais

- 🔢 **Sorteio de Números**: Intervalos personalizados, exclusão de dezenas, opção com ou sem repetição, ordenação automática.
- 👥 **Sorteio de Nomes e Equipes**: Suporte a listas extensas, importação direta de arquivos `.txt` e `.csv`, divisão automática e equilibrada em $N$ grupos/equipes.
- 🎡 **Roleta Interativa da Fortuna**: Roleta em Canvas 2D com física realista, desaceleração suave por atrito, efeitos sonoros sintetizados a cada setor e explosão de confetes no vencedor.
- 🔮 **Globo de Bingo 3D**: Simulação visual de globo giratório com extração de bolinhas 3D, suporte a padrões de 50, 60, 75 e 90 bolas, além de bandeja com histórico de pedras chamadas.
- 🎁 **Amigo Secreto Criptografado (Zero-Knowledge)**: Algoritmo de permutação circular perfeita (derangement) garantindo que ninguém tire a si mesmo. Cartões interativos estilo "raspadinha" e geração de links diretos para WhatsApp individual de cada participante sem que o organizador descubra os pares.
- 🎟️ **Sorteio Ponderado / Rifas**: Suporte a pesos e múltiplos bilhetes por participante (sintaxes como `Maria, 5`, `João [10]`, `Pedro (3)`).
- 🎲 **Dados Virtuais de RPG**: Rolagem simultânea de D4, D6, D8, D10, D12, D20 e D100 com suporte a modificadores (+/-) e painel estatístico em tempo real.
- 🪙 **Cara ou Coroa**: Moeda tridimensional com animação física realista de rotação e métricas percentuais de probabilidade.

---

## 🛡️ Auditoria, Transparência & Anti-Fraude

- **Selo Criptográfico SHA-256**: Cada resultado gera um hash de autenticidade único computado via Web Crypto API a partir dos dados do concurso, promotor, timestamp e sementes aleatórias.
- 📑 **Certificado Oficial de Sorteio (PDF A4)**: Layout homologado para impressão direta ou exportação em PDF A4 com dados do promotor, tabela completa de resultados e campo para assinaturas.
- 📱 **Gerador de Stories para Instagram (1080×1920)**: Renderização instantânea em Canvas em alta resolução com visual Cyber Glassmorphism e download direto em PNG.

---

## ⚙️ Tecnologias & Arquitetura

- **Frontend**: HTML5 Semântico, CSS3 Moderno (Glassmorphism, CSS Variables, Flexbox/Grid, Print Stylesheet), JavaScript ES6+ Modular.
- **Áudio**: Web Audio API pura (síntese sonora em tempo real sem arquivos externos de áudio).
- **Offline / PWA**: Web App Manifest, Service Worker com estratégia **Network-First** e invalidação automática de cache mobile.
- **Zero-Knowledge**: Todos os cálculos, listas e sorteios são processados 100% no cliente (memória RAM local), respeitando a privacidade (LGPD).

---

## 📄 Páginas Institucionais

- [Política de Privacidade](privacidade.php)
- [Termos de Uso](termos.php)
- [Suporte & FAQ](suporte.php)

---

## 👤 Créditos & Licença

Desenvolvido por **[4U.IA.BR Labs](https://github.com/4u-Labs)**.  
Distribuído sob a licença MIT.
