<?php
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}
validarCsrf();
$_SESSION = [];
session_regenerate_id(true);
header('Location: /');
exit;
