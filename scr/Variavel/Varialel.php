<?php
$token='api_token=1UG45NSvGj4fL96CCnlrcQgMw3agTbMENfOdftrAfq9MsutTb3fc4vO8sR8m';

$dns='https://sousaandrade.pcmsolucoes.com.br/backend/api/v1/reports/queue/statistics?';

$data_atual = date('Y-m-d');
// echo $data_atual;

$data_anterior = date('Y-m-d', strtotime('-1 day'));
// echo $data_anterior;
$hora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

$queues = [
    'Relacionamento Cliente' => '6a85b6a6fdc94466e9079590',
    'Repasse' => '6a85b6b020978a047d6de8f5',
    'Cobrança' => '6a85b6bfead62e45120ffb50',
    'Personalização' => '6a85b6cb20978a047d6de8f7',
    'Recepção' => '6a85b6d9ead62e45120ffb52',
    'Transbordo' => '6a90931af4602d22dd584a49',
];

$arrayFp = [
    [
        'Dia e Hora',
        'Fila',
        'Origem',
        'Status',
        'Tempo De Chamada',
        'Tempo De Atendimento',
        'Tempo De Espera',
        'Atendido Por ',
    ],
];

$config = [
    'host'      => 'smtp.gmail.com',
    'username'  => 'diogo@pcmsolucoes.com.br',
    'password'  => 'agcc yhvm wgvh dgrz',   // senha de app
    'port'      => 587,
    'fromEmail' => 'diogo@pcmsolucoes.com.br',
    'fromName'  => 'Programada',
    'debug'     => false, // true apenas para testar
];


