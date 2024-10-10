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
  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/stux.css">
  <link rel="stylesheet" href="./css/login.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div class="formo">
    <div class="titulo">
      <h1 id="titulo">Hello World</h1>
      <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit vitae, corporis minima facilis harum adipisci reiciendis dolorum neque nam error earum accusantium corrupti repellendus.?</p>
    </div>
    <div class="formulario">
      <form action="" method="POST">
        <h2 id="usuario">Login do Usuário</h2>
        <br>
        <label for="email"></label>
        <input placeholder="Email" type="text" id="email" name="email" required><br>
        <label for="senha"></label>
        <input placeholder="Password" type="password" id="senha" name="senha" required><br>

        <input id="login" type="submit" value="Login">
        <p class="cadastra">Se não tiver conta <a class="cadastra" href="cadastro.php">Faça seu Cadastro</a></p>

      </form>
    </div>
  </div>
</body>

</html>