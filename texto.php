<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: textos.php");
    exit();
}

$host = "localhost";
$usuario = "u906671717_izv6m";
$senha = ".3l.e>09ZCvf"; // Substitua pela sua senha
$banco = "u906671717_EP325";

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Falha na conexão.");
}

$stmt = $conexao->prepare("SELECT titulo, conteudo, imagem FROM textos_blog WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$texto = $resultado->fetch_assoc();
$stmt->close();
$conexao->close();

if (!$texto) {
    header("Location: textos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($texto['titulo']); ?> - Malagueta do Deserto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.4">
</head>
<body>

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

    <main class="texto-single-page">
        <article class="texto-single-container">
            <a href="textos.php" class="texto-voltar">&larr; Voltar aos textos</a>

            <?php if (!empty($texto['imagem'])): ?>
                <figure class="texto-single-image">
                    <!-- O caminho da imagem será onde a Hostinger salvar o upload -->
                    <img src="<?php echo htmlspecialchars($texto['imagem']); ?>" alt="Imagem da publicação">
                </figure>
            <?php endif; ?>

            <header class="texto-single-header">
                <h1><?php echo htmlspecialchars($texto['titulo']); ?></h1>
            </header>

            <div class="texto-single-content">
                <?php echo nl2br(htmlspecialchars($texto['conteudo'])); ?>
            </div>
        </article>
    </main>

    <footer>
        <div class="footer-bottom">
            &copy; 2026 Malagueta do Deserto - José Nilton Fernandes. Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>