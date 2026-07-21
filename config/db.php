<?php

$host = "localhost";
$usuario = "u906671717_izv6m";
$senha = ".3l.e>09ZCvf";
$banco = "u906671717_EP325";

function obter_conexao(): mysqli
{
    global $host, $usuario, $senha, $banco;

    $conexao = new mysqli($host, $usuario, $senha, $banco);
    $conexao->set_charset("utf8mb4");

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    return $conexao;
}
