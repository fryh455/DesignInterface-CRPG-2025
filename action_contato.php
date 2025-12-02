<?php
header("Content-Type: text/html; charset=UTF-8");

function salvarMensagem($dados) {
    $arquivo = "mensagens.json";

    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, "[]");
    }

    $lista = json_decode(file_get_contents($arquivo), true);
    $lista[] = $dados;

    file_put_contents($arquivo, json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = htmlspecialchars($_POST["nome"] ?? "");
    $email = htmlspecialchars($_POST["email"] ?? "");
    $mensagem = htmlspecialchars($_POST["mensagem"] ?? "");
    $data = date("Y-m-d H:i:s");

    salvarMensagem([
        "nome" => $nome,
        "email" => $email,
        "mensagem" => $mensagem,
        "data" => $data
    ]);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Dados Recebidos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <h2>Cyber Vault</h2>
    <nav>
        <a href="index.html">Início</a>
        <a href="contatos.html">Contato</a>
        <a href="planilha.html">Planilha</a>
    </nav>
</header>

<main>
    <h1>Dados Recebidos</h1>
    <div class="contato-form">
        <?php if ($_SERVER["REQUEST_METHOD"] === "POST") : ?>

            <p><strong>Nome:</strong> <?= $nome ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Mensagem:</strong> <?= nl2br($mensagem) ?></p>
            <p><strong>Registrado em:</strong> <?= $data ?></p>

            <p style="color: #0f0;">Mensagem salva com sucesso no banco local.</p>

        <?php else : ?>
            <p>Nenhum dado foi enviado.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 Cyber Vault</p>
</footer>
</body>
</html>
