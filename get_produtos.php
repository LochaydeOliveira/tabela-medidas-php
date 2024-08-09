<?php
// Incluir o arquivo de conexão
include 'db_connect.php';

// Definir o cabeçalho para JSON
header('Content-Type: application/json');

// Obter os parâmetros de consulta
$produto = $_GET['produto'] ?? '';
$tamanho = $_GET['tamanho'] ?? '';

// Consultar medidas
$sql = $conn->prepare("
    SELECT p.nome AS nome_produto, m.tamanho, 
           m.comprimento_min, m.comprimento_max, 
           m.ombro_min, m.ombro_max, 
           m.busto_min, m.busto_max, 
           m.cintura_min, m.cintura_max, 
           m.quadril_min, m.quadril_max, 
           m.manga_min, m.manga_max
    FROM medidas m
    INNER JOIN produtos p ON m.produto_id = p.id
    WHERE (p.nome = ? OR p.id = ?) AND m.tamanho = ?
");

$sql->bind_param('sis', $produto, $produto, $tamanho);
$sql->execute();
$result = $sql->get_result();

$medidas = [];

if ($result->num_rows > 0) {
    // Iterar sobre os resultados e adicioná-los ao array
    while ($row = $result->fetch_assoc()) {
        $medidas[] = $row;
    }
}

// Fechar a conexão
$conn->close();

// Retornar os dados como JSON
echo json_encode($medidas);
?>
