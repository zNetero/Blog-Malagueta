<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    die("Acesso negado. Você precisa estar logado para publicar.");
}

$host = "localhost"; 
$usuario = "u906671717_izv6m";
$senha = ".3l.e>09ZCvf"; 
$banco = "u906671717_EP325";

// Cria a conexão
$conexao = new mysqli($host, $usuario, $senha, $banco);


if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $conexao->real_escape_string($_POST['titulo']);
    $conteudo = $conexao->real_escape_string($_POST['conteudo']);

    $sql = "INSERT INTO textos_blog (titulo, conteudo) VALUES ('$titulo', '$conteudo')";

    if ($conexao->query($sql) === TRUE) {
        header("Location: painel-autor.html?sucesso=1");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conexao->error;
    }
}

$conexao->close();
?>