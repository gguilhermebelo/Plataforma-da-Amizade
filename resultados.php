<?php 
session_start();
ob_start();

include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/Function.php');

// Supondo que o ID do projeto esteja armazenado na sessão
$idProjeto = $_SESSION['idProjeto'];

// Consulta ao banco de dados
$checklistProjeto = visualizarPerguntasPorProjeto($conexao, $idProjeto);

$totalRegistros = count($checklistProjeto);
$quantidadeSim = 0;
$quantidadeNao = 0;
$quantidadeNaoSeAplica = 0;

foreach ($checklistProjeto as $pergunta) {
    switch ($pergunta['Esta_Conforme']) {
        case 'SIM':
            $quantidadeSim++;
            break;
        case 'NÃO':
            $quantidadeNao++;
            break;
        case 'NAO SE APLICA':
            $quantidadeNaoSeAplica++;
            break;
    }
}

$totalValidos = $totalRegistros - $quantidadeNaoSeAplica;
$aderencia = $totalValidos > 0 ? ($quantidadeSim / $totalValidos) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma da Amizada</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center; /* Centraliza todo o texto */
        }
        h2 {
            margin: 20px 0;
        }
        .results {
            margin: 20px;
            font-size: 18px; /* Aumenta o tamanho da fonte */
        }
        .aderencia {
            font-size: 24px; /* Tamanho maior para destaque */
            font-weight: bold; /* Deixa o texto em negrito */
            color: #4CAF50; /* Cor verde para destaque */
        }
        canvas {
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<h2>Resultados do Checklist</h2>

<div class="results">
    <p>Total de Registros: <?php echo $totalRegistros; ?></p>
    <p>Quantidade de SIM: <?php echo $quantidadeSim; ?></p>
    <p>Quantidade de NÃO: <?php echo $quantidadeNao; ?></p>
    <p>Quantidade de NÃO SE APLICA: <?php echo $quantidadeNaoSeAplica; ?></p>
    <p class="aderencia">Aderência: <?php echo round($aderencia, 2); ?>%</p> <!-- Destaca a aderência -->
</div>

<canvas id="myChart" width="400" height="200"></canvas>

<div class="btn-area btn-area-infos">
        <a class="btn-voltar" href="./checkListProjeto.php">Voltar</a>
        <a class="btn-nao-conforme" href="./naoConformidades.php">Não Conformidades</a>
    </div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['SIM', 'NÃO', 'NÃO SE APLICA'],
            datasets: [{
                label: 'Quantidade',
                data: [<?php echo $quantidadeSim; ?>, <?php echo $quantidadeNao; ?>, <?php echo $quantidadeNaoSeAplica; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>