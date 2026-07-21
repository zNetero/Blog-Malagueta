<?php

function esc(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function gerar_previa(string $conteudo, int $limite = 220): string
{
    $texto = strip_tags($conteudo);
    $texto = preg_replace('/\s+/u', ' ', trim($texto));

    if (mb_strlen($texto) <= $limite) {
        return $texto;
    }

    return mb_substr($texto, 0, $limite) . '...';
}

function processar_upload_imagem(array $arquivo): ?string
{
    if (!isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Erro ao enviar a imagem. Tente novamente.");
    }

    if ($arquivo['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException("A imagem deve ter no máximo 5 MB.");
    }

    $tipos_permitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);

    if (!isset($tipos_permitidos[$mime])) {
        throw new RuntimeException("Formato de imagem não permitido. Use JPG, PNG, WEBP ou GIF.");
    }

    $pasta = __DIR__ . '/../assets/img/textos';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nome_arquivo = uniqid('texto_', true) . '.' . $tipos_permitidos[$mime];
    $caminho_completo = $pasta . '/' . $nome_arquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
        throw new RuntimeException("Não foi possível salvar a imagem enviada.");
    }

    return 'assets/img/textos/' . $nome_arquivo;
}

function normalizar_imagem(?string $imagem): string
{
    return $imagem ?? '';
}
