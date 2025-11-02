<?php
$senha_clara = '123456'; // A senha que queremos usar
$hash_correto = password_hash($senha_clara, PASSWORD_DEFAULT);

echo "Senha Clara: " . $senha_clara . "<br>";
echo "Hash Correto para o Banco: " . $hash_correto . "<br>";
?>