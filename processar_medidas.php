<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produtoId = intval($_POST['produto']);
    $tamanho = $_POST['tamanho'];
    $comprimento = floatval($_POST['comprimento']);
    $ombro = floatval($_POST['ombro']);
    $busto = floatval($_POST['busto']);
    $cintura = floatval($_POST['cintura']);
    $quadril = floatval($_POST['quadril']);
    $manga = floatval($_POST['manga']);

    // Consultar as medidas do produto
    $sql = "SELECT * FROM medidas WHERE produto_id = ? AND tamanho = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $produtoId, $tamanho);
    $stmt->execute();
    $result = $stmt->get_result();

    $resultado = null;
    if ($row = $result->fetch_assoc()) {
        // Verificar se as medidas inseridas estão dentro dos intervalos
        if (
            $comprimento >= $row['comprimento_min'] && $comprimento <= $row['comprimento_max'] &&
            $ombro >= $row['ombro_min'] && $ombro <= $row['ombro_max'] &&
            $busto >= $row['busto_min'] && $busto <= $row['busto_max'] &&
            $cintura >= $row['cintura_min'] && $cintura <= $row['cintura_max'] &&
            $quadril >= $row['quadril_min'] && $quadril <= $row['quadril_max'] &&
            $manga >= $row['manga_min'] && $manga <= $row['manga_max']
        ) {
            $resultado = "O tamanho $tamanho é adequado para suas medidas.";
        } else {
            $resultado = "O tamanho $tamanho não é adequado para suas medidas.";
        }
    } else {
        $resultado = "Não foram encontradas medidas para o produto selecionado e o tamanho.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Provador Virtual</title>
</head>
<body>
    <h1>Resultado</h1>
    <p><?php echo htmlspecialchars($resultado); ?></p>
    <a href="provador.php">Voltar</a>
</body>
</html>
