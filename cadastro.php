    <?php
    require_once 'includes/config.php';

    $mensagem_sucesso = "";
    $mensagem_erro = "";
    session_start();
    if (isset($_SESSION['usuario_id'])) {
        header("Location: dashboard.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $sql_verifica = "SELECT * FROM usuarios WHERE email = ?";
        $stmt_verifica = $conn->prepare($sql_verifica);
        $stmt_verifica->bind_param('s', $email);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem_erro = "Este email ja esta cadastrado.";
        } else {
            $sql = "INSERT INTO usuarios (nome, email, senha) Values (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $nome, $email, $senha);

            if ($stmt->execute()) {
                $_SESSION['mensagem-sucesso'] = "Cadastro realizado com sucesso";
                header("Location: cadastro.php");
                exit();
            } else {
                $mensagem_erro = "Erro ao cadastrar " . $conn->error;
            }
        }
        $stmt->close();
        $conn->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/cadastrar.css">
        <link rel="stylesheet" href="./css/stux.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
    </head>

    <body>
        <div class="formo">
            <div class="formulario">
                <form action="" method="POST">
                    <h2 id="usuario">Cadastro de usuarios</h2>
                    <label for="nome"></label>
                    <input placeholder="Nome" type="text" id="nome" name="nome" required><br>
                    <label for="email"></label>
                    <input placeholder="Email" type="text" id="email" name="email" required><br>
                    <label for="senha"></label>
                    <input placeholder="Senha" type="password" id="senha" name="senha" required><br>
            
            <?php
            if ($mensagem_sucesso): ?>
                <p><?php echo $mensagem_sucesso; ?></p>

            <?php endif; ?>
            <?php
            if ($mensagem_erro): ?>
                <p><?php echo $mensagem_erro; ?></p>

            <?php endif; ?>

            <input id="cadastros" type="submit" value="Cadastrar">
            <p class="cadastra">Já tem conta? <a class="cadastra" href="index.php">Ir para login</a></p>

            </form>
            </div>
        </div>
    </body>

    </html>