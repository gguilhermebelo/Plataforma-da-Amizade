<?php 
session_start();
ob_start();

include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/Function.php');

// Visualiza o projeto
$projeto = visualizarProjeto($conexao, $_SESSION['idProjeto']);
$nomeProjeto = $projeto[0]["Nome"];

// Visualiza todas as não conformidades
$naoConformidades = visualizarNaoConformidadesProjeto($conexao, $_SESSION['idProjeto']);

// Inicialização das variáveis de filtro
$responsavelFiltro = isset($_POST['responsavel']) ? $_POST['responsavel'] : '';
$classificacaoFiltro = isset($_POST['classificacao']) ? $_POST['classificacao'] : '';
$corrigidoFiltro = isset($_POST['corrigido']) ? $_POST['corrigido'] : '';

// Aplicar filtros em não conformidades
if ($responsavelFiltro || $classificacaoFiltro || $corrigidoFiltro) {
    $naoConformidades = array_filter($naoConformidades, function($item) use ($responsavelFiltro, $classificacaoFiltro, $corrigidoFiltro) {
        $matches = true;

        // Filtra por responsável
        if ($responsavelFiltro) {
            $matches = $matches && stripos($item['Responsavel'], $responsavelFiltro) !== false;
        }

        // Filtra por classificação
        if ($classificacaoFiltro) {
            $matches = $matches && stripos($item['Classificacao'], $classificacaoFiltro) !== false;
        }

        // Filtra por corrigido
        if ($corrigidoFiltro) {
            $matches = $matches && $item['Corrigido'] === $corrigidoFiltro;
        }

        return $matches;
    });
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
<h2>Tabela de Não Conformidades</h2>

<!-- Formulário de Filtro -->
<form method="post" action="">
    <label for="responsavel">Responsável:</label>
    <input type="text" name="responsavel" id="responsavel" value="<?php echo htmlspecialchars($responsavelFiltro); ?>">

    <label for="classificacao">Classificação:</label>
    <input type="text" name="classificacao" id="classificacao" value="<?php echo htmlspecialchars($classificacaoFiltro); ?>">

    <label for="corrigido">Está Corrigido?</label>
    <select name="corrigido" id="corrigido">
        <option value="">Selecione</option>
        <option value="SIM" <?php echo ($corrigidoFiltro === 'SIM') ? 'selected' : ''; ?>>Sim</option>
        <option value="NÃO" <?php echo ($corrigidoFiltro === 'NÃO') ? 'selected' : ''; ?>>Não</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Ação Corretiva</th>
            <th>Classificação</th>
            <th>Responsável</th>
            <th>Superior</th>
            <th>Data de Envio do Comunicado</th>
            <th>Está Corrigido?</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($naoConformidades as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['Pergunta']); ?></td>
                <td><?php echo htmlspecialchars($item['Acao_Corretiva']); ?></td>
                <td><?php echo htmlspecialchars($item['Classificacao']); ?></td>
                <td><?php echo htmlspecialchars($item['Responsavel']); ?></td>
                <td><?php echo htmlspecialchars($item['Superior']); ?></td>
                <td><?php echo htmlspecialchars($item['Data_Envio']); ?></td>
                <td><?php echo htmlspecialchars($item['Corrigido']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="btn-area btn-area-infos">
    <a class="btn-voltar" href="./checkListProjeto.php">Voltar</a>
    <a href="./resultados.php">Resultados</a>
</div>

</body>
</html>
