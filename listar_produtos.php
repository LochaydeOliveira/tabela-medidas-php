<?php
include 'db_connect.php';

$sql = "SELECT p.id, p.nome AS product_name, tp.nome AS product_type FROM produtos p JOIN tipos_produtos tp ON p.tipo_id = tp.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; background-color: #f1f1f1; display: flex; align-items: center; justify-content: center; margin: 0; min-height: 100vh; }
        .container { padding: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; width: 100%; max-width: 1000px; }
        .table-container { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-3">Produtos Cadastrados</h1>
        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Produto</th>
                            <th>Tipo de Produto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo $row["product_name"]; ?></td>
                                <td><?php echo $row["product_type"]; ?></td>
                                <td>
                                    <a href="editar_produto.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                </td>
                                <td>
                                    <a href="excluir_produto.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
