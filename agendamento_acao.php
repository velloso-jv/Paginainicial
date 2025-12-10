<?php
include "proteger.php";
include "conexao.php";

// EXCLUIR
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM agendamentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: consultar.php?excluido=1");
    exit;
}

// SALVAR EDIÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $id = intval($_POST['id']);
    $nome = $_POST['nome'];
    $tel  = $_POST['tel'];
    $email= $_POST['email'];
    $data = $_POST['data'];

    $stmt = $conn->prepare("UPDATE agendamentos SET nome=?, telefone=?, email=?, data_horario=? WHERE id=?");
    $stmt->bind_param("ssssi", $nome, $tel, $email, $data, $id);
    if ($stmt->execute()) {
        header("Location: consultar.php?editado=1");
        exit;
    } else {
        die("Erro ao atualizar: " . $stmt->error);
    }
}

// MOSTRAR FORMULÁRIO DE EDIÇÃO
if (!isset($_GET['id'])) {
    header("Location: consultar.php");
    exit;
}
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT id, nome, telefone, email, data_horario FROM agendamentos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$ag = $res->fetch_assoc();
if (!$ag) {
    die("Agendamento não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Editar Agendamento</title>
  <link rel="stylesheet" href="styhome.css">
</head>
<body>
  <center>
    <h1 class="agendamentoh1">Editar Agendamento</h1>
    <div class="form-container" style="width:360px;">
      <form action="agendamento_acao.php" method="POST">
        <input type="hidden" name="acao" value="salvar">
        <input type="hidden" name="id" value="<?= $ag['id'] ?>">
        <label>Nome completo:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($ag['nome']) ?>" required>
        <label>Telefone:</label>
        <input type="text" name="tel" value="<?= htmlspecialchars($ag['telefone']) ?>" required>
        <label>E-mail:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($ag['email']) ?>" required>
        <label>Data e horário:</label>
        <input type="datetime-local" name="data" value="<?= date('Y-m-d\TH:i', strtotime($ag['data_horario'])) ?>" required>
        <button type="submit">Salvar Alterações</button>
      </form>
      <br>
      <a href="consultar.php">Voltar</a> |
      <a href="agendamento_acao.php?acao=excluir&id=<?= $ag['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
    </div>
  </center>
</body>
</html>
