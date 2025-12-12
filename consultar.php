<?php
include "proteger.php";
include "conexao.php";

// FILTROS
$filtroNome = $_GET['nome'] ?? '';
$filtroData = $_GET['data'] ?? '';

$query = "SELECT id, nome, telefone, email, data_horario FROM agendamentos WHERE 1=1";

// Filtro por nome
if (!empty($filtroNome)) {
    $query .= " AND nome LIKE '%" . $conn->real_escape_string($filtroNome) . "%'";
}

// Filtro por data
if (!empty($filtroData)) {
    $query .= " AND DATE(data_horario) = '" . $conn->real_escape_string($filtroData) . "'";
}

$query .= " ORDER BY data_horario ASC";

$result = $conn->query($query);
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

<div class="form-container" style="width:420px;">
    <form method="GET" action="consultar.php">

        <label>Filtrar por nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($filtroNome) ?>">

        <label>Filtrar por data:</label>
        <input type="date" name="data" value="<?= htmlspecialchars($filtroData) ?>">

        <button type="submit">Filtrar</button>
    </form>
</div>

<br>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>Nome</th>
    <th>Telefone</th>
    <th>Email</th>
    <th>Data/Horário</th>
    <th>Ações</th>
</tr>

<?php if ($result->num_rows > 0): ?>
    <?php while ($ag = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($ag['nome']) ?></td>
            <td><?= htmlspecialchars($ag['telefone']) ?></td>
            <td><?= htmlspecialchars($ag['email']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($ag['data_horario'])) ?></td>

            <td>
                <!-- EDITAR -->
                <a href="agendamento_acao.php?acao=editar&id=<?= $ag['id'] ?>">Editar</a> |

                <!-- EXCLUIR -->
                <a href="agendamento_acao.php?acao=excluir&id=<?= $ag['id'] ?>"
                   onclick="return confirm('Deseja realmente excluir este agendamento?');">
                    Excluir
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

<?php else: ?>
    <tr>
        <td colspan="5">Nenhum agendamento encontrado.</td>
    </tr>
<?php endif; ?>
</table>

<br>
<a href="login.php">Sair</a>

</center>

</body>
</html>
