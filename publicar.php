<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    die("Acesso negado. Você precisa estar logado para publicar.");
}

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: painel-autor.php");
    exit();
}

$conexao = obter_conexao();

$titulo = trim($_POST['titulo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');

if ($titulo === '' || $conteudo === '') {
    header("Location: painel-autor.php?erro=campos");
    exit();
}

try {
    $imagem = normalizar_imagem(processar_upload_imagem($_FILES['imagem'] ?? []));
} catch (RuntimeException $e) {
    header("Location: painel-autor.php?erro=" . urlencode($e->getMessage()));
    exit();
}

$stmt = $conexao->prepare("INSERT INTO textos_blog (titulo, conteudo, imagem) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $titulo, $conteudo, $imagem);

if ($stmt->execute()) {
    $stmt->close();
    $conexao->close();
    header("Location: painel-autor.php?sucesso=1");
    exit();
}

$erro = $stmt->error;
$stmt->close();
$conexao->close();

header("Location: painel-autor.php?erro=" . urlencode("Erro ao publicar: " . $erro));
exit();
