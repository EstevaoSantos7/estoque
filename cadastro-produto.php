    <?php
    require_once 'includes/config.php';

    $mensagem_sucesso = "";
    $mensagem_erro = "";
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $quantidade = intval($_POST['quantidade']);

        $sql_verifica = "SELECT * FROM produtos WHERE nome = ?";
        $stmt_verifica = $conn->prepare($sql_verifica);
        $stmt_verifica->bind_param('s', $nome);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem_erro = "Este produto ja esta cadastrado.";
        } else {
            $sql = "INSERT INTO usuarios (nome, descricao, quantidade) Values (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $nome, $descricao, $quantidade);

            if ($stmt->execute()) {
                $_SESSION['mensagem-sucesso'] = "Cadastro realizado com sucesso";
                header("Location: cadastro-produto.php");
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
                    <h2 id="titulo">Cadastro de Produtos</h2>
                    <label for="nome"></label>
                    <input placeholder="Nome" type="text" id="nome" name="nome" required>
                    <label for="descricao"></label>
                    <input placeholder="Descriçao" type="text" id="descricao" name="descricao" required>
                    <label for="quantidade"></label>
                    <input placeholder="Quantidade" type="text" id="quantidade" name="quantidade" required>

                    <?php
                    if ($mensagem_sucesso): ?>
                        <p><?php echo $mensagem_sucesso; ?></p>

                    <?php endif; ?>
                    <?php
                    if ($mensagem_erro): ?>
                        <p><?php echo $mensagem_erro; ?></p>

                    <?php endif; ?>

                    <a class="cadastra" href="dashboard.php">Ira para Dashboard</a>

                </form>
            </div>
        </div>
    </body>

    </html>