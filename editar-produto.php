<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("location: index.php");
    exit();
}

require_once 'includes/config.php';

$mensagem_sucesso = "";
$mensagem_erro = "";
$produto = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Corrigido para usar $_GET['id']
    $sql_verifica = "SELECT * FROM produtos WHERE id = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param('i', $id); // 'i' para inteiro
    $stmt_verifica->execute();
    $resultado = $stmt_verifica->get_result();
    if ($resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
    } else {
        $mensagem_erro = "Produto não encontrado";
    }
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = intval($_POST['quantidade']);

    $sql_verifica = "SELECT * FROM produtos WHERE nome = ? AND descricao = ? AND quantidade = ? AND id = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param('ssii', $nome, $descricao, $quantidade, $id);

    if ($stmt_verifica->execute()) {
        $resultado = $stmt_verifica->get_result();
        if ($resultado->num_rows > 0) {
            $mensagem_erro = "Este produto já está cadastrado.";
        } else {
            $sql_update = "UPDATE produtos SET nome = ?, descricao = ?, quantidade = ? WHERE id = ?"; // Corrigido para UPDATE
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('ssii', $nome, $descricao, $quantidade, $id);

            if ($stmt_update->execute()) {
                $_SESSION['mensagem-sucesso'] = "Produto atualizado com sucesso";
                header("Location: dashboard.php");
                exit();
            } else {
                $mensagem_erro = "Erro ao atualizar o produto.";
            }
            $stmt_update->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/stux.css">
    <link rel="stylesheet" href="./css/usuarios.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
</head>

<body>
    <div class="formo">
        <div class="formulario">
            <form action="" method="POST">
                <h2 id="titulo">EDITAR Produtos</h2>
                <label for="nome">Nome:</label>
                <input placeholder="Nome" type="text" id="nome" name="nome" value="<?php echo $produto['nome'] ?? ''; ?>" required>
                <label for="descricao">Descrição:</label>
                <input placeholder="Descrição" type="text" id="descricao" name="descricao" value="<?php echo $produto['descricao'] ?? ''; ?>" required>
                <label for="quantidade">Quantidade:</label>
                <input placeholder="Quantidade" type="text" id="quantidade" name="quantidade" value="<?php echo $produto['quantidade'] ?? ''; ?>" required>

                <?php if ($mensagem_sucesso): ?>
                    <p><?php echo $mensagem_sucesso; ?></p>
                <?php endif; ?>
                <?php if ($mensagem_erro): ?>
                    <p><?php echo $mensagem_erro; ?></p>
                <?php endif; ?>

                <input class="cadastra" type="submit" value="Atualizar">
                <a class="cadastra" href="dashboard.php">Ir para Dashboard</a>
            </form>
        </div>
    </div>
</body>

</html>