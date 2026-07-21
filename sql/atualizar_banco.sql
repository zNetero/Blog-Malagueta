-- Execute este script no phpMyAdmin da Hostinger (banco u906671717_EP325)
-- antes de publicar textos com imagem.

ALTER TABLE textos_blog
    ADD COLUMN imagem VARCHAR(255) DEFAULT NULL AFTER conteudo;
