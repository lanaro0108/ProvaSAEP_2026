-- Script de criação e alterações do banco de dados

-- 1. Criação do banco de dados

-- 2. Criação da tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL,
    senha_hash VARCHAR(128) NOT NULL,
    perfil VARCHAR(128) NOT NULL CHECK (perfil IN ('admin', 'veterinario', 'recepcionista')),
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. Criação de tabela tutores
CREATE TABLE IF NOT EXISTS tutores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    cpf_criptografado VARCHAR(128) NOT NULL,
    cpf_hash CHAR(64) NOT NULL UNIQUE,
    telefone VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 4. Criação da tabela de pets
CREATE TABLE IF NOT EXISTS pets (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id) ON DELETE CASCADE,
    nome VARCHAR(128) NOT NULL,
    especie VARCHAR(128) NOT NULL,
    raca VARCHAR(128) NOT NULL,
    data_nascimento DATE NOT NULL,
    cadastrado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pets_id_tutor_unique UNIQUE (id, tutor_id)
);

-- 5. Criação da tabela de agendamentos - ordenada
CREATE TABLE IF NOT EXISTS agendamentos (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id),
    pet_id INTEGER NOT NULL REFERENCES pets(id),
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id),
    data_agendamento DATE NOT NULL,
    hora_agendamento TIME NOT NULL,
    servico VARCHAR(128) NOT NULL,
    status VARCHAR(128) NOT NULL DEFAULT 'AGENDADO' CHECK (status IN ('AGENDADO', 'CONCLUIDO', 'CANCELADO')),
    observacao TEXT,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT agendamentos_pet_tutor_fk FOREIGN KEY (pet_id, tutor_id) REFERENCES pets(id, tutor_id)
);

-- Alterações iniciais (3 registros por tabela)

-- Usuários: Senha padrão para todos: '123456'
-- Senha de demonstracao para os usuarios abaixo: 123456 (hash bcrypt).

INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES
('Dra. Camila Alencar', 'camila.vet@sisvet.com', '$2y$12$vIkXeCv8cOXjJ5QFpbRSxODd27KOZIYEad6xXhBVHVAd8HJLxS21e', 'veterinario'),
('Lucas Fernandes', 'lucas.recepcao@sisvet.com', '$2y$12$vIkXeCv8cOXjJ5QFpbRSxODd27KOZIYEad6xXhBVHVAd8HJLxS21e', 'recepcionista'),
('Mariana Rocha', 'gestao@sisvet.com', '$2y$12$vIkXeCv8cOXjJ5QFpbRSxODd27KOZIYEad6xXhBVHVAd8HJLxS21e', 'admin');


-- Tutores: CPFs sintéticos armazenados criptografados em Base64 (OpenSSL AES-256-CBC).
INSERT INTO tutores (id, nome, cpf_criptografado, cpf_hash, telefone, email) VALUES
(1, 'Carlos Eduardo Silva', 'DO4MNjpa36yzQoBfshuHSU94ekhyV3ltcjJaeVNmT2ZTOWFBMWc9PQ==', '97aa289ef80d8dba21267f09418983626e6ba92097f3513f73ed2c94a553a289', '(11) 98765-4321', 'carlos.silva@email.com'),
(2, 'Beatriz Souza Martins', '5imS5ExyZKDDK+aPyHJeIE5vZ2ZSd2o3V0pscS9jZFNRSWJnU1E9PQ==', 'cc73e8dcb39ed992517f0ae56570d179884412e5cac1400faea434c62ac6ddff', '(11) 97654-3210', 'beatriz.martins@email.com'),
(3, 'Fernando Henrique Lima', '+mMnLn7CGBtrm4rZoK9Ip05zck5PakFIWDRrVjg1T1U0azQ1Nmc9PQ==', 'f2a1958697046ed2c65d6f86c2fe3c02c59e0fe637ae1f1f5f8471141e565123', '(19) 99123-4567', 'fernando.lima@email.com');


-- Pets: Cada pet obrigatoriamente associado ao seu tutor
INSERT INTO pets (id, tutor_id, nome, especie, raca, data_nascimento) VALUES
(1, 1, 'Thor', 'Canina', 'Golden Retriever', '2021-05-10'),
(2, 2, 'Mia', 'Felina', 'Siamês', '2022-08-15'),
(3, 3, 'Bob', 'Canina', 'Bulldog Francês', '2020-02-20');


-- Agendamentos de demonstração em ordem cronológica
INSERT INTO agendamentos (id, tutor_id, pet_id, usuario_id, data_agendamento, hora_agendamento, servico, status, observacao) VALUES
(1, 1, 1, 1, '2026-11-18', '09:30:00', 'Consulta Geral e Vacinação Anual', 'AGENDADO', 'Animal dócil e com carteira atualizada'),
(2, 2, 2, 1, '2026-11-25', '14:00:00', 'Profilaxia Dentária e Tartarectomia', 'AGENDADO', 'Exames pré-operatórios normais'),
(3, 3, 3, 2, '2026-12-03', '10:30:00', 'Check-up Preventivo e Exame de Sangue', 'AGENDADO', 'Paciente alérgico a dipirona');


-- Ajuste dos contadores das sequências para inserções futuras
SELECT setval('usuarios_id_seq', (SELECT MAX(id) FROM usuarios));
SELECT setval('tutores_id_seq', (SELECT MAX(id) FROM tutores));
SELECT setval('pets_id_seq', (SELECT MAX(id) FROM pets));
SELECT setval('agendamentos_id_seq', (SELECT MAX(id) FROM agendamentos));
