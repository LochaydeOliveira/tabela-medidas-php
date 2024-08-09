<?php
include 'db_connect.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_nome = $_POST['productName'];
    $tipo_id = $_POST['productType'];

    // Sanitiza e valida os dados recebidos
    $produto_nome = filter_var($produto_nome, FILTER_SANITIZE_STRING);
    $tipo_id = filter_var($tipo_id, FILTER_VALIDATE_INT);

    if ($produto_nome && $tipo_id) {
        // Insere o produto
        $sql = "INSERT INTO produtos (nome, tipo_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param('si', $produto_nome, $tipo_id);
        $stmt->execute();
        $produto_id = $stmt->insert_id;
        $stmt->close();

        // Adiciona as medidas
        $tamanhos = ['P', 'M', 'G', 'GG']; // Certifique-se de que esses tamanhos correspondem aos valores reais no banco de dados
        foreach ($tamanhos as $tamanho) {
            $comprimento_min = filter_var($_POST["comprimento_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $comprimento_max = filter_var($_POST["comprimento_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);
            $ombro_min = filter_var($_POST["ombro_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $ombro_max = filter_var($_POST["ombro_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);
            $busto_min = filter_var($_POST["busto_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $busto_max = filter_var($_POST["busto_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);
            $cintura_min = filter_var($_POST["cintura_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $cintura_max = filter_var($_POST["cintura_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);
            $quadril_min = filter_var($_POST["quadril_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $quadril_max = filter_var($_POST["quadril_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);
            $manga_min = filter_var($_POST["manga_" . strtolower($tamanho) . "_min"], FILTER_VALIDATE_FLOAT);
            $manga_max = filter_var($_POST["manga_" . strtolower($tamanho) . "_max"], FILTER_VALIDATE_FLOAT);

            $sql = "INSERT INTO medidas_produto (produto_id, tamanho, comprimento_min, comprimento_max, ombro_min, ombro_max, busto_min, busto_max, cintura_min, cintura_max, quadril_min, quadril_max, manga_min, manga_max)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param('isdddddddddddd', $produto_id, $tamanho, $comprimento_min, $comprimento_max, $ombro_min, $ombro_max, $busto_min, $busto_max, $cintura_min, $cintura_max, $quadril_min, $quadril_max, $manga_min, $manga_max);
            $stmt->execute();
            $stmt->close();
        }

        echo "Produto salvo com sucesso!";
    } else {
        echo "Dados inválidos.";
    }
}

// Feche a conexão com o banco de dados
$conn->close();
?>
