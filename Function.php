<?php
class Function{
}

include 'config.php';

function inserirPergunta($pdo, $idPergunta, $Pergunta, $Seccao, $Classificacao) {
    $sql = "INSERT INTO TBPerguntas_Checklist (IDPergunta, Pergunta, Seccao, Classificacao) VALUES  ( :idPergunta, :Pergunta, :Seccao, :Classificacao)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idPergunta' => $idPergunta,
        ':Pergunta' => $Pergunta,
        ':Seccao' => $Seccao,
        ':Classificacao' => $Classificacao,
    ]);
}

function inserirProjeto($pdo, $idProjeto, $Nome, $Descricao) {
    $sql = "INSERT INTO TBProjeto (IDProjeto, Nome, Descricao) VALUES  ( :idProjeto, :Nome, :Descricao)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idProjeto' => $idProjeto,
        ':Nome' => $Nome,
        ':Descricao' => $Descricao,
    ]);
}

function inserirChecklistProjeto($pdo, $IDPergunta, $IDProjeto, $Esta_Conforme) {
    $sql = "INSERT INTO TBChecklist_Projeto (IDPergunta, IDProjeto, Esta_Conforme) VALUES  ( :idPergunta, :idProjeto, :Esta_Conforme)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idPergunat' => $IDPergunta,
        ':idProjeto' => $IDProjeto,
        ':Esta_Conforme' => $Esta_Conforme,
    ]);
}

function inserirNaoConformidade($pdo, $idProjeto, $idPergunta, $acaoCorretiva, $responsavel, $dataEnvio, $superior, $numeroEscalonamento, $corrigido) {
    $sql = "INSERT INTO TBNao_Conformidade (IDProjeto, IDPergunta, Acao_Corretiva, Responsavel, Data_Envio, Superior, Numero_Escalonamento, Corrigido) 
            VALUES (:idProjeto, :idPergunta, :acaoCorretiva, :responsavel, :dataEnvio, :superior, :numeroEscalonamento, :corrigido)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idProjeto' => $idProjeto,
        ':idPergunta' => $idPergunta,
        ':acaoCorretiva' => $acaoCorretiva,
        ':responsavel' => $responsavel,
        ':dataEnvio' => $dataEnvio,
        ':superior' => $superior,
        ':numeroEscalonamento' => $numeroEscalonamento,
        ':corrigido' => $corrigido
    ]);
}

function visualizarChecklistProjeto($pdo) {
    $sql = "SELECT * FROM TBChecklist_Projeto";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function visualizarPerguntas($pdo) {
    $sql = "SELECT * FROM TBPerguntas_Checklist";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function visualizarProjeto($pdo) {
    $sql = "SELECT * FROM TBProjeto";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function visualizarNaoconformidade($pdo) {
    $sql = "SELECT * FROM TBNao_Conformidade";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function visualizarNaoConformidadesNaoCorrigidas($conexao) {
    $sql = "SELECT n.IDProjeto, n.IDPergunta, n.Acao_Corretiva, n.Responsavel, n.Data_Envio
            FROM TBNao_Conformidade n
            WHERE n.Corrigido = 'Nao'";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function visualizarNaoConformidadesProjeto($conexao) {
    $sql = " SELECT n.Acao_Corretiva, n.Responsavel, n.Data_Envio, n.Corrigido
             FROM TBNao_Conformidade n
             WHERE n.IDProjeto = 1;"
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function alterarConformidade($pdo, $idPergunta, $idProjeto, $estaConforme) {
    $sql = "UPDATE TBChecklist_Projeto 
            SET Esta_Conforme = :estaConforme 
            WHERE IDPergunta = :idPergunta AND IDProjeto = :idProjeto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':estaConforme' => $estaConforme,
        ':idPergunta' => $idPergunta,
        ':idProjeto' => $idProjeto
    ]);
}

function alterarAcaoCorretiva($pdo, $idProjeto, $idPergunta, $novaAcao) {
    $sql = "UPDATE TBNao_Conformidade 
            SET Acao_Corretiva = :novaAcao 
            WHERE IDProjeto = :idProjeto AND IDPergunta = :idPergunta";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':novaAcao' => $novaAcao,
        ':idProjeto' => $idProjeto,
        ':idPergunta' => $idPergunta
    ]);
}

function deletarNaoConformidade($pdo, $idProjeto, $idPergunta) {
    $sql = "DELETE FROM TBNao_Conformidade 
            WHERE IDProjeto = :idProjeto AND IDPergunta = :idPergunta";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idProjeto' => $idProjeto,
        ':idPergunta' => $idPergunta
    ]);
}
