<?php
$file = 'users.csv';

// Recebe POST
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Cria CSV se não existir
if(!file_exists($file)) {
    $f = fopen($file, 'w');
    fputcsv($f, ['name','phone','email','password']); // cabeçalho
    fclose($f);
}

// Verifica se email já existe
$rows = array_map('str_getcsv', file($file));
foreach($rows as $row) {
    if(isset($row[2]) && $row[2] === $email) {
        die("Email já cadastrado!");
    }
}

// Salva dados
$f = fopen($file, 'a');
fputcsv($f, [$name,$phone,$email,$password]);
fclose($f);

echo "Cadastro realizado com sucesso!";
?>
