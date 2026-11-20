
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';
require_once __DIR__ . '/src/Services/CalculadoraFrete.php';
require_once __DIR__ . '/src/Services/RelatorioPedidos.php';

// Cliente.
$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Rua das Empresas, 100",
    "Corporativo"
);

// Pedido.
$pedido = new PedidoFrete(
    $cliente,
    "Hospital Central",
    120
);

// Volumes.
$pedido->adicionarVolume(
    new Volume("Eletronicos", 15, "carga frágil")
);

$pedido->adicionarVolume(
    new Volume("Mercadorias gerais", 25, "carga geral")
);

$pedido->adicionarVolume(
    new Volume("Medicamentos", 10, "carga refrigerada")
);

// Veículo.
$veiculo = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

// Alocação e histórico.
$pedido->alocarVeiculo($veiculo);

$pedido->atualizarStatus(
    "Em separacao",
    "Encomenda preparada para transporte."
);

$pedido->atualizarStatus(
    "Saiu para entrega",
    "Veiculo iniciou o transporte."
);

$pedido->atualizarStatus(
    "Entregue",
    "Encomenda entregue ao destinatario."
);

// Gerar relatório.
$pedidos = [$pedido];

$relatorio = new RelatorioPedidos();

$relatorio->gerarRelatorio(
    $pedidos,
    "VC-1001"
);
