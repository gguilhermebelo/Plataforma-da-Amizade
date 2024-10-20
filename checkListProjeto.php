<?php 
    session_start();
    ob_start();

    include_once(__DIR__ . '/config.php');
    include_once(__DIR__ . '/Function.php');
    $projeto = visualizarProjeto($conexao, $_SESSION['idProjeto']);
    $nomeProjeto = $projeto[0]["Nome"];


    $naoConformidade = null;

    function alterarConformidadeRep($conexao) {
        $idPergunta = $_POST['idPergunta'];
        $idProjeto = $_POST['idProjeto'];
        $estaConforme = $_POST['estaConforme'];
        
        alterarConformidade($conexao, $idPergunta, $idProjeto, $estaConforme);
    }

    function salvarNaoConformidadeRep($conexao) {
        global $naoConformidade ;
        $naoConformidade = null;
        $idPergunta = $_POST['idPergunta']; 
        $idProjeto = $_SESSION['idProjeto'];
        $estaConforme = $_POST['estaConforme'];
        $acaoCorretiva = $_POST['acaoCorretiva'];
        $responsavel = $_POST['responsavel'];
        $dataEnvio = $_POST['dataEnvio'];
        $superior = $_POST['superior'];
        $numeroEscalonamento = $_POST['numeroEscalonamento'] ?? null;
        $corrigido = $_POST['corrigido'];
        
        alterarConformidade($conexao, $idPergunta, $idProjeto, $corrigido);
        salvarNaoConformidade($conexao, $idProjeto, $idPergunta, $acaoCorretiva, $responsavel, $dataEnvio, $superior, $numeroEscalonamento, $corrigido);
    }

    function buscarNaoConformidadeRep($conexao) {
        global $naoConformidade;
        $idPergunta = $_GET['idPergunta']; 
        $idProjeto = $_SESSION['idProjeto'];

        $naoConformidade = visualizarNaoConformidade($conexao, $idProjeto, $idPergunta);
        echo "<script defer>
        document.addEventListener('DOMContentLoaded', function() {
            const segundoForm = document.querySelector('.segundo-form');
            if (segundoForm) {
                console.log(segundoForm.classList);
                segundoForm.classList.toggle('absolute');
                segundoForm.classList.toggle('none');
                console.log(segundoForm.classList);
            }
        });
    </script>";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'alterarConformidade':
                    alterarConformidadeRep($conexao);
                    break;
                case 'salvarNaoConformidade':
                    salvarNaoConformidadeRep($conexao);
                    break;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['action']) && $_GET['action'] === 'buscarNaoConformidade') {
            buscarNaoConformidadeRep($conexao);
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma da Amizada</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Tabela de Checklist</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Secção Documento</th>
            <th>Pergunta</th>
            <th>Está Conforme</th>
            <th>Classificação</th>
        </tr>
    </thead>
    <tbody>
        

        <?php
            // $perguntas = visualizarPerguntas($conexao);
            $checklistProjeto = visualizarPerguntasPorProjeto($conexao, $_SESSION['idProjeto']);

            foreach ($checklistProjeto as $pergunta) {
                $valorConforme = isset($pergunta['Esta_Conforme']) ? $pergunta['Esta_Conforme'] : '';
                echo "<tr>
                    <td> {$pergunta['IDPergunta']} </td>
                    
                        <td> {$pergunta['Seccao']} </td>
                        <td> {$pergunta['Pergunta']} </td>
                        <td id='campo-esta-conforme'>
                            <select id='{$pergunta['IDPergunta']}' onchange='handleSelectChange(this, event)'>
                                <option value=''>  </option>
                                <option value='SIM' " . ($valorConforme === 'SIM' ? 'selected' : '') . "> SIM </option>
                                <option value='NÃO' " . ($valorConforme === 'NÃO' ? 'selected' : '') . "> NÃO </option>
                                <option value='NAO SE APLICA' " . ($valorConforme === 'NAO SE APLICA' ? 'selected' : '') . "> NÃO SE APLICA </option>
                            </select>".
                            ($valorConforme === 'NÃO' 
                                ? "<a href='?action=buscarNaoConformidade&idPergunta={$pergunta['IDPergunta']}'>Editar</a>" 
                                : ''
                            ) .
                        "</td>
                        <td> {$pergunta['Classificacao']} </td>
                    </tr>
                    
                    <form id='form-{$pergunta['IDPergunta']}' action='#' method='POST' style='display: none;'>
                        <input type='hidden' name='action' value='alterarConformidade'>
                        <input type='hidden' name='idPergunta' value='{$pergunta['IDPergunta']}'>
                        <input type='hidden' name='idProjeto' value='{$_SESSION['idProjeto']}'>
                        <input type='hidden' name='estaConforme' id='hidden-esta-conforme-{$pergunta['IDPergunta']}' value=''>
                    </form>
                ";    
            }
        ?>
    </tbody>
</table>


    <div class="segundo-form none" onclick="toggleDisplayNone(event)">
        <form action="" method="POST" class="nao-conformidades">
            <input type="hidden" name="action" value="salvarNaoConformidade">
            <table>
                <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>ID da Pergunta</th>
                        <th>Ação Corretiva</th>
                        <th>Responsavel</th>
                        <th>Data de Envio</th>
                        <th>Superior</th>
                        <th>Numero Escalonamento</th>
                        <th>Corrigido</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $nomeProjeto; ?></td>
                    <td>
                        <input type="text" id="id-pergunta" name="idPergunta" value="<?php echo $naoConformidade[0]['IDPergunta'] ?? isset($_GET['idPergunta']); ?>" readonly>
                    </td>
                    <td>
                        <input type="text" name="acaoCorretiva" placeholder="Ação Corretiva" value="<?php echo $naoConformidade[0]['Acao_Corretiva'] ?? ''; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="responsavel" placeholder="Responsável" value="<?php echo $naoConformidade[0]['Responsavel'] ?? ''; ?>" required>
                    </td>
                    <td>
                        <input type="date" name="dataEnvio" value="<?php echo $naoConformidade[0]['Data_Envio'] ?? ''; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="superior" placeholder="Superior" value="<?php echo $naoConformidade[0]['Superior'] ?? ''; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="numeroEscalonamento" placeholder="Número Escalonamento" value="<?php echo $naoConformidade[0]['Numero_Escalonamento'] ?? ''; ?>">
                    </td>
                    <td style='display: none;'>
                        <input type="hidden" name="estaConforme" id="id-conforme" value="">
                    </td>
                    <td>
                        <select name="corrigido" required>
                            <option value="SIM" <?php echo (isset($naoConformidade[0]['Corrigido']) && $naoConformidade[0]['Corrigido'] === 'SIM') ? 'selected' : ''; ?>>SIM</option>
                            <option value="NÃO" <?php echo (isset($naoConformidade[0]['Corrigido']) && $naoConformidade[0]['Corrigido'] === 'NÃO') ? 'selected' : ''; ?>>NÃO</option>
                        </select>
                    </td>
                </tr>
            </tbody>
            </table>
            <div class="btn-area">
                <div class="btn-cancelar" onclick="toggleDisplayNone(document.querySelector('.segundo-form'), event)">Cancelar</div>
                <button type="submit">Salvar</button>
            </div>
        </form>
    </div>

    <div class="btn-area btn-area-infos">
        <a class="btn-voltar" href="./">Voltar</a>
        <a class="btn-nao-conforme" href="./naoConformidades.php">Não Conformidades</a>
        <a href="./resultados.php">Resultados</a>
    </div>

<script src="checkListProjeto.js" defer></script>
</body>
</html>
