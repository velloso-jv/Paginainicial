<?php
include 'conexao.php';
include 'proteger.php';

// Recebe parâmetros do filtro
$busca = $_GET['busca'] ?? '';
$data  = $_GET['data'] ?? '';

// Monta a query base
$query = "SELECT id, nome, telefone, email, data_horario FROM agendamentos";
$condicoes = [];
$params = [];
$tipos = "";

// Filtro de texto (nome, telefone, email)
if (!empty($busca)) {
    $condicoes[] = "(nome LIKE CONCAT('%', ?, '%') OR telefone LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%'))";
    $params[] = $busca;
    $params[] = $busca;
    $params[] = $busca;
    $tipos .= "sss";
}

// Filtro de data
if (!empty($data)) {
    $condicoes[] = "DATE(data_horario) = ?";
    $params[] = $data;
    $tipos .= "s";
}

// Aplica filtros se existirem
if (!empty($condicoes)) {
    $query .= " WHERE " . implode(" AND ", $condicoes);
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Consultar Agendamentos</title>
<link rel="stylesheet" href="styhome.css">
</head>
<body>
<center>

<h1 class="agendamentoh1">Agendamentos</h1>

<!-- Filtro -->
<div class="form-container" style="width:400px; margin-bottom:20px;">
<form method="GET" action="consultar.php" style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
    <input type="text" name="busca" placeholder="Buscar nome, telefone ou email"
           value="<?= htmlspecialchars($busca) ?>" class="input-field">

    <input type="date" name="data" value="<?= htmlspecialchars($data) ?>"
           class="input-field">

    <button type="submit" class="btn">Filtrar</button>
</form>
</div>

<!-- Tabela -->
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>Data e Horário</th>
        <th>Ações</th> <!-- COLUNA DOS BOTÕES -->
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nome']) ?></td>
        <td><?= htmlspecialchars($row['telefone']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['data_horario']) ?></td>

        <!-- BOTÕES DE EDIÇÃO E EXCLUSÃO -->
        <td>
            <a href="editar_agendamento.php?id=<?= $row['id'] ?>" class="btn-editar">Editar</a>
            <a href="excluir_agendamento.php?id=<?= $row['id'] ?>" class="btn-excluir"
               onclick="return confirm('Deseja realmente excluir este agendamento?')">
               Excluir
            </a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</center>
</body>
</html>
