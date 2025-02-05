#DROP DATABASE monetizze;
CREATE DATABASE IF NOT EXISTS monetizze;
USE monetizze;

CREATE TABLE IF NOT EXISTS tb_tripulantes (
    id_tripulante INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tb_bilhetes (
    id_bilhete INT AUTO_INCREMENT PRIMARY KEY,
    id_tripulante INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tripulante) REFERENCES tb_tripulantes(id_tripulante) 
        ON DELETE CASCADE
);  

CREATE TABLE tb_bilhetes_dezenas (
    id_bilhete INT NOT NULL,
    dezena INT NOT NULL,
    PRIMARY KEY (id_bilhete, dezena),
    FOREIGN KEY (id_bilhete) REFERENCES tb_bilhetes(id_bilhete)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE tb_bilhetes_premiados (
    id_bilhete_premiado INT AUTO_INCREMENT PRIMARY KEY,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);  

CREATE TABLE tb_bilhetes_premiados_dezenas (
    id_bilhete_premiado INT NOT NULL,
    dezena INT NOT NULL,
    PRIMARY KEY (id_bilhete_premiado, dezena),
    FOREIGN KEY (id_bilhete_premiado) REFERENCES tb_bilhetes_premiados(id_bilhete_premiado) ON DELETE CASCADE
);

INSERT INTO tb_tripulantes (nome, email) VALUES ('Tripulante 1', 't3@email.com');