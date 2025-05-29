<?php
/**
 * API para envio de mensagens de contato
 * Processa o formulário de contato da página Sobre
 */

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método não permitido'
    ]);
    exit;
}

// Obtém os dados do formulário
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Valida os dados
$errors = [];

if (empty($name)) {
    $errors[] = 'Nome é obrigatório';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email inválido';
}

if (empty($subject)) {
    $errors[] = 'Assunto é obrigatório';
}

if (empty($message)) {
    $errors[] = 'Mensagem é obrigatória';
}

// Se houver erros, retorna-os
if (!empty($errors)) {
    echo json_encode([
        'status' => 'error',
        'errors' => $errors
    ]);
    exit;
}

// Em um ambiente de produção, aqui seria feito o envio do email
// Usando funções como mail() ou bibliotecas como PHPMailer

// Simula o envio bem-sucedido
$response = [
    'status' => 'success',
    'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
    'data' => [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ]
];

// Registra a mensagem em um arquivo de log (opcional)
$log_dir = __DIR__ . '/../logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

$log_file = $log_dir . '/contact_' . date('Y-m-d') . '.log';
$log_entry = date('Y-m-d H:i:s') . " | {$name} | {$email} | {$subject}\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Retorna a resposta
echo json_encode($response);
?>
