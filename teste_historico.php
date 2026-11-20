<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';

$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Rua das Empresas, 100",
    "Corporativo"
);

$pedido = new PedidoFrete(
    $cliente,
    "Hospital Central",
    120
);

$pedido->adicionarVolume(
    new Volume("Medicamentos", 10, "carga refrigerada")
);

$veiculo = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

$pedido->alocarVeiculo($veiculo);

$pedido->atualizarStatus(
    "Em separacao",
    "Encomenda preparada para transporte."
);

$pedido->atualizarStatus(
    "Saiu para entrega",
    "O veículo iniciou o transporte da encomenda."
);

$pedido->atualizarStatus(
    "Entregue",
    "Encomenda entregue ao destinatário."
);

echo "====================================\n";
echo " HISTORICO DO PEDIDO VELOCARGO\n";
echo "====================================\n\n";

echo "Rastreamento: " .
    $pedido->getCodigoRastreamento() . "\n";

echo "Status atual: " . $pedido->getStatus() . "\n\n";

foreach ($pedido->getHistorico() as $evento) {
    echo "Data e hora: " . $evento['dataHora'] . "\n";
    echo "Status: " . $evento['status'] . "\n";
    echo "Descricao: " . $evento['descricao'] . "\n";
    echo "------------------------------------\n";
}