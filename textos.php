<?php
// Configurações do Banco de Dados
$host = "localhost";
$usuario = "u906671717_izv6m";
$senha = ".3l.e>09ZCvf"; // Substitua pela sua senha
$banco = "u906671717_EP325";

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Busca os textos do mais recente para o mais antigo
$resultado = $conexao->query("SELECT id, titulo, conteudo FROM textos_blog ORDER BY id DESC");
$textos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
$conexao->close();

// Função simples para gerar a prévia do texto
function gerar_previa($texto, $limite = 200) {
    if (mb_strlen($texto) > $limite) {
        return mb_substr($texto, 0, $limite) . '...';
    }
    return $texto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textos - Malagueta do Deserto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.4">
</head>
<body>

    <!-- CABEÇALHO PADRÃO -->
    <header>
        <div class="logo-header">MALAGUETA DO DESERTO</div>
        <nav>
            <a href="index.html">Início</a>
            <a href="textos.php">Textos</a>
            <a href="autor.html">Autor</a>
            <a href="livros.html">Livros</a>
            <a href="contato.html">Contato</a>
            <a href="arquivo.html">Arquivo</a>
        </nav>
    </header>

    <main class="textos-page">
        <div class="textos-container">
            <header class="textos-header">
                <h1>Textos Publicados</h1>
                <p>Crônicas, memórias e histórias do sertão cearense.</p>
            </header>

            <?php if (empty($textos)): ?>
                <div class="textos-vazio">
                    <p>Nenhum texto publicado ainda. Em breve, novas histórias aparecerão aqui.</p>
                </div>
            <?php else: ?>
                <div class="textos-grid">
                    <?php foreach ($textos as $texto): ?>
                        <article class="texto-card">
                            <div class="texto-card-content">
                                <h2><?php echo htmlspecialchars($texto['titulo']); ?></h2>
                                <p class="texto-card-preview"><?php echo nl2br(htmlspecialchars(gerar_previa($texto['conteudo']))); ?></p>
                            </div>
                            <div class="texto-card-actions">
                                <a href="texto.php?id=<?php echo (int) $texto['id']; ?>" class="btn-ler-mais">Ler texto completo</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- RODAPÉ PADRÃO -->
    <footer>
        <div class="footer-bottom">
            &copy; 2026 Malagueta do Deserto - José Nilton Fernandes. Todos os direitos reservados.
            <div style="margin-top: 15px;">
                <a href="painel-autor.php" style="color: #555; text-decoration: none; font-size: 0.8rem; transition: color 0.3s;" onmouseover="this.style.color='#f9f9f9'" onmouseout="this.style.color='#555'">
                    <i class="fa-solid fa-lock" style="font-size: 0.7rem;"></i> Área Restrita
                </a>
            </div>
        </div>
    </footer>

</body>
</html>