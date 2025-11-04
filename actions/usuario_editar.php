<?php
/**
 * Processa o formulário de edição de usuário.
 */
session_start();

// Caminho corrigido para dois níveis acima
require_once __DIR__ . '/../app/dao/UsuarioDAO.php';

// Checagem de Nível de Acesso (APENAS ADMIN PODE ACESSAR)
if ($_SESSION['user_nivel'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado.";
    header("Location: ../public/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/usuarios/listar.php");
    exit();
}

// Coleta e sanitização de dados
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$nivel_acesso = filter_input(INPUT_POST, 'nivel_acesso', FILTER_SANITIZE_SPECIAL_CHARS);
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Armazena dados do formulário na sessão para preenchimento em caso de erro
$_SESSION['form_data'] = ['id' => $id, 'nome' => $nome, 'email' => $email, 'nivel_acesso' => $nivel_acesso];

// Validação básica
if (!$id || !$nome || !$email || !$nivel_acesso || ($nivel_acesso !== 'admin' && $nivel_acesso !== 'user')) {
    $_SESSION['msg_erro'] = "Dados inválidos ou incompletos.";
    header("Location: ../public/usuarios/editar.php?id=$id");
    exit();
}

$usuarioDAO = new UsuarioDAO();

// Validação de e-mail (checa se o novo e-mail já existe para outro ID)
if ($usuarioDAO->emailExiste($email, $id)) {
    $_SESSION['msg_erro'] = "Este e-mail já está em uso por outro usuário.";
    header("Location: ../public/usuarios/editar.php?id=$id");
    exit();
}

// Processamento da Edição de Dados (Nome, Email, Nível)
if ($usuarioDAO->atualizar($id, $nome, $email, $nivel_acesso)) {
    $_SESSION['msg_sucesso'] = "Usuário '$nome' atualizado com sucesso!";
} else {
    $_SESSION['msg_erro'] = "Nenhuma alteração nos dados do usuário foi detectada ou ocorreu um erro.";
}

// Processamento da Senha (Se fornecida)
if (!empty($nova_senha)) {
    if ($nova_senha !== $confirmar_senha) {
        $_SESSION['msg_erro'] = "A nova senha e a confirmação de senha não coincidem. Os outros dados foram salvos.";
        header("Location: ../public/usuarios/editar.php?id=$id");
        exit();
    }
    
    $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
    
    if ($usuarioDAO->atualizarSenha($id, $senhaHash)) {
        $_SESSION['msg_sucesso'] .= " A senha também foi alterada com sucesso!";
    } else {
        $_SESSION['msg_erro'] = "Erro ao alterar a senha.";
    }
}

// sLimpa dados de formulário e redireciona
unset($_SESSION['form_data']);
header("Location: ../public/usuarios/listar.php");
exit();
