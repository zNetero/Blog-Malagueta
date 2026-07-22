<?php

require_once __DIR__ . '/funcoes.php';

if (!isset($titulo_pagina)) {
    $titulo_pagina = 'Malagueta do Deserto';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($titulo_pagina); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css?v=2.0">
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
