-- ============================================================
-- CRUD de Usuários — Programação Web
-- Aula 0 (Pré-aula): criação do banco e da tabela
-- Como usar: phpMyAdmin (http://localhost/phpmyadmin) > aba SQL > colar > Executar
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_crud_usuarios
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_crud_usuarios;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento DATE         NOT NULL,
    senha           VARCHAR(255) NOT NULL,   -- guarda o hash (password_hash), nunca a senha pura
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Dados de exemplo para o teste inicial.
-- Senha de ambos os usuários: 123456 (hash bcrypt gerado com password_hash)
INSERT INTO usuarios (nome, email, data_nascimento, senha) VALUES
('Pedro', 'contato@canalti.com.br',  '2000-05-01', '$2y$10$r.V7KBpynA7500ICS4D7DevkbNYtfOjzHh2Vd6fOPHQDvOnpPDn/O'),
('João',  'contato2@canalti.com.br', '2001-05-01', '$2y$10$ZeHa5Z7PYbcWpzlIJ5dJ7uqLSN1LzZIUcJO5jVyq4INLVHyoVNgnG');

-- Conferir se deu certo:
-- SELECT id, nome, email, data_nascimento FROM usuarios;
