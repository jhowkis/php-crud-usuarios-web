-- Criar a base de dados se não existir
CREATE DATABASE IF NOT EXISTS db_crud_usuarios;
USE db_crud_usuarios;

-- Criar a tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de dados para teste inicial
INSERT INTO usuarios (nome, email, data_nascimento, senha) VALUES
('Pedro', 'contato@canalti.com.br', '2000-05-01', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1a9g1ZJ3.'),
('João', 'contato2@canalti.com.br', '2001-05-01', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1a9g1ZJ3.');