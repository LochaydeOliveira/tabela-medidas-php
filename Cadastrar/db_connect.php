<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_tabela_medidas";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Configurar o conjunto de caracteres para UTF-8
$conn->set_charset("utf8");
?>
