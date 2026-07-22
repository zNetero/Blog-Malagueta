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
    <link rel="stylesheet" href="assets/css/style.css?v=2.0">
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

                <?php if (isset($_GET['erro'])): ?>
                    <div style="color: #d9534f; background-color: #fdf7f7; padding: 20px; border-radius: 8px; border: 1px solid #d9534f; margin-bottom: 30px; text-align: center; font-weight: bold;">
                        <?php echo htmlspecialchars($_GET['erro'] === 'campos' ? 'Preencha o título e o conteúdo do texto.' : $_GET['erro'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <form action="publicar.php" method="POST" enctype="multipart/form-data" class="form-escrita">
                    <div class="form-group-large">
                        <label for="titulo">Título do Texto</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Ex: Lembranças de Aracati" required>
                    </div>

                    <div class="form-group-large">
                        <label for="imagem">Imagem do Texto (opcional)</label>
                        <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp,image/gif" class="input-arquivo">
                        <small class="form-hint">Esta imagem aparecerá no topo da página de leitura. Formatos: JPG, PNG, WEBP ou GIF (máx. 5 MB).</small>
                    </div>
                    
                    <div class="form-group-large">
                        <label for="conteudo">Sua História</label>
                        <textarea id="conteudo" name="conteudo" rows="15" placeholder="Comece a escrever aqui..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-publicar">PUBLICAR TEXTO NO SITE</button>
                </form>

                <?php
                    // Lista de textos publicados pelo autor
                    require_once __DIR__ . '/config/db.php';
                    require_once __DIR__ . '/includes/funcoes.php';

                    $conexao = obter_conexao();

                    // Detecta coluna de data se existir (created_at ou data)
                    $date_col = null;
                    $check = $conexao->query("SHOW COLUMNS FROM textos_blog LIKE 'created_at'");
                    if ($check && $check->num_rows) {
                        $date_col = 'created_at';
                    } else {
                        $check2 = $conexao->query("SHOW COLUMNS FROM textos_blog LIKE 'data'");
                        if ($check2 && $check2->num_rows) {
                            $date_col = 'data';
                        }
                    }

                    if ($date_col) {
                        $sql = "SELECT id, titulo, " . $date_col . " AS data FROM textos_blog ORDER BY id DESC";
                        $stmt = $conexao->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $stmt = $conexao->prepare("SELECT id, titulo FROM textos_blog ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();
                    }
                ?>

                <hr style="margin:30px 0;border:none;border-top:1px solid rgba(0,0,0,0.06);">
                <h2>Meus Textos Publicados</h2>

                <?php if ($result && $result->num_rows > 0): ?>
                    <table style="width:100%;border-collapse:collapse;margin-top:12px;">
                        <thead>
                            <tr style="text-align:left;border-bottom:1px solid #eee;">
                                <th style="padding:10px 8px;width:60px;">ID</th>
                                <th style="padding:10px 8px;">Título</th>
                                <th style="padding:10px 8px;width:180px;">Data</th>
                                <th style="padding:10px 8px;width:120px;text-align:right;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr style="border-bottom:1px solid #fafafa;">
                                    <td style="padding:10px 8px;vertical-align:middle;"><?php echo (int) $row['id']; ?></td>
                                    <td style="padding:10px 8px;vertical-align:middle;"><?php echo esc($row['titulo']); ?></td>
                                    <td style="padding:10px 8px;vertical-align:middle;"><?php echo isset($row['data']) ? esc($row['data']) : '—'; ?></td>
                                    <td style="padding:10px 8px;vertical-align:middle;text-align:right;">
                                        <form method="post" action="deletar.php" onsubmit="return confirm('Confirma exclusão deste texto?');" style="display:inline-block;margin:0;">
                                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                            <button type="submit" class="btn-delete">Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#666;margin-top:12px;">Nenhum texto publicado ainda.</p>
                <?php endif; ?>

                <?php
                    if (isset($stmt) && $stmt) {
                        $stmt->close();
                    }
                    $conexao->close();
                ?>

            </div>
        <?php endif; ?>

    </main>

</body>
</html>