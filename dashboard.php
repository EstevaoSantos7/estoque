<?php
session_start();
 
// Desconectar
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("location: index.php");
  exit();
}
 
// Verificar se existe usuário logado
if (!isset($_SESSION['usuario_id'])) {
  header("location: index.php");
  exit();
}
 
require_once 'includes/config.php'; // Inclua a conexão correta aqui
 
$conn->close();
?>
 
<!DOCTYPE html>
<html lang="pt-br">
 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
 
<body>
  <header>
    <a href="?logout=true">Sair</a>
  </header>
</body>
 
</html>
