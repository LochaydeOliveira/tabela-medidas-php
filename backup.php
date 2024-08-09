<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_tabela_medidas";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obter dados do formulário
$productName = $_POST['productName'];
$productType = $_POST['productType'];

$tamanhos = ['pp', 'p', 'm', 'g', 'gg', 'xg', 'xgg'];
foreach ($tamanhos as $tamanho) {
    $comprimento_min = $_POST["comprimento_{$tamanho}_min"];
    $comprimento_max = $_POST["comprimento_{$tamanho}_max"];
    $ombro_min = $_POST["ombro_{$tamanho}_min"];
    $ombro_max = $_POST["ombro_{$tamanho}_max"];
    $busto_min = $_POST["busto_{$tamanho}_min"];
    $busto_max = $_POST["busto_{$tamanho}_max"];
    $cintura_min = $_POST["cintura_{$tamanho}_min"];
    $cintura_max = $_POST["cintura_{$tamanho}_max"];
    $quadril_min = $_POST["quadril_{$tamanho}_min"];
    $quadril_max = $_POST["quadril_{$tamanho}_max"];
    $manga_min = $_POST["manga_{$tamanho}_min"];
    $manga_max = $_POST["manga_{$tamanho}_max"];

    // Inserir dados no banco de dados
    $sql = "INSERT INTO medidas_produto (nome_produto, tipo_produto, tamanho, comprimento_min, comprimento_max, ombro_min, ombro_max, busto_min, busto_max, cintura_min, cintura_max, quadril_min, quadril_max, manga_min, manga_max)
            VALUES ('$productName', '$productType', '$tamanho', $comprimento_min, $comprimento_max, $ombro_min, $ombro_max, $busto_min, $busto_max, $cintura_min, $cintura_max, $quadril_min, $quadril_max, $manga_min, $manga_max)";

    if ($conn->query($sql) === TRUE) {
        echo "Novo registro criado com sucesso";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Fechar a conexão
$conn->close();
?>
