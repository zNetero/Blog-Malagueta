<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/funcoes.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: painel-autor.php?erro=nao_autorizado');
    exit();
}

$id = 0;
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

if ($id <= 0) {
    header('Location: painel-autor.php?erro=id_invalido');
    exit();
}

$conexao = obter_conexao();

// Recupera imagem associada, usando compatibilidade com hosts sem mysqlnd
$imagem = null;
$stmt = $conexao->prepare("SELECT imagem FROM textos_blog WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if (method_exists($stmt, 'get_result')) {
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
} else {
    $stmt->bind_result($col_imagem);
    $row = null;
    if ($stmt->fetch()) {
        $row = ['imagem' => $col_imagem];
    }
}
$stmt->close();

if ($row && !empty($row['imagem'])) {
    $imagem = $row['imagem'];
}

// Apaga o registro
$del = $conexao->prepare("DELETE FROM textos_blog WHERE id = ?");
$del->bind_param("i", $id);
$ok = $del->execute();
$error = $del->error;
$del->close();

// Se havia imagem, tenta remover do disco
if ($ok && $imagem) {
    $caminho = __DIR__ . '/' . $imagem; // deve ser algo como assets/img/textos/xxx
    if (file_exists($caminho) && is_file($caminho)) {
        @unlink($caminho);
    }
}

$conexao->close();

if ($ok) {
    header('Location: painel-autor.php?sucesso=apagado');
    exit();
}

header('Location: painel-autor.php?erro=' . urlencode('Erro ao apagar: ' . $error));
exit();
