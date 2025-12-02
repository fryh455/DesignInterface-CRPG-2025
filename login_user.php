<?php
$file = 'users.csv';

$email = $_POST['email'];
$password = $_POST['password'];

if(!file_exists($file)) {
    die("Nenhum usuário cadastrado!");
}

$rows = array_map('str_getcsv', file($file));
$found = false;

foreach($rows as $row) {
    if(isset($row[2]) && $row[2] === $email) {
        if(password_verify($password, $row[3])) {
            $found = true;
            break;
        }
    }
}

if($found) {
    echo "Login bem-sucedido!";
} else {
    echo "Email ou senha incorretos!";
}
?>
