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
(1, 'O nome do projeto está preenchido?', 'Organização', 'ALTA 4 DIAS'),
(2, 'O nome está sem erros de digitação?', 'Organização', 'BAIXA 1 DIA'),
(3, 'O número da versão está preenchido?', 'Organização', 'MEDIA 2 DIAS'),
(4, 'O contexto do projeto está preenchido?', 'Organização', 'ALTA 4 DIAS'),
(5, 'A descrição do contexto é objetiva, ou seja, atinge um fim desejado?', 'Organização', 'BAIXA 1 DIA'),
(6, 'Os membros da equipe têm seus nomes completos preenchidos?', 'Equipe de Projeto', 'BAIXA 1 DIA'),
(7, 'Todos os membros possuem e-mails preenchidos ?', 'Equipe de Projeto', 'ALTA 4 DIAS'),
(8, 'Os e-mails preenchidos são válidos possuem @ e dominio existente?', 'Equipe de Projeto', 'BAIXA 1 DIA'),
(9, 'Os requisitos estão preenchidos?', 'Requisitos', 'BAIXA 1 DIA'),
(10, 'Os requisitos estão sem ambiguidade?', 'Requisitos', 'ALTA 4 DIAS'),
(11, 'A descrição de cada requisito está preenchida?', 'Requisitos', 'MEDIA 2 DIAS'),
(12, 'A prioridade de cada requisito está preenchida?', 'Requisitos', 'ALTA 4 DIAS'),
(13, 'As prioridades estão adequadamente classificadas como "Alto", "Médio" ou "Baixo"?', 'Requisitos', 'ALTA 4 DIAS'),
(14, 'O status de cada requisito está preenchido?', 'Requisitos', 'MEDIA 2 DIAS'),
(15, 'O status está atualizado conforme  "Concluído", "Em andamento" ou "Não iniciado"?', 'Requisitos', 'BAIXA 1 DIA');

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
DELIMITER //

CREATE TRIGGER trg_after_insert_tbprojeto
AFTER INSERT ON TBProjeto
FOR EACH ROW
BEGIN
    DECLARE v_IDPergunta INT;

    -- Cursor para iterar por todas as perguntas existentes em TBPerguntas_Checklist
    DECLARE pergunta_cursor CURSOR FOR 
        SELECT IDPergunta FROM TBPerguntas_Checklist;

    -- Mão de controle para evitar erro de não encontrar dados no cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_IDPergunta = NULL;

    OPEN pergunta_cursor;

    -- Loop para inserir um registro em TBChecklist_Projeto para cada IDPergunta
    read_loop: LOOP
        FETCH pergunta_cursor INTO v_IDPergunta;
        IF v_IDPergunta IS NULL THEN
            LEAVE read_loop; -- Sai do loop se não houver mais perguntas
        END IF;

        -- Inserindo o registro na tabela TBChecklist_Projeto
        INSERT INTO TBChecklist_Projeto (IDPergunta, IDProjeto, Esta_Conforme)
        VALUES (v_IDPergunta, NEW.IDProjeto, NULL); -- Conformidade inicial como NULL
    END LOOP;

    CLOSE pergunta_cursor;
END;
//

DELIMITER ;



-- POPULAÇÃO DA TABELA PROJETO --
INSERT INTO TBProjeto (IDProjeto, Nome, Descricao)
VALUES (1, 'Trama', 'E-commerce de moda sustentavel');