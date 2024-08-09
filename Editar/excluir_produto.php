<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Exclui todas as medidas associadas ao produto
    $sql_delete_medidas = "DELETE FROM medidas_produto WHERE produto_id = $id";
    if ($conn->query($sql_delete_medidas) === TRUE) {
        // Depois de excluir as medidas, exclui o produto
        $sql_delete_produto = "DELETE FROM produtos WHERE id = $id";
        if ($conn->query($sql_delete_produto) === TRUE) {
            header("Location: listar_produtos.php");
            exit();
        } else {
            echo "Erro ao excluir o produto: " . $conn->error;
        }
    } else {
        echo "Erro ao excluir as medidas do produto: " . $conn->error;
    }
}

$conn->close();
?>
