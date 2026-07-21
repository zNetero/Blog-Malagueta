<?php


require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/includes/funcoes.php';


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


if ($id <= 0) {

    header("Location: textos.php");

    exit();

}


$conexao = obter_conexao();

$stmt = $conexao->prepare("SELECT id, titulo, conteudo, imagem FROM textos_blog WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$texto = null;
/* Compatibilidade: evitar uso de get_result() que requer mysqlnd em alguns hosts */
if (method_exists($stmt, 'get_result')) {
    $resultado = $stmt->get_result();
    $texto = $resultado ? $resultado->fetch_assoc() : null;
} else {
    $stmt->bind_result($col_id, $col_titulo, $col_conteudo, $col_imagem);
    if ($stmt->fetch()) {
        $texto = [
            'id' => $col_id,
            'titulo' => $col_titulo,
            'conteudo' => $col_conteudo,
            'imagem' => $col_imagem,
        ];
    }
}

$stmt->close();
$conexao->close();


if (!$texto) {

    header("Location: textos.php");

    exit();

}


$titulo_pagina = $texto['titulo'] . ' - Malagueta do Deserto';

require_once __DIR__ . '/includes/header.php';

?>


    <main class="texto-single-page">

        <article class="texto-single-container">

            <a href="textos.php" class="texto-voltar">&larr; Voltar aos textos</a>


            <?php if (!empty($texto['imagem'])): ?>

                <figure class="texto-single-image">

                    <img src="<?php echo esc($texto['imagem']); ?>" alt="<?php echo esc($texto['titulo']); ?>">

                </figure>

            <?php endif; ?>


            <header class="texto-single-header">

                <h1><?php echo esc($texto['titulo']); ?></h1>

            </header>


            <div class="texto-single-content">

                <?php echo nl2br(esc($texto['conteudo'])); ?>

            </div>

        </article>

    </main>


<?php require_once __DIR__ . '/includes/footer.php'; ?> 