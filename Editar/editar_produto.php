<?php
include 'db_connect.php';

// Recupera o ID do produto a ser editado
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obter o produto
$product_query = $conn->prepare("SELECT p.id, p.nome, p.tipo_id, m.tamanho, m.comprimento_min, m.comprimento_max, m.ombro_min, m.ombro_max, m.busto_min, m.busto_max, m.cintura_min, m.cintura_max, m.quadril_min, m.quadril_max, m.manga_min, m.manga_max
FROM produtos p
LEFT JOIN medidas_produto m ON p.id = m.produto_id
WHERE p.id = ?");
$product_query->bind_param("i", $product_id);
$product_query->execute();
$product_result = $product_query->get_result();
$product_data = $product_result->fetch_assoc();

// Consulta para obter todos os tipos de produtos
$types_query = "SELECT id, nome FROM tipos_produtos";
$types_result = $conn->query($types_query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Inclua os estilos CSS necessários aqui */
        .border-inputs { border: none; font-size: 12px; border-radius: 2px; width: 70px; }
        body { padding: 2rem; background-color: #f1f1f1; display: flex; align-items: center; justify-content: center; margin: 0; min-height: 100vh; }
        .container { padding: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; width: 100%; max-width: 1000px; }
        .form-section { margin-bottom: 20px; }
        .form-section h4 { margin-bottom: 15px; color: #333; }
        .table-container { 
            margin-top: 15px;
            overflow-x: auto;
        }
        .table {
            width: 100%;
            min-width: 800px;
        }
        .form-check-group { display: flex; flex-wrap: wrap; gap: 10px; }
        input[type="text"]:invalid { border-color: red; }
        input[type="text"]:valid { border-color: green; }

        /* Estilos para o spinner e mensagem de sucesso */
        .spinner-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
        }
        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.4em;
        }
        .success-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-3">EDITAR PRODUTO</h1>
        <div class="form-section">
            <h4>Informações do Produto</h4>
            <form action="atualizar.php" method="POST">
                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product_data['id']); ?>">
                <div class="mb-2">
                    <label for="productName" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product_data['nome']); ?>" placeholder="Digite o nome do produto" required>
                </div>
                <div class="mb-2">
                    <label for="productType" class="form-label">Tipo de Produto</label>
                    <select class="form-select" id="productType" name="productType" required>
                        <option value="" disabled>Escolha o tipo de produto</option>
                        <?php while ($row = $types_result->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo $row['id'] == $product_data['tipo_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nome']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tamanho</th>
                                <th>Comprimento</th>
                                <th>Ombro</th>
                                <th>Busto</th>
                                <th>Cintura</th>
                                <th>Quadril</th>
                                <th>Manga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ajuste a consulta para obter medidas do produto
                            $measure_query = $conn->prepare("SELECT * FROM medidas_produto WHERE produto_id = ?");
                            $measure_query->bind_param("i", $product_id);
                            $measure_query->execute();
                            $measure_result = $measure_query->get_result();

                            while ($measure = $measure_result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($measure['tamanho']); ?></td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="comprimento_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['comprimento_min']); ?>" placeholder="Mín" aria-label="Comprimento Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="comprimento_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['comprimento_max']); ?>" placeholder="Máx" aria-label="Comprimento Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="ombro_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['ombro_min']); ?>" placeholder="Mín" aria-label="Ombro Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="ombro_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['ombro_max']); ?>" placeholder="Máx" aria-label="Ombro Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="busto_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['busto_min']); ?>" placeholder="Mín" aria-label="Busto Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="busto_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['busto_max']); ?>" placeholder="Máx" aria-label="Busto Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="cintura_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['cintura_min']); ?>" placeholder="Mín" aria-label="Cintura Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="cintura_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['cintura_max']); ?>" placeholder="Máx" aria-label="Cintura Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="quadril_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['quadril_min']); ?>" placeholder="Mín" aria-label="Quadril Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="quadril_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['quadril_max']); ?>" placeholder="Máx" aria-label="Quadril Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <input name="manga_<?php echo htmlspecialchars($measure['tamanho']); ?>_min" type="number" class="form-control border-inputs mb-1 mb-md-0" value="<?php echo htmlspecialchars($measure['manga_min']); ?>" placeholder="Mín" aria-label="Manga Mín <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                            <input name="manga_<?php echo htmlspecialchars($measure['tamanho']); ?>_max" type="number" class="form-control border-inputs" value="<?php echo htmlspecialchars($measure['manga_max']); ?>" placeholder="Máx" aria-label="Manga Máx <?php echo htmlspecialchars($measure['tamanho']); ?>" required>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para carregar os dados do produto
        function loadProductData(productId) {
            fetch(`get_product_data.php?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('productName').value = data.name;
                    document.getElementById('productType').value = data.type_id;

                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = ''; // Limpa as linhas existentes

                    data.measures.forEach(measure => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${measure.size}</td>
                            <td>
                                <div class="d-flex flex-column flex-md-row">
                                    <input name="comprimento_size[]" type="hidden" value="${measure.size}">
                                    <input name="comprimento_min_${measure.size}" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Comprimento Mín ${measure.size}" value="${measure.comprimento_min}" required>
                                    <input name="comprimento_max_${measure.size}" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Comprimento Máx ${measure.size}" value="${measure.comprimento_max}" required>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column flex-md-row">
                                    <input name="ombro_min_${measure.size}" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Ombro Mín ${measure.size}" value="${measure.ombro_min}" required>
                                    <input name="ombro_max_${measure.size}" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Ombro Máx ${measure.size}" value="${measure.ombro_max}" required>
                                </div>
                            </td>
                            <!-- Repita para busto, cintura, quadril e manga -->
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Erro ao carregar os dados do produto:', error));
        }

        // Exemplo de chamada da função
        const productId = document.getElementById('productId').value;
        if (productId) {
            loadProductData(productId);
        }
    </script>   

</body>
</html>
<?php
$conn->close();
?>
