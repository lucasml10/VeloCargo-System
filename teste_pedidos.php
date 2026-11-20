
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';

echo "====================================\n";
echo " TESTE DE ALOCACAO DE VEICULOS\n";
echo "====================================\n\n";

$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Avenida Brasil, 500",
    "Corporativo"
);

// Criando os veículos.
$moto = new Motocicleta(
    "ABC1234", "Honda CG 160", 2024, 20, 160, true, 2.50
);

$utilitario = new Utilitario(
    "DEF5678", "Fiat Fiorino", 2023, 1000, 3.5, true, 4.50
);

$caminhao = new CaminhaoRefrigerado(
    "GHI9012", "Mercedes-Benz Atego", 2022, 5000, -10, true, 7.00
);

// Pedido com carga geral, frágil e refrigerada.
$pedido1 = new PedidoFrete($cliente, "Lucas Pereira", 120);

$pedido1->adicionarVolume(
    new Volume("Eletrônicos", 15, "carga frágil")
);

$pedido1->adicionarVolume(
    new Volume("Mercadorias", 25, "carga geral")
);

$pedido1->adicionarVolume(
    new Volume("Medicamentos", 10, "carga refrigerada")
);

echo "Peso total do pedido: " . $pedido1->getPesoTotal() . " kg\n\n";

// Testando motocicleta.
echo "===== TESTE COM MOTOCICLETA =====\n";

try {
    $pedido1->alocarVeiculo($moto);
    echo "Motocicleta alocada com sucesso.\n";
} catch (RuntimeException $erro) {
    echo "Alocação bloqueada: " . $erro->getMessage() . "\n";
}

// Testando utilitário.
echo "\n===== TESTE COM UTILITARIO =====\n";

try {
    $pedido1->alocarVeiculo($utilitario);
    echo "Utilitário alocado com sucesso.\n";
} catch (RuntimeException $erro) {
    echo "Alocação bloqueada: " . $erro->getMessage() . "\n";
}

// Testando caminhão refrigerado.
echo "\n===== TESTE COM CAMINHAO REFRIGERADO =====\n";

try {
    $pedido1->alocarVeiculo($caminhao);
    echo "Caminhão alocado com sucesso!\n";
    echo "Placa: " . $pedido1->getVeiculo()->getPlaca() . "\n";
} catch (RuntimeException $erro) {
    echo "Alocação bloqueada: " . $erro->getMessage() . "\n";
}

// Testando se o caminhão ocupado aceita outro pedido.
echo "\n===== TESTE DE DISPONIBILIDADE =====\n";

$pedido2 = new PedidoFrete($cliente, "Maria Oliveira", 50);

$pedido2->adicionarVolume(
    new Volume("Caixa de roupas", 10, "carga geral")
);

try {
    $pedido2->alocarVeiculo($caminhao);
    echo "Segundo pedido alocado no caminhão.\n";
} catch (RuntimeException $erro) {
    echo "Segundo pedido bloqueado: " . $erro->getMessage() . "\n";
}

// Testando pedido sem volumes.
echo "\n===== TESTE DE PEDIDO SEM VOLUMES =====\n";

$pedido3 = new PedidoFrete($cliente, "João Oliveira", 30);

try {
    $pedido3->alocarVeiculo($utilitario);
    echo "Veículo alocado.\n";
} catch (RuntimeException $erro) {
    echo "Alocação bloqueada: " . $erro->getMessage() . "\n";
}

echo "\n====================================\n";
echo "       TESTES FINALIZADOS\n";
echo "====================================\n";
