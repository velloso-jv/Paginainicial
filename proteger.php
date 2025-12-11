<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    // Redireciona para a página de login
    header("Location: login.php");
    exit(); // garante que nada mais será executado
}
?>
