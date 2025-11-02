-- Criação do Banco de Dados (se necessário)
-- CREATE DATABASE seu_banco_poo2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE seu_banco_poo2;

-- Tabela de Usuários (para Login, compatível com POO2 se usar C#)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL, -- Para armazenar o password_hash
    nivel_acesso ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Categorias (para o relacionamento)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela Principal (Exemplo: Produtos, adaptável ao seu tema de POO2)
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERÇÃO DE DADOS INICIAIS
-- Senha inicial: '123456'
-- Use um script PHP para gerar o hash final mais seguro: password_hash('123456', PASSWORD_DEFAULT)
-- O hash abaixo é APENAS um exemplo (ele varia)
INSERT INTO usuarios (nome, email, senha_hash, nivel_acesso) VALUES
('Admin', 'admin@sistema.com', '$2y$10$w8T9V.I0XQ0Z3Y0X.A4X4uG2yV3zE2X1Z5yV2jM0Z2m5Q4X1J5T5', 'admin');

INSERT INTO categorias (nome) VALUES
('Eletrônicos'),
('Livros'),
('Roupas');

INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES
('Smartphone X', 'Top de linha com IA', 2500.00, 1),
('O Programador Pragmático', 'Livro essencial', 120.50, 2);