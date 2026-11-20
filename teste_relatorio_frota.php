
<?php

require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Services/RelatorioFrota.php';

// Criando os tres veiculos da frota.
$motocicleta = new Motocicleta(
    "ABC1234",
    "Honda CG 160",
    2023,
    20,
    160,
    true,
    2.50
);

$utilitario = new Utilitario(
    "DEF5678",
    "Fiat Fiorino",
    2022,
    1000,
    3.5,
    true,
    4.50
);

$caminhao = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

// Colocando os veiculos em um unico array.
$frota = [
    $motocicleta,
    $utilitario,
    $caminhao
];

// Simulando um veiculo em rota.
$motocicleta->reservar();

// Gerando o relatorio.
$relatorio = new RelatorioFrota();

$relatorio->gerarRelatorio($frota);

