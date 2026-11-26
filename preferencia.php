<?php
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}
validarCsrf();
$tema = (string) ($_POST['tema'] ?? '');
if (!in_array($tema, ['claro', 'escuro'], true)) {
    http_response_code(400);
    exit('Preferência de tema inválida.');
}
setcookie('futnex_tema', $tema, [
    'expires' => time() + 60 * 60 * 24 * 365,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
header('Location: /');
exit;
