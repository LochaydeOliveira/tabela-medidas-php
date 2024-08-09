<?php
include 'db_connect.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';

$measurements = [];
if ($product_id && $product_name) {
    $stmt = $conn->prepare("
        SELECT MIN(m.comprimento_min) AS comprimento_min, MAX(m.comprimento_max) AS comprimento_max,
               MIN(m.busto_min) AS busto_min, MAX(m.busto_max) AS busto_max,
               MIN(m.cintura_min) AS cintura_min, MAX(m.cintura_max) AS cintura_max,
               MIN(m.quadril_min) AS quadril_min, MAX(m.quadril_max) AS quadril_max,
               MIN(m.manga_min) AS manga_min, MAX(m.manga_max) AS manga_max
        FROM medidas_produto m
        JOIN produtos p ON m.produto_id = p.id
        WHERE p.id = ? AND p.nome = ?
    ");
    $stmt->bind_param("is", $product_id, $product_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $measurements = $result->fetch_assoc();
    $stmt->close();
}

if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Tamanho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; background-color: #f1f1f1; display: flex; align-items: center; justify-content: center; margin: 0; min-height: 100vh; }
        .container { padding: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; width: 100%; max-width: 500px; }
        .button-busca-custom {
            position: relative;
            top: 8px;
            width: 20%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-3">Calculadora de Tamanho</h1>
        <form id="productForm" action="" method="GET" class="align-items-center d-flex justify-content-between gap-1">
            <div class="mb-3">
                <label for="product_id" class="form-label">ID do Produto</label>
                <input type="number" class="form-control" id="product_id" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>" required>
            </div>
            <div class="mb-3 w-100">
                <label for="product_name" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary button-busca-custom">Buscar</button>
        </form>

        <?php if ($measurements): ?>
        <form id="measurementForm" action="calcular_tamanho.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
            <div class="mb-3">
                <label for="comprimento" class="form-label">Comprimento</label>
                <input type="number" class="form-control" id="comprimento" name="comprimento" placeholder="entre <?php echo htmlspecialchars($measurements['comprimento_min']) . ' a ' . htmlspecialchars($measurements['comprimento_max']); ?> cm" min="<?php echo htmlspecialchars($measurements['comprimento_min']); ?>" max="<?php echo htmlspecialchars($measurements['comprimento_max']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="busto" class="form-label">Busto</label>
                <input type="number" class="form-control" id="busto" name="busto" placeholder="entre <?php echo htmlspecialchars($measurements['busto_min']) . ' a ' . htmlspecialchars($measurements['busto_max']); ?> cm" min="<?php echo htmlspecialchars($measurements['busto_min']); ?>" max="<?php echo htmlspecialchars($measurements['busto_max']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cintura" class="form-label">Cintura</label>
                <input type="number" class="form-control" id="cintura" name="cintura" placeholder="entre <?php echo htmlspecialchars($measurements['cintura_min']) . ' a ' . htmlspecialchars($measurements['cintura_max']); ?> cm" min="<?php echo htmlspecialchars($measurements['cintura_min']); ?>" max="<?php echo htmlspecialchars($measurements['cintura_max']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quadril" class="form-label">Quadril</label>
                <input type="number" class="form-control" id="quadril" name="quadril" placeholder="entre <?php echo htmlspecialchars($measurements['quadril_min']) . ' a ' . htmlspecialchars($measurements['quadril_max']); ?> cm" min="<?php echo htmlspecialchars($measurements['quadril_min']); ?>" max="<?php echo htmlspecialchars($measurements['quadril_max']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="manga" class="form-label">Manga</label>
                <input type="number" class="form-control" id="manga" name="manga" placeholder="entre <?php echo htmlspecialchars($measurements['manga_min']) . ' a ' . htmlspecialchars($measurements['manga_max']); ?> cm" min="<?php echo htmlspecialchars($measurements['manga_min']); ?>" max="<?php echo htmlspecialchars($measurements['manga_max']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Calcular Tamanho</button>
        </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
