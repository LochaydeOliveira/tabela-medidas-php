<?php
include 'db_connect.php';

// Recupera o ID do produto a ser atualizado
$product_id = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
$product_name = isset($_POST['productName']) ? $_POST['productName'] : '';
$product_type = isset($_POST['productType']) ? $_POST['productType'] : '';

// Atualiza as informações do produto
$update_product_query = $conn->prepare("UPDATE produtos SET nome = ?, tipo_id = ? WHERE id = ?");
$update_product_query->bind_param("ssi", $product_name, $product_type, $product_id);

if ($update_product_query->execute()) {
    // Atualiza as medidas do produto
    $measurements = ['comprimento', 'ombro', 'busto', 'cintura', 'quadril', 'manga'];

    foreach ($measurements as $measurement) {
        // Verifica se os tamanhos existem antes de acessá-los
        if (isset($_POST[$measurement . '_size'])) {
            $sizes = $_POST[$measurement . '_size'];
            
            foreach ($sizes as $size) {
                $min_key = "{$measurement}_min_{$size}";
                $max_key = "{$measurement}_max_{$size}";
                
                $min_value = isset($_POST[$min_key]) ? $_POST[$min_key] : null;
                $max_value = isset($_POST[$max_key]) ? $_POST[$max_key] : null;

                // Adiciona ou atualiza a medida
                $update_measure_query = $conn->prepare("
                    INSERT INTO medidas_produto (produto_id, tamanho, comprimento_min, comprimento_max, ombro_min, ombro_max, busto_min, busto_max, cintura_min, cintura_max, quadril_min, quadril_max, manga_min, manga_max)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    comprimento_min = VALUES(comprimento_min),
                    comprimento_max = VALUES(comprimento_max),
                    ombro_min = VALUES(ombro_min),
                    ombro_max = VALUES(ombro_max),
                    busto_min = VALUES(busto_min),
                    busto_max = VALUES(busto_max),
                    cintura_min = VALUES(cintura_min),
                    cintura_max = VALUES(cintura_max),
                    quadril_min = VALUES(quadril_min),
                    quadril_max = VALUES(quadril_max),
                    manga_min = VALUES(manga_min),
                    manga_max = VALUES(manga_max)
                ");
                
                // Definindo parâmetros de acordo com os campos existentes
                $update_measure_query->bind_param(
                    "issiiiiiiiiiiii",
                    $product_id,
                    $size,
                    $min_value,
                    $max_value,
                    $min_value,
                    $max_value,
                    $min_value,
                    $max_value,
                    $min_value,
                    $max_value,
                    $min_value,
                    $max_value,
                    $min_value,
                    $max_value
                );
                
                if ($update_measure_query->execute()) {
                    echo "Medida do tamanho $size atualizada com sucesso.<br>";
                } else {
                    echo "Erro ao atualizar medida do tamanho $size: " . $conn->error . "<br>";
                }
            }
        }
    }

    echo "Produto atualizado com sucesso!";
} else {
    echo "Erro ao atualizar o produto: " . $conn->error;
}

$conn->close();
?>
