<?php
session_start();
 
require_once 'includes/config.php';
 
$mensagem_erro = "";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];
 
  $sql_verifica = "SELECT * FROM usuarios WHERE email = ?";
  $stmt_verifica = $conn->prepare($sql_verifica);
  $stmt_verifica->bind_param('s', $email);
  $stmt_verifica->execute();
  $resultado = $stmt_verifica->get_result();
  $usuario = $resultado->fetch_assoc();
 
  if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header("Location: dashboard.php");
    exit();
  } else {
    $mensagem_erro = "Email ou senha incorretos.";
  }
 
  $stmt_verifica->close();
  $conn->close();
}
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="./css/index.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="formo">
        <form action="" method="POST">
            <h2>Login</h2>
            <br>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required><br>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br>

            <input id="entrar" type="submit" value="Entrar">
            <p>Ja tem conta? <a href="cadastro.php">Ir para Cadastro</a></p>

        </form>
    </div>
</body>

</html>