<?php
require "proteger.php"; // impede acesso sem login
require "conexao.php";

// --- FILTRO ---
$filtro = "";
$param = "";

if (!empty($_GET['buscar'])) {
    $buscar = "%" . $_GET['buscar'] . "%";
    $filtro = " WHERE nome LIKE ? OR telefone LIKE ? OR DATE(data_horario) LIKE ? ";
    $param = $buscar;
}

// --- CONSULTA COM OU SEM FILTRO ---
$sql = "SELECT * FROM agendamentos $filtro ORDER BY data_horario ASC";
$stmt = $mysqli->prepare($sql);

if ($filtro !== "") {
    $stmt->bind_param("sss", $param, $param, $param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel de Agendamentos</title>
<style>
body { font-family: Arial; padding: 30px; background:#f5f5f5; }
h1 { color:#333; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { border:1px solid #aaa; padding:10px; text-align:left; }
th { background:#ddd; }
a.botao { padding:6px 10px; background:#444; color:white; text-decoration:none; border-radius:5px; }
a.excluir { background:#a90000; }
form { margin-bottom:20px; }
input[type=text] { padding:8px; width:250px; }
button { padding:8px 15px; background:#333; color:white; border:none; cursor:pointer; }
</style>
</head>

<body>

<h1>Painel de Agendamentos</h1>

<form method="GET">
    <input type="text" name="buscar" placeholder="Buscar por nome, telefone ou data...">
    <button type="submit">Buscar</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Telefone</th>
    <th>Email</th>
    <th>Data / Horário</th>
    <th>Ações</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nome'] ?></td>
    <td><?= $row['telefone'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= date("d/m/Y H:i", strtotime($row['data_horario'])) ?></td>
    <td>
        <a class="botao" href="editar.php?id=<?= $row['id'] ?>">Editar</a>
        <a class="botao excluir" 
           href="excluir.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
