<?php
include 'db_connect.php';

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';

$comprimento = isset($_POST['comprimento']) ? floatval($_POST['comprimento']) : 0;
$busto = isset($_POST['busto']) ? floatval($_POST['busto']) : 0;
$cintura = isset($_POST['cintura']) ? floatval($_POST['cintura']) : 0;
$quadril = isset($_POST['quadril']) ? floatval($_POST['quadril']) : 0;
$manga = isset($_POST['manga']) ? floatval($_POST['manga']) : 0;

$faixas_tamanho = [
    'comprimento' => [
        'P' => [50, 55],
        'M' => [56, 59],
        'G' => [60, 64],
        'GG' => [65, 70]
    ],
    'busto' => [
        'P' => [35, 40],
        'M' => [41, 45],
        'G' => [46, 50],
        'GG' => [51, 55]
    ],
    'cintura' => [
        'P' => [30, 35],
        'M' => [36, 40],
        'G' => [41, 45],
        'GG' => [46, 50]
    ],
    'quadril' => [
        'P' => [40, 45],
        'M' => [46, 50],
        'G' => [51, 55],
        'GG' => [56, 60]
    ],
    'manga' => [
        'P' => [15, 20],
        'M' => [21, 25],
        'G' => [26, 30],
        'GG' => [31, 35]
    ]
];

function calcular_tamanho_ideal($faixas_tamanho, $comprimento, $busto, $cintura, $quadril, $manga) {
    $tamanhos = ['P', 'M', 'G', 'GG'];
    $contagem_tamanhos = array_fill_keys($tamanhos, 0);

    foreach ($faixas_tamanho as $medida => $faixas) {
        foreach ($faixas as $tamanho => $intervalo) {
            switch ($medida) {
                case 'comprimento':
                    if ($comprimento >= $intervalo[0] && $comprimento <= $intervalo[1]) {
                        $contagem_tamanhos[$tamanho]++;
                    }
                    break;
                case 'busto':
                    if ($busto >= $intervalo[0] && $busto <= $intervalo[1]) {
                        $contagem_tamanhos[$tamanho]++;
                    }
                    break;
                case 'cintura':
                    if ($cintura >= $intervalo[0] && $cintura <= $intervalo[1]) {
                        $contagem_tamanhos[$tamanho]++;
                    }
                    break;
                case 'quadril':
                    if ($quadril >= $intervalo[0] && $quadril <= $intervalo[1]) {
                        $contagem_tamanhos[$tamanho]++;
                    }
                    break;
                case 'manga':
                    if ($manga >= $intervalo[0] && $manga <= $intervalo[1]) {
                        $contagem_tamanhos[$tamanho]++;
                    }
                    break;
            }
        }
    }

    arsort($contagem_tamanhos);
    return array_key_first($contagem_tamanhos);
}

$tamanho_ideal = calcular_tamanho_ideal($faixas_tamanho, $comprimento, $busto, $cintura, $quadril, $manga);

if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Tamanho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .body-custom {
        padding: 0;
        display: flex;
        align-items: center;
        height: 800px;
    }
    .container-custom {
        max-width: 30%;
    }
</style>
<body class="body-custom">
    <div class="container container-custom">
        <h1 class="text-center mb-3">Resultado do Tamanho</h1>
        <div class="alert alert-info text-center">
            O tamanho ideal para você é: <?php echo htmlspecialchars($tamanho_ideal); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
