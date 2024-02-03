<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$name = $_POST['name'];
$email = $_POST['email'];
$mobilePhone = $_POST['mobilePhone'];
$cpfCnpj = $_POST['cpfCnpj'];

// Lista todos os clientes
$response = $client->request('GET', 'https://sandbox.asaas.com/api/v3/customers', [
    'headers' => [
        'accept' => 'application/json',
        'access_token' => '',
    ],
]);

$customers = json_decode($response->getBody(), true);

// Verifica se o cliente já existe
foreach ($customers['data'] as $customer) {
    if ($customer['cpfCnpj'] == $cpfCnpj) {
        die('O cliente já está cadastrado');
    }
}

// Se o cliente não existir, cria um novo cliente
$response = $client->request('POST', 'https://sandbox.asaas.com/api/v3/customers', [
    'body' => json_encode([
        'name' => $name,
        'email' => $email,
        'mobilePhone' => $mobilePhone,
        'cpfCnpj' => $cpfCnpj
    ]),
    'headers' => [
        'accept' => 'application/json',
        'access_token' => '',
        'content-type' => 'application/json',
    ],
]);

$response = json_decode($response->getBody(), true);

$payment = $client->request('POST', 'https://sandbox.asaas.com/api/v3/payments', [
    'body' => json_encode([
        'customer' => $response['id'],
        'billingType' => 'PIX',
        'dueDate' => '2024-12-30',
        'value' => 49.90,
        'description' => 'Descrição da cobrança',
        'externalReference' => '123456', //configurar o id do cliente no meu sistemas
    ]),
    'headers' => [
        'accept' => 'application/json',
        'access_token' => '',
        'content-type' => 'application/json',
    ],
]);

$payment = json_decode($payment->getBody(), true);


header('Location: ' . $payment['invoiceUrl']);
