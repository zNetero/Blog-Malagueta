<?php

require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/includes/funcoes.php';


$conexao = obter_conexao();

$resultado = $conexao->query("SELECT id, titulo, conteudo FROM textos_blog ORDER BY id DESC");

$textos = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $textos[] = $row;
    }
    $resultado->free();
}

$conexao->close();


$titulo_pagina = 'Textos - Malagueta do Deserto';

require_once __DIR__ . '/includes/header.php';

?>


    <main class="textos-page">

        <div class="textos-container">

            <header class="textos-header">

                <h1>Textos</h1>

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

                            <div class="texto-card-title">

                                <h2><?php echo esc($texto['titulo']); ?></h2>

                            </div>

                            <p class="texto-card-preview"><?php echo esc(gerar_previa($texto['conteudo'])); ?></p>

                            <div class="texto-card-actions">

                                <a href="texto.php?id=<?php echo (int) $texto['id']; ?>" class="btn-ler-mais">Ler mais</a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

    </main>


<?php require_once __DIR__ . '/includes/footer.php'; ?> 