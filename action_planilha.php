<?php
header("Content-Type: application/json; charset=UTF-8");

$arquivo = "cadastros.json";

// cria o arquivo se não existir
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, "[]");
}

// carrega os dados existentes
$cadastros = json_decode(file_get_contents($arquivo), true);

// garante que seja uma lista válida
if (!is_array($cadastros)) {
    $cadastros = [];
}

$action = $_GET["action"] ?? $_POST["action"] ?? "";

/* ============================================================
   1) LISTAR CADASTROS
   ============================================================ */
if ($action === "listar") {
    echo json_encode($cadastros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/* ============================================================
   2) CADASTRAR NOVO USUÁRIO
   ============================================================ */
if ($action === "cadastrar" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = htmlspecialchars($_POST["nome"] ?? "");
    $telefone = htmlspecialchars($_POST["telefone"] ?? "");

    if (!$nome || !$telefone) {
        echo json_encode(["erro" => "Dados incompletos."], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // adiciona ao "banco"
    $cadastros[] = [
        "nome" => $nome,
        "telefone" => $telefone
    ];

    file_put_contents($arquivo, json_encode($cadastros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    echo json_encode(["status" => "ok", "mensagem" => "Cadastro salvo."], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ============================================================
   3) LOGIN (VERIFICA SE O NOME EXISTE)
   ============================================================ */
if ($action === "login" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = strtolower(trim($_POST["nome"] ?? ""));

    if (!$nome) {
        echo json_encode(["erro" => "Nome não enviado."], JSON_UNESCAPED_UNICODE);
        exit;
    }

    foreach ($cadastros as $c) {
        if (strtolower($c["nome"]) === $nome) {
            echo json_encode([
                "status" => "ok",
                "mensagem" => "Login bem-sucedido.",
                "usuario" => $c
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    echo json_encode(["erro" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ============================================================
   4) CHAMADA INVÁLIDA
   ============================================================ */
echo json_encode(["erro" => "Ação inválida."], JSON_UNESCAPED_UNICODE);
exit;
?>
