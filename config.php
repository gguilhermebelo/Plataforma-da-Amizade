<?php 
    $dbHost = 'Localhost';
    $dbUsername ='root';
    $dbPassword='';
    $dbname = 'Amizade';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbname);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
?>