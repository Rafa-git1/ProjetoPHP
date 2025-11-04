🧭 Sistema de Gestão em PHP
📘 Descrição Geral

Este projeto é um Sistema de Gestão Web desenvolvido em PHP com MySQL, utilizando o paradigma de Programação Orientada a Objetos (POO).

O sistema permite gerenciar usuários, categorias e produtos, com controle de permissões de acesso.
Usuários com perfil Super Admin têm acesso completo (inclusive a categorias e usuários), enquanto usuários comuns visualizam e gerenciam apenas produtos.

🧩 Funcionalidades Principais
👥 Usuários

CRUD completo (criar, listar, editar, excluir).

Login e autenticação por sessão.

Perfis de acesso:

Super Admin: pode gerenciar usuários, categorias e produtos.

Usuário comum: pode visualizar e manipular apenas produtos.

🗂️ Categorias

Cadastro de categorias para organizar os produtos.

CRUD funcional (adicionar, editar, excluir e listar).

Acesso restrito ao Super Admin.

📦 Produtos

Cadastro de produtos vinculados a uma categoria existente.

CRUD funcional completo.

Todos os usuários podem visualizar e gerenciar produtos (de acordo com suas permissões).

⚙️ Instalação e Configuração
1️⃣ Pré-requisitos

PHP 8 ou superior

MySQL

Servidor local (ex: XAMPP)

2️⃣ Clonar o projeto
git clone https://github.com/seuusuario/ProjetoPHP.git

3️⃣ Configurar o banco de dados

Crie um banco de dados no MySQL, por exemplo:

CREATE DATABASE sistema_gestao;


Importe o arquivo SQL ou crie as tabelas conforme os modelos:

usuarios

categorias

produtos

Ajuste o arquivo de conexão (app/config/database.php):

$host = 'localhost';
$dbname = 'sistema_gestao';
$username = 'root';
$password = '';

4️⃣ Executar o projeto

No navegador, acesse:

http://localhost/ProjetoPHP/public/login.php


O sistema redirecionará automaticamente para a tela de login.

🔐 Credenciais de Acesso (exemplo)
Tipo de Usuário	Usuário	Senha
Super Admin	admin	admin123
Usuário comum	usuario	user123

(Essas credenciais podem variar conforme seu banco de dados.)

🧱 Funcionalidades Técnicas

Programação Orientada a Objetos (POO)

CRUD completo para todas as entidades

Controle de sessão e autenticação

Validação de permissões (acesso condicional)

Separação de camadas: Modelo, Controle, Visão

Estrutura modular, fácil de manter e expandir

🎨 Estilo Visual

O sistema utiliza CSS personalizado, localizado em:

/public/assets/css/style.css

Recurso extra:

- O site conta com um sistema de filtros. Em cada tela de lista, é possível fazer um filtro conforme a necessidade do usuário. O sistema irá exibir a queryset de acordo com os parâmetros passados no filtro pelo usuário

🧩 Possíveis Melhorias Futuras

Implementar upload de imagens para produtos

Adicionar paginação nas listagens

Criar logs de atividade de usuários

Implementar API REST para integração externa

👨‍💻 Autores:

R044939 - Rafael José Carvalho dos Santos
G9988H5 - Pedro Di Bonito Balconi
G99CHB1- Marcelo Cristiano da Luz Junior
R103650 - Luciano Santiago de Araujo Júnior
R0526D3 - Guilherme Lucas Bonfim
