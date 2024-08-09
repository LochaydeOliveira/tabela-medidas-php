<?php
include 'db_connect.php'; // Inclua o arquivo de conexão

// Dados de teste
$testData = [
    ['produto_id' => 1, 'tamanho' => 'P', 'comprimento_min' => 70, 'comprimento_max' => 80, 'ombro_min' => 40, 'ombro_max' => 45, 'busto_min' => 85, 'busto_max' => 90, 'cintura_min' => 70, 'cintura_max' => 75, 'quadril_min' => 90, 'quadril_max' => 95, 'manga_min' => 60, 'manga_max' => 65],
    ['produto_id' => 1, 'tamanho' => 'M', 'comprimento_min' => 75, 'comprimento_max' => 85, 'ombro_min' => 42, 'ombro_max' => 47, 'busto_min' => 90, 'busto_max' => 95, 'cintura_min' => 75, 'cintura_max' => 80, 'quadril_min' => 95, 'quadril_max' => 100, 'manga_min' => 62, 'manga_max' => 67],
    ['produto_id' => 2, 'tamanho' => 'G', 'comprimento_min' => 80, 'comprimento_max' => 90, 'ombro_min' => 44, 'ombro_max' => 49, 'busto_min' => 95, 'busto_max' => 100, 'cintura_min' => 80, 'cintura_max' => 85, 'quadril_min' => 100, 'quadril_max' => 105, 'manga_min' => 64, 'manga_max' => 69]
];

foreach ($testData as $data) {
    $stmt = $conn->prepare("INSERT INTO medidas_produto (produto_id, tamanho, comprimento_min, comprimento_max, ombro_min, ombro_max, busto_min, busto_max, cintura_min, cintura_max, quadril_min, quadril_max, manga_min, manga_max) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdddddddddddd", $data['produto_id'], $data['tamanho'], $data['comprimento_min'], $data['comprimento_max'], $data['ombro_min'], $data['ombro_max'], $data['busto_min'], $data['busto_max'], $data['cintura_min'], $data['cintura_max'], $data['quadril_min'], $data['quadril_max'], $data['manga_min'], $data['manga_max']);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Dados de teste inseridos com sucesso!";
?>
