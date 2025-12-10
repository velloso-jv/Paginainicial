<?php
$server = "localhost";
$usuarioBD = "root";
$senhaBD = "";
$banco = "carvi_clinic";

$conn = new mysqli($server, $usuarioBD, $senhaBD, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
