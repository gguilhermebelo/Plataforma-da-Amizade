-- Script do Banco de Dados do TDE 03 de Qualidade de Software
-- drop database Plataforma da Amizade; 
create database Amizade;
use Amizade;

create table TBPerguntas_Checklist (
    IDPergunta INT PRIMARY KEY,
    Pergunta VARCHAR(80),
    Seccao VARCHAR(80),
    Classificacao ENUM('BAIXA 1 DIA', 'MEDIA 2 DIAS', 'ALTA 4 DIAS') 
);

create table TBProjeto (
    IDProjeto INT PRIMARY KEY,
    Nome VARCHAR(100),
    Descricao VARCHAR(100)
);

create table TBChecklist_Projeto(
    IDPergunta INT,
    IDProjeto INT,
    Esta_Conforme ENUM('SIM', 'NÃO', 'NAO SE APLICA'), 
    PRIMARY KEY (IDPergunta, IDProjeto),
    FOREIGN KEY (IDPergunta) REFERENCES TBPerguntas_Checklist(IDPergunta), 
    FOREIGN KEY (IDProjeto) REFERENCES TBProjeto(IDProjeto)                
);

create table TBNao_Conformidade (
    IDProjeto INT,
    IDPergunta INT,
    Acao_Corretiva VARCHAR(100),
    Responsavel VARCHAR(100),
    Data_Envio DATE, 
    Superior VARCHAR(100),
    Numero_Escalonamento VARCHAR(10),
    Corrigido VARCHAR(10),
    PRIMARY KEY (IDProjeto, IDPergunta),
    FOREIGN KEY (IDPergunta) REFERENCES TBPerguntas_Checklist(IDPergunta), 
    FOREIGN KEY (IDProjeto) REFERENCES TBProjeto(IDProjeto)                
);

                                    -- População das Tabelas do Banco de Dados da Plataforma da Amizade --

-- POPULAÇÃO DA TABELA PERGUNTAS CHECKLIST --
INSERT INTO TBPerguntas_Checklist (IDPergunta, Pergunta, Seccao, Classificacao) VALUES 
(1, 'O Resquisitos são entendidos sem ambiguidade?', 'Requisitos Funcionais', 'ALTA 4 DIAS');

-- POPULAÇÃO DA TABELA PROJETO --
INSERT INTO TBProjeto (IDProjeto, Nome, Descricao)
VALUES (1, 'Trama', 'E-commerce de moda sustentavel');

-- POPULAÇÃO DA TABELA CHECKLIST_PROJETO --
INSERT INTO TBChecklist_Projeto (IDPergunta, IDProjeto, Esta_Conforme)
VALUES (1, 1, 'NÃO');


-- POPULAÇÃO DA TABELA NAO CONFORMIDADE --
INSERT INTO TBNao_Conformidade (IDProjeto, IDPergunta, Acao_Corretiva, Responsavel, Data_Envio, Superior, Numero_Escalonamento, Corrigido)
VALUES (1, 1, 'Corrigir a forma que foi escrito os requisitos, deixando mais objetivo', 'João das Couves', '2024-10-14', 'Maria Florinda', '2', 'Nao');

                                                         -- Queries --

-- VIZUALIZAR PERGUNTAS -- 
SELECT * FROM TBPerguntas_Checklist;

-- VIZUALIZAR PROJETO -- 
SELECT * FROM TBProjeto;

-- VIZUALIZAR CHECKLIST_PROJETO -- 
SELECT * FROM TBChecklist_Projeto;

-- VIZUALIZAR NAO CONFORMIDADE -- 
SELECT * FROM TBNao_Conformidade;

-- VIZUALIZAR NÃO CONFORMIDADES QUE NÃO FORAM CORRIGIDAS --
SELECT n.IDProjeto, n.IDPergunta, n.Acao_Corretiva, n.Responsavel, n.Data_Envio
FROM TBNao_Conformidade n
WHERE n.Corrigido = 'Nao';

-- VIZUALIZAR AS NÃO CONFORMIDADES DE UM PROJETO -- 
SELECT n.Acao_Corretiva, n.Responsavel, n.Data_Envio, n.Corrigido
FROM TBNao_Conformidade n
WHERE n.IDProjeto = 1;

-- ALTERAR A CONFORMIDADE DA PERGUNTA -- 
UPDATE TBChecklist_Projeto
SET Esta_Conforme = 'NAO'
WHERE IDPergunta = 8 AND IDProjeto = 1;

UPDATE TBChecklist_Projeto
SET Esta_Conforme = 'SIM'
WHERE IDPergunta = 6 AND IDProjeto = 1;

-- ALTERAR A AÇÃO CORRETIVA DA NÃO CONFORMIDADE -- 
UPDATE TBNao_Conformidade
SET Acao_Corretiva = 'Listar o Requisitos de forma mais organizada, difernciando os funcionais dos não funcionais'
WHERE IDProjeto = 1 AND IDPergunta = 3;

-- DELETAR NÃO CONFORMIDADE -- 
DELETE FROM TBNao_Conformidade
WHERE IDProjeto = 1 AND IDPergunta = 8;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
