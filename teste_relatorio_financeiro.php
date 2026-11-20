
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';
require_once __DIR__ . '/src/Services/CalculadoraFrete.php';
require_once __DIR__ . '/src/Services/RelatorioFinanceiro.php';

// Cliente corporativo.
$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Rua das Empresas, 100",
    "Corporativo"
);

// Criar pedido.
$pedido = new PedidoFrete(
    $cliente,
    "Hospital Central",
    120
);

// Adicionar volumes.
$pedido->adicionarVolume(
    new Volume("Eletronicos", 15, "carga frágil")
);

$pedido->adicionarVolume(
    new Volume("Mercadorias gerais", 25, "carga geral")
);

$pedido->adicionarVolume(
    new Volume("Medicamentos", 10, "carga refrigerada")
);

// Criar veículo.
$veiculo = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

// Alocar veículo.
$pedido->alocarVeiculo($veiculo);

// Criar lista de pedidos.
$pedidos = [$pedido];

// Gerar relatório financeiro.
$relatorio = new RelatorioFinanceiro();

$relatorio->gerarRelatorio($pedidos);
