<?php
/* ============================================================
   acoes.php — processa as ações do CRUD
   Aula 1: gatilho "criar" (Create)
   Aula 2: entra o gatilho "excluir"
   Aula 3: entra o gatilho "atualizar"
   ============================================================ */

require 'config/conexao.php';

// Só aceitamos requisições POST aqui.
// Se alguém abrir acoes.php direto na URL (GET), devolvemos 405.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // 405 Method Not Allowed
    die('Método não permitido.');
}

$acao = $_POST['acao'] ?? '';

/* ---------------------------- CREATE ---------------------------- */
if ($acao === 'criar') {

    // 1) Receber os dados (as chaves são os atributos "name" do formulário)
    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha           = $_POST['senha'] ?? '';

    // 2) Validar NO SERVIDOR. A validação em JavaScript pode ser
    //    contornada, então o PHP nunca confia só nela.
    $dataValida = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (
        mb_strlen($nome) < 3 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !$dataValida || $dataValida->format('Y-m-d') !== $data_nascimento ||
        $dataValida > new DateTime('today') ||
        strlen($senha) < 6
    ) {
        header('Location: usuario-criar.php?erro=campos');
        exit;
    }

    // 3) Criptografar a senha.
    //    NUNCA use md5($senha): é rápido demais, sem salt, e quebrado por
    //    Rainbow Tables. password_hash gera um salt aleatório a cada chamada,
    //    então a mesma senha produz hashes diferentes.
    //
    //    echo md5('123456');                      // sempre o mesmo valor
    //    echo password_hash('123456', PASSWORD_DEFAULT); // muda a cada execução
    //
    //    Para conferir no login (Aula 3 / Mini Loja): password_verify($digitada, $hash)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 4) INSERT com Prepared Statement (placeholders nomeados).
    //    Os valores NUNCA são concatenados no SQL: isso impede SQL Injection.
    try {
        $sql  = "INSERT INTO usuarios (nome, email, data_nascimento, senha)
                 VALUES (:nome, :email, :data_nascimento, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'            => $nome,
            'email'           => $email,
            'data_nascimento' => $data_nascimento,
            'senha'           => $senhaHash,
        ]);

        // 5) Redirecionar para a lista (padrão Post/Redirect/Get:
        //    evita reenviar o formulário se o usuário atualizar a página)
        header('Location: index.php?msg=criado');
        exit;

    } catch (PDOException $e) {
        // 23000 = violação de chave única (e-mail UNIQUE já existe)
        $erro = ($e->getCode() === '23000') ? 'email' : 'banco';
        header('Location: usuario-criar.php?erro=' . $erro);
        exit;
    }
}

/* ------------------- Ação desconhecida ------------------- */
http_response_code(400); // 400 Bad Request
die('Ação inválida.');
