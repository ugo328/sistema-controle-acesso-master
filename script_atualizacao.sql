DROP DATABASE IF EXISTS controle_de_acesso;
CREATE DATABASE IF NOT EXISTS controle_de_acesso;
USE controle_de_acesso;

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    cpf CHAR(14) NOT NULL UNIQUE,
    senha VARCHAR(32) NOT NULL,
    telefone VARCHAR(20) DEFAULT '(99) 9999-99999',
    data_nascimento DATE DEFAULT '1970-01-01',
    genero VARCHAR(30) DEFAULT 'Indefinido',
    imagem_usuario VARCHAR(80) DEFAULT 'user.png',
    nivel_acesso VARCHAR(13) NOT NULL
);

CREATE TABLE moradores (
    id_morador INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    cpf CHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    apartamento VARCHAR(5) NOT NULL,
    bloco CHAR(1) NOT NULL
);

CREATE TABLE prestadores_servicos_cadastrados (
    id_prestador_servico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    cpf CHAR(14) UNIQUE NOT NULL,
    rg VARCHAR(14) NOT NULL,
    uf VARCHAR(2) NOT NULL
);

CREATE TABLE prestadores_servicos (
    id_prestador_servico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    cpf_rg VARCHAR(14) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    apartamento VARCHAR(5) NOT NULL,
    bloco CHAR(1) NOT NULL,
    data_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_saida DATETIME,
    fk_id_prestador_servico INT NOT NULL,
    FOREIGN KEY(fk_id_prestador_servico) REFERENCES prestadores_servicos_cadastrados(id_prestador_servico) ON DELETE CASCADE
);

CREATE TABLE visitantes_cadastrados (
    id_visitante INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    cpf CHAR(14) UNIQUE NOT NULL,
    rg VARCHAR(14) NOT NULL,
    uf VARCHAR(2) NOT NULL
);

CREATE TABLE visitantes (
    id_visitante INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL,
    cpf_rg VARCHAR(14) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    apartamento VARCHAR(5) NOT NULL,
    bloco CHAR(1) NOT NULL,
    data_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_saida DATETIME,
    fk_id_visitante INT NOT NULL,
    FOREIGN KEY(fk_id_visitante) REFERENCES visitantes_cadastrados(id_visitante) ON DELETE CASCADE
);

CREATE TABLE encomendas (
    id_encomenda INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    empresa VARCHAR(60) NOT NULL,
    apartamento VARCHAR(5) NOT NULL,
    bloco CHAR(1) NOT NULL,
    data_entrega DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_entrega VARCHAR(30) NOT NULL DEFAULT 'Processando'
);

CREATE TABLE eventos (
    id_evento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    cpf CHAR(14) NOT NULL,
    titulo_evento VARCHAR(60) NOT NULL,
    inicio_evento DATETIME NOT NULL,
    fim_evento DATETIME NOT NULL,
    cor VARCHAR(10) NOT NULL,
    status_pagamento VARCHAR(20) NOT NULL DEFAULT 'Pendente',
    fk_id_morador INT NOT NULL,
    FOREIGN KEY(fk_id_morador) REFERENCES moradores(id_morador) ON DELETE CASCADE
);
