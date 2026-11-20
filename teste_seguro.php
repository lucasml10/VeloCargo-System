
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';

echo "====================================\n";
echo "      TESTE DO SEGURO VELOCARGO\n";
echo "====================================\n\n";

$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Avenida Brasil, 500",
    "Corporativo"
);

$pedido = new PedidoFrete(
    $cliente,
    "Lucas Pereira",
    120
);

$pedido->adicionarVolume(
    new Volume("Eletrônicos", 15, "carga frágil")
);

$pedido->adicionarVolume(
    new Volume("Medicamentos", 10, "carga refrigerada")
);

$seguro = new SeguroFrete();

echo "===== VERIFICAÇÃO DO SEGURO =====\n";

if ($seguro->exigeSeguro($pedido)) {
    echo "Seguro obrigatório: SIM\n";

    // Valor ilustrativo utilizado apenas para testar o seguro.
    $valorBase = 1052.48;

    $premio = $seguro->calcularPremio($valorBase);
    $apolice = $seguro->emitirApolice();

    echo "Valor base: R$ "
        . number_format($valorBase, 2, ',', '.') . "\n";

    echo "Taxa do seguro: "
        . ($seguro->getTaxaPremio() * 100) . "%\n";

    echo "Prêmio do seguro: R$ "
        . number_format($premio, 2, ',', '.') . "\n";

    echo "Número da apólice: " . $apolice . "\n";
} else {
    echo "Seguro obrigatório: NÃO\n";
}

echo "\n====================================\n";
echo "       TESTES FINALIZADOS\n";
echo "====================================\n";
