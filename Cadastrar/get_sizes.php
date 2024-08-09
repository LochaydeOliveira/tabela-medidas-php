<?php
include 'db_connect.php';

$sql = "SELECT DISTINCT tamanho FROM medidas_produto";
$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$sizes = [];
while ($row = $result->fetch_assoc()) {
    $sizes[] = htmlspecialchars($row['tamanho'], ENT_QUOTES, 'UTF-8');
}

// Retorne os tamanhos como JSON
header('Content-Type: application/json');
echo json_encode($sizes);

$conn->close();
?>
