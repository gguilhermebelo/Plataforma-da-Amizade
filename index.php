<?php
    session_start();
    ob_start();

    include_once(__DIR__ . '/config.php');
    include_once(__DIR__ . '/Function.php');
    
    unset($_SESSION["idProjeto"]);
    $projetos = visualizarProjetos($conexao);
    if (isset($_POST['submit'])) {

        $id = count($projetos) > 0 ? max(array_column($projetos, 'IDProjeto')) + 1 : 1;
        
        $_SESSION['idProjeto'] = $id + 1;
        inserirProjeto($conexao, $_SESSION['idProjeto'], $_POST['nome-projeto'], $_POST['descricao-projeto']);
        header('Location: ./checkListProjeto.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idProjeto'])) {
        $_SESSION['idProjeto'] = $_GET['idProjeto'];
        echo $_SESSION['idProjeto'];
        header('Location: ./checkListProjeto.php');
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
    <div class="absolute main">
        <div class="esquerda">
            <h1>Criar Novo Projeto</h1>
    
            <form class="form-projeto" action="" method="post" autocomplete="off">
                <div>
                    <label for="nome-projeto">Nome do Projeto:</label>
                    <input type="text" name="nome-projeto" id="nome-projeto" required>
                </div>
    
                <div>
                    <label for="descricao-projeto">Descriação do Projeto:</label>
                    <input type="text" name="descricao-projeto" id="descricao-projeto" required>
                </div>
                <button type="submit" name="submit">Enviar</button>
            </form>
        </div>

        <div class="direita">
            <h1>Projetos Existentes</h1>


            <table>
                <thead>
                    <tr>
                        <th>Nome Projeto</th>
                        <th>Descriação</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($projetos as $projeto) {
                            echo "<tr>
                                    <td>{$projeto['Nome']}</td>
                                    <td>{$projeto['Descricao']}</td>
                                    <td><a href='?idProjeto={$projeto['IDProjeto']}'>Editar</a></td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>