
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';

$cliente = new Cliente(
    "Cliente Teste",
    "12345678901",
    "Rua Teste, 100",
    "Comum"
);

// =====================================
// TESTE 1 - MOTOCICLETA COM CARGA REFRIGERADA
// =====================================

echo "TESTE 1 - Motocicleta com carga refrigerada:\n";

$moto = new Motocicleta(
    "ABC1234",
    "Honda CG 160",
    2023,
    20,
    160,
    true,
    2.50
);

$pedido1 = new PedidoFrete(
    $cliente,
    "Loja Central",
    10
);

$pedido1->adicionarVolume(
    new Volume(
        "Medicamentos",
        10,
        "carga refrigerada"
    )
);

try {
    $pedido1->alocarVeiculo($moto);

    echo "FALHA: alocacao indevida.\n";

} catch (RuntimeException $e) {

    echo "BLOQUEADO CORRETAMENTE: " .
        $e->getMessage() . "\n";
}

// =====================================
// TESTE 2 - UTILITARIO COM CARGA GERAL
// =====================================

echo "\nTESTE 2 - Utilitario com carga geral:\n";

$utilitario = new Utilitario(
    "DEF5678",
    "Fiat Fiorino",
    2022,
    1000,
    3.5,
    true,
    4.50
);

$pedido2 = new PedidoFrete(
    $cliente,
    "Loja Central",
    20
);

$pedido2->adicionarVolume(
    new Volume(
        "Caixas",
        100,
        "carga geral"
    )
);

try {
    $pedido2->alocarVeiculo($utilitario);

    echo "ALOCACAO ACEITA CORRETAMENTE.\n";

} catch (RuntimeException $e) {

    echo "FALHA: " . $e->getMessage() . "\n";
}

// =====================================
// TESTE 3 - CAMINHAO COM CERTIFICADO
// =====================================

echo "\nTESTE 3 - Caminhao com carga refrigerada:\n";

$caminhao = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

$pedido3 = new PedidoFrete(
    $cliente,
    "Hospital Central",
    50
);

$pedido3->adicionarVolume(
    new Volume(
        "Medicamentos",
        100,
        "carga refrigerada"
    )
);

try {
    $pedido3->alocarVeiculo($caminhao);

    echo "ALOCACAO ACEITA CORRETAMENTE.\n";

} catch (RuntimeException $e) {

    echo "FALHA: " . $e->getMessage() . "\n";
}

// =====================================
// TESTE 4 - CAMINHAO SEM CERTIFICADO
// =====================================

echo "\nTESTE 4 - Caminhao sem certificado sanitario:\n";

$caminhaoSemCertificado = new CaminhaoRefrigerado(
    "JKL3456",
    "Volvo VM",
    2023,
    5000,
    -15,
    false,
    7.50
);

$pedido4 = new PedidoFrete(
    $cliente,
    "Hospital Central",
    50
);

$pedido4->adicionarVolume(
    new Volume(
        "Medicamentos",
        100,
        "carga refrigerada"
    )
);

try {
    $pedido4->alocarVeiculo($caminhaoSemCertificado);

    echo "FALHA: alocacao indevida.\n";

} catch (RuntimeException $e) {

    echo "BLOQUEADO CORRETAMENTE: " .
        $e->getMessage() . "\n";
}

// =====================================
// FIM DOS TESTES
// =====================================

echo "\n=====================================\n";
echo "FIM DOS TESTES DE ALOCACAO\n";
echo "=====================================\n";
