    <?php
    require_once 'includes/config.php';

    $mensagem_sucesso = "";
    $mensagem_erro = "";

    if(isset($_SESSION['usuario_id'])){
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
        <link rel="stylesheet" href="./css/cadastro.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <div class="formo">
            <form action="" method="POST">
                <h2>Cadastro de usuarios</h2>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required><br>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required><br>

<?php
if($mensagem_sucesso):?>
<p><?php echo $mensagem_sucesso; ?></p>

<?php endif; ?>
<?php
if($mensagem_erro):?>
<p><?php echo $mensagem_erro; ?></p>

<?php endif; ?>

                <input type="submit" value="Cadastrar">
                <p>Ja tem conta? <a href="index.php">Ira para login</a></p>

            </form>
        </div>
    </body>

    </html>