<?php
session_start();


$senha_correta = "aracati2026"; 
$erro = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['senha_acesso'])) {
    if ($_POST['senha_acesso'] === $senha_correta) {
        $_SESSION['logado'] = true;
        header("Location: painel-autor.php"); 
        exit();
    } else {
        $erro = "Senha incorreta. Tente novamente.";
    }
}


if (isset($_GET['sair'])) {
    session_destroy();
    header("Location: painel-autor.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Escrita - Malagueta do Deserto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
</head>
<body style="background-color: #f3efe9;">

    <header style="justify-content: center; position: relative;">
        <div class="logo-header" style="font-size: 1.5rem;">ÁREA DO ESCRITOR</div>
        <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
            <a href="painel-autor.php?sair=true" style="position: absolute; right: 60px; color: #782c23; text-decoration: none; font-weight: bold; font-family: var(--font-sans);">Sair (Encerrar Sessão)</a>
        <?php endif; ?>
    </header>

    <main class="painel-page">
        
        <?php if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true): ?>
            <div class="painel-container" style="max-width: 500px; margin-top: 50px;">
                <h1>Acesso Restrito</h1>
                <p>Digite sua senha de escritor para acessar o painel.</p>
                
                <?php if ($erro): ?>
                    <div style="color: #d9534f; background-color: #fdf7f7; padding: 15px; border-radius: 8px; border: 1px solid #d9534f; margin-bottom: 20px; text-align: center; font-weight: bold;">
                        <?php echo $erro; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-escrita">
                    <div class="form-group-large">
                        <label for="senha_acesso" style="text-align: center;">Senha</label>
                        <input type="password" id="senha_acesso" name="senha_acesso" style="text-align: center; letter-spacing: 5px;" required>
                    </div>
                    <button type="submit" class="btn-publicar">ENTRAR NO PAINEL</button>
                </form>
            </div>

        <?php else: ?>
            <div class="painel-container">
                <h1>Escrever Novo Texto</h1>
                <p>Preencha o título e escreva sua história abaixo. Quando terminar, clique no botão verde.</p>
                
                <?php if (isset($_GET['sucesso'])): ?>
                    <div style="color: #3b5e3b; background-color: #f0f9f0; padding: 20px; border-radius: 8px; border: 1px solid #3b5e3b; margin-bottom: 30px; text-align: center; font-size: 1.2rem; font-weight: bold;">
                        ✓ Texto publicado com sucesso!
                    </div>
                <?php endif; ?>

                <form action="publicar.php" method="POST" class="form-escrita">
                    <div class="form-group-large">
                        <label for="titulo">Título do Texto</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Ex: Lembranças de Aracati" required>
                    </div>
                    
                    <div class="form-group-large">
                        <label for="conteudo">Sua História</label>
                        <textarea id="conteudo" name="conteudo" rows="15" placeholder="Comece a escrever aqui..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-publicar">PUBLICAR TEXTO NO SITE</button>
                </form>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>