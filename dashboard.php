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
 
$sql = "SELECT * from produtos";
$resultado = $conn-> query($sql);
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
    <a href="cadastro-produto.php">Cadastro produtos</a>
  </header>
  <main>
<?php if($resultado->num_rows > 0): ?>
<?php while ($produto = $resultado->fetch_assoc()): ?>
  <div>
<h3>Descrição:<?php echo $produto['descricao'] ?></h3>
<h3>Qunatidade:<?php echo $produto['qunatidade'] ?></h3>
<buton>Editar</buton>
<buton>Excuir</buton>
  </div>
<?php endwhile ?>
<?php else: ?>
  <p>Nenhum produto cadastrado.</p>
<?php endif; ?>
<?php $conn->close(); ?>


  </main>
</body>
 
</html>
