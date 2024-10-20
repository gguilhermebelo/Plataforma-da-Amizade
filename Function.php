<?php
// class Function{
// }

include 'config.php';


function inserirProjeto($pdo, $idProjeto, $Nome, $Descricao) {
    $sql = "INSERT INTO TBProjeto (IDProjeto, Nome, Descricao) VALUES  ( ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idProjeto, $Nome, $Descricao,]);
}

function salvarNaoConformidade($pdo, $idProjeto, $idPergunta, $acaoCorretiva, $responsavel, $dataEnvio, $superior, $numeroEscalonamento, $corrigido) {
    $sqlCheck = "SELECT COUNT(*) AS total FROM TBNao_Conformidade WHERE IDProjeto = ? AND IDPergunta = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bind_param('ii', $idProjeto, $idPergunta); // 'ii' indica que ambos são inteiros
    $stmtCheck->execute();
    $result = $stmtCheck->get_result()->fetch_array(MYSQLI_ASSOC);

    $exists = $result['total'] > 0;

    if ($exists) {
        $sql = "UPDATE TBNao_Conformidade 
                SET Acao_Corretiva = ?,
                    Responsavel = ?,
                    Data_Envio = ?,
                    Superior = ?,
                    Numero_Escalonamento = ?,
                    Corrigido = ?
                WHERE IDProjeto = ? AND IDPergunta = ?";
        
        $stmt = $pdo->prepare($sql);
        $params = [
            $acaoCorretiva,
            $responsavel,
            $dataEnvio,
            $superior,
            $numeroEscalonamento,
            $corrigido,
            $idProjeto,
            $idPergunta
        ];
    } else {
        $sql = "INSERT INTO TBNao_Conformidade (IDProjeto, IDPergunta, Acao_Corretiva, Responsavel, Data_Envio, Superior, Numero_Escalonamento, Corrigido) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $params = [
            $idProjeto,
            $idPergunta,
            $acaoCorretiva,
            $responsavel,
            $dataEnvio,
            $superior,
            $numeroEscalonamento,
            $corrigido
        ];
    }

    $stmt->execute($params);
}

function visualizarPerguntasPorProjeto($pdo, $idProjeto) {
    $sql = "SELECT 
            p.IDPergunta, 
            p.Pergunta, 
            p.Classificacao, 
            c.Esta_Conforme, 
            p.Seccao 
        FROM 
            TBChecklist_Projeto c
        JOIN 
            TBPerguntas_Checklist p ON p.IDPergunta = c.IDPergunta
        WHERE 
            c.IDProjeto = $idProjeto";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function visualizarProjetos($pdo) {
    $sql = "SELECT * FROM TBProjeto";
    $stmt = $pdo->query($sql);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function visualizarProjeto($pdo, $idProjeto) {
    $sql = "SELECT * FROM TBProjeto WHERE IDProjeto = $idProjeto";
    $stmt = $pdo->query($sql);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function visualizarNaoConformidade($pdo, $idProjeto, $idPergunta) {
    $sql = "SELECT * FROM TBNao_Conformidade WHERE IDProjeto = $idProjeto AND IDPergunta = $idPergunta";
    $stmt = $pdo->query($sql);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function visualizarNaoConformidadesProjeto($pdo, $idProjeto) {
    $sql = "SELECT 
        p.Pergunta,
        n.Acao_Corretiva,
        p.Classificacao,
        n.Responsavel,
        n.Superior,
        n.Data_Envio,
        n.Corrigido
    FROM 
        TBNao_Conformidade n
    JOIN 
        TBPerguntas_Checklist p ON n.IDPergunta = p.IDPergunta
    WHERE 
        n.IDProjeto = $idProjeto";

    $stmt = $pdo->query($sql);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function alterarConformidade($pdo, $idPergunta, $idProjeto, $estaConforme) {
    $sql = "UPDATE TBChecklist_Projeto 
            SET Esta_Conforme = ? 
            WHERE IDPergunta = ? AND IDProjeto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$estaConforme, $idPergunta, $idProjeto]);
}