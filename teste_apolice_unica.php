
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';
require_once __DIR__ . '/src/Services/CalculadoraFrete.php';

// Criar cliente.
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

// Criar calculadora.
$calculadora = new CalculadoraFrete();

// Primeiro cálculo.
$resultado1 = $calculadora->calcular($pedido);

// Segundo cálculo do mesmo pedido.
$resultado2 = $calculadora->calcular($pedido);

echo "========================================\n";
echo " TESTE DE APOLICE UNICA\n";
echo "========================================\n\n";

echo "PRIMEIRO CALCULO\n";
echo "Apolice: " . $resultado1['numeroApolice'] . "\n";
echo "Seguro: R$ " .
    number_format($resultado1['premioSeguro'], 2, ',', '.') . "\n";

echo "\nSEGUNDO CALCULO\n";
echo "Apolice: " . $resultado2['numeroApolice'] . "\n";
echo "Seguro: R$ " .
    number_format($resultado2['premioSeguro'], 2, ',', '.') . "\n";

echo "\nRESULTADO DO TESTE\n";

if (
    $resultado1['numeroApolice'] === $resultado2['numeroApolice']
    && $resultado1['premioSeguro'] === $resultado2['premioSeguro']
) {
    echo "APROVADO: A apolice foi reutilizada.\n";
} else {
    echo "REPROVADO: Foram gerados dados diferentes.\n";
}

echo "========================================\n";
