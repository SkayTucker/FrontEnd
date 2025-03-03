CREATE DATABASE scrum_db;

USE scrum_db;

CREATE TABLE etapas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL
);

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etapa_id INT,
    descricao VARCHAR(255) NOT NULL,
    concluido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (etapa_id) REFERENCES etapas(id) ON DELETE CASCADE
);

-- Inserindo Etapas
INSERT INTO etapas (titulo) VALUES ('🏁 Etapa 1 - Criação do Projeto');
INSERT INTO etapas (titulo) VALUES ('🎨 Etapa 2 - Estilização das Views');

-- Inserindo Tarefas
INSERT INTO tarefas (etapa_id, descricao, concluido) VALUES (1, 'Criar repositório no GitHub', 0);
INSERT INTO tarefas (etapa_id, descricao, concluido) VALUES (1, 'Configurar estrutura base do projeto', 0);
INSERT INTO tarefas (etapa_id, descricao, concluido) VALUES (2, 'Definir tema e identidade visual', 0);
INSERT INTO tarefas (etapa_id, descricao, concluido) VALUES (2, 'Adicionar dependências essenciais', 0);
