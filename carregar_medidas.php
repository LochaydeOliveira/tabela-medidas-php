<?php
include 'db_connect.php';

if (isset($_GET['produto_id'])) {
    $produtoId = intval($_GET['produto_id']);
    
    $sql = "SELECT * FROM medidas WHERE produto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produtoId);
    $stmt->execute();
    $result = $stmt->get_result();

    $medidas = [];
    while ($row = $result->fetch_assoc()) {
        $medidas[] = $row;
    }
    
    echo json_encode($medidas);

    $stmt->close();
}

$conn->close();
?>
