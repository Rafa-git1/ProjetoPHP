<?php
/**
 * header.php
 * Contém o cabeçalho HTML, a inclusão de CSS, a barra de navegação
 * e a lógica para exibir mensagens de feedback da sessão.
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// ** CRÍTICO: BASE URL CORRIGIDA PARA CAMINHO ABSOLUTO **
// Use esta variável para todos os links internos para evitar a duplicação de 'produtos/'
// Se o nome da sua pasta for diferente de ProjetoPHP, ajuste aqui!
$base_url = '/ProjetoPHP/public/';

// Usa $pageTitle se definido na página, ou usa um fallback
$title = $pageTitle ?? 'Sistema CRUD';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?> - Projeto PHP</title>
    <!-- Importa o CSS centralizado que contém o estilo de todo o sistema -->
    <link rel="stylesheet" href="<?= $base_url; ?>assets/css/style.css">
</head>
<body>
    <header class="app-header">
        <nav class="main-nav">
            <!-- Todos os links corrigidos para usar $base_url para serem absolutos -->
            <a href="<?= $base_url; ?>dashboard.php">Dashboard</a>
            <a href="<?= $base_url; ?>produtos/listar.php">Produtos</a>

            <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'admin'): ?>
                <a href="<?= $base_url; ?>usuarios/listar.php">Usuários</a>
            <?php endif; ?>
        </nav>
        <div class="user-info">
            <span>Olá, **<?= isset($_SESSION['user_nome']) ? htmlspecialchars($_SESSION['user_nome']) : 'Visitante'; ?>**</span>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <!-- Link de Logout corrigido para usar $base_url -->
                <a href="<?= $base_url; ?>logout.php" class="button button-logout">Sair</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <!-- Main content area -->
        <main class="content-area">
            <?php 
            // Exibe e limpa mensagens de feedback (Sucesso e Erro)
            // Estilos CSS substituem os estilos inline.
            if (isset($_SESSION['msg_sucesso'])): ?>
                <div class="message message-success">
                    <?= htmlspecialchars($_SESSION['msg_sucesso']); 
                    unset($_SESSION['msg_sucesso']); ?>
                </div>
            <?php endif; 

            if (isset($_SESSION['msg_erro'])): ?>
                <div class="message message-error">
                    <?= htmlspecialchars($_SESSION['msg_erro']); 
                    unset($_SESSION['msg_erro']); ?>
                </div>
            <?php endif; ?>
            
            <!-- O restante do conteúdo da página será injetado aqui -->
