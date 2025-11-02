<form action="../../actions/usuario_cadastrar.php" method="POST">
    <!-- ... campos do formulário ... -->
</form>
```

### Explicação do caminho: `../../actions/usuario_cadastrar.php`

O caminho é relativo à localização do arquivo que contém o formulário: `public/usuarios/cadastrar.php`.

1.  **`../`**: Sobe um nível, saindo de `usuarios/` para `public/`.
2.  **`../../`**: Sobe mais um nível, saindo de `public/` para a **raiz do seu projeto** (`ProjetoPHP/`).
3.  **`../../actions/`**: Entra na pasta `actions/` na raiz do projeto.
4.  **`../../actions/usuario_cadastrar.php`**: Aponta para o arquivo de processamento correto dentro da pasta `actions/`.

Você pode ver isso aplicado no código que eu gerei para você. Por exemplo, no arquivo **`public/usuarios/cadastrar.php`** (que não está no Canvas agora, mas foi gerado):

```php
// Trecho de public/usuarios/cadastrar.php
// ...
<form action="../../actions/usuario_cadastrar.php" method="POST">
// ...
```

E no arquivo **`public/usuarios/editar.php`** (que está no Canvas como "Edição de Usuário"):

```php
// Trecho de public/usuarios/editar.php
// ...
<form action="../../actions/usuario_editar.php" method="POST">
// ...
