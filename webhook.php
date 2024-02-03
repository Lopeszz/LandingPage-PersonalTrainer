<?php
$json = file_get_contents('php://input'); // Recebe o Json
$arr = json_decode($json, true); // Transforma para Array

// Nutre as variáveis com os valores de identificação
$event = $arr['event'];

// No caso de Pagamento Recebido...
if ($event == "PAYMENT_RECEIVED") {
    // Redireciona o cliente para a página desejada
    header('Location: ' . 'index.html');
}