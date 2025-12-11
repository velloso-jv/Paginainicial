<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'conexao.php'; // conexão com o banco

$erro = ""; // variável para mensagens de erro

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST['usuario'] ?? '';
    $senhaDigitada = $_POST['senha'] ?? '';

    if (!empty($usuario) && !empty($senhaDigitada)) {

        // Prepara a query
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE usuario = ?");
        if ($stmt === false) {
            die("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($senhaCriptografada);
            $stmt->fetch();

            // Verifica a senha com hash bcrypt
            if (password_verify($senhaDigitada, $senhaCriptografada)) {
                $_SESSION['usuario'] = $usuario;
                header("Location: agendamento_acao.php");
                exit();
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }

        $stmt->close();
    } else {
        $erro = "Preencha todos os campos!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Login - Admin</title>
    <link rel="stylesheet" href="styhome.css">
</head>
<body>
    <div class="form-container" style="width:360px; margin:80px auto;">
        <h2 class="agendamentoh1">Área Administrativa</h2>

        <?php if(!empty($erro)) { ?>
            <p style="color:red; text-align:center;"><?= htmlspecialchars($erro) ?></p>
        <?php } ?>

        <form action="login.php" method="POST">
            <label>Usuário:</label>
            <input type="text" name="usuario" required>
            <label>Senha:</label>
            <input type="password" name="senha" required>
            <button type="submit">ENTRAR</button>
        </form>
    </div>
</body>
</html>
