<?php
require_once 'includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM produtos WHERE id = ?"; // Usar prepared statement

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // 'i' para inteiro

    if ($stmt->execute()) {
        echo "Produto excluído com sucesso!";
    } else {
        echo "Erro ao excluir o produto: " . $stmt->error;
    }

    $conn->close();
    header("Location: dashboard.php");
} else {
    echo "Id não encontrado!";
}