<?php
/**
 * actions/usuario_cadastrar.php
 * Processa o cadastro de um novo usuário.
 * * Requisitos de Inclusão:
 * - includes/auth_check.php: Para verificar o login e o nível de acesso (Admin).
 * - app/dao/UsuarioDAO.php: Para interagir com o banco de dados.
 */

// Inclui o cabeçalho e a verificação de autenticação.
// Caminho: actions/ -> ../ -> includes/
require_once __DIR__ . '/../includes/auth_check.php';

// Verifica se o usuário é administrador (apenas admin pode cadastrar/editar outros usuários)
if ($_SESSION['user_nivel'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado. Apenas administradores podem cadastrar usuários.";
    header("Location: ../public/usuarios/listar.php");
    exit();
}

// Inclui o DAO.
// Caminho: actions/ -> ../ -> app/dao/UsuarioDAO.php
require_once __DIR__ . '/../app/dao/UsuarioDAO.php';

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Coleta e sanitiza os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);
    $confirmar_senha = filter_input(INPUT_POST, 'confirmar_senha', FILTER_DEFAULT);
    $nivel_acesso = filter_input(INPUT_POST, 'nivel_acesso', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validação básica (campos vazios)
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha) || empty($nivel_acesso)) {
        $_SESSION['msg_erro'] = "Todos os campos obrigatórios devem ser preenchidos.";
        header("Location: ../public/usuarios/cadastrar.php");
        exit();
    }

    // Validação de senhas
    if ($senha !== $confirmar_senha) {
        $_SESSION['msg_erro'] = "As senhas digitadas não coincidem.";
        header("Location: ../public/usuarios/cadastrar.php");
        exit();
    }

    // Hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Instancia o DAO e verifica duplicidade de e-mail
    $usuarioDAO = new UsuarioDAO();

    if ($usuarioDAO->emailExiste($email)) {
        $_SESSION['msg_erro'] = "Este e-mail já está cadastrado no sistema.";
        header("Location: ../public/usuarios/cadastrar.php");
        exit();
    }

    // Tenta cadastrar o usuário
    if ($usuarioDAO->cadastrar($nome, $email, $senhaHash, $nivel_acesso)) {
        $_SESSION['msg_sucesso'] = "Usuário '$nome' cadastrado com sucesso!";
        header("Location: ../public/usuarios/listar.php");
    } else {
        $_SESSION['msg_erro'] = "Erro ao cadastrar o usuário no banco de dados.";
        header("Location: ../public/usuarios/cadastrar.php");
    }
    exit();

} else {
    // Se não for POST, redireciona para a página de listagem.
    header("Location: ../public/usuarios/listar.php");
    exit();
}
