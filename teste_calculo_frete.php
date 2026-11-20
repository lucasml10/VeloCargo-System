
<?php

require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';
require_once __DIR__ . '/src/Services/CalculadoraFrete.php';

echo "========================================\n";
echo " TESTE DE CALCULO DE FRETE VELOCARGO\n";
echo "========================================\n\n";

// 1. Criar cliente corporativo.
$cliente = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Rua das Empresas, 100",
    "Corporativo"
);

// 2. Criar volumes.
$volume1 = new Volume("Eletronicos", 15, "carga frágil");
$volume2 = new Volume("Mercadorias gerais", 25, "carga geral");
$volume3 = new Volume("Medicamentos", 10, "carga refrigerada");

// 3. Criar pedido.
$pedido = new PedidoFrete(
    $cliente,
    "Hospital Central",
    120
);

$pedido->adicionarVolume($volume1);
$pedido->adicionarVolume($volume2);
$pedido->adicionarVolume($volume3);

// 4. Criar caminhão refrigerado.
$veiculo = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

// 5. Alocar veículo ao pedido.
$pedido->alocarVeiculo($veiculo);

// 6. Calcular frete.
$calculadora = new CalculadoraFrete();
$resultado = $calculadora->calcular($pedido);

// 7. Exibir resultados.
echo "CLIENTE: " . $cliente->getNome() . "\n";
echo "TIPO: " . $cliente->getTipoCliente() . "\n";
echo "RASTREAMENTO: " . $pedido->getCodigoRastreamento() . "\n";
echo "VEICULO: " . $veiculo->getModelo() . "\n";
echo "DISTANCIA: " . $resultado['distancia'] . " km\n";
echo "PESO TOTAL: " . $resultado['peso'] . " kg\n";

echo "\n----------- CALCULO DO FRETE -----------\n";

echo "Custo de deslocamento: R$ " .
    number_format($resultado['custoDeslocamento'], 2, ',', '.') . "\n";

echo "Custo por peso: R$ " .
    number_format($resultado['custoPeso'], 2, ',', '.') . "\n";

echo "Custo base: R$ " .
    number_format($resultado['custoBase'], 2, ',', '.') . "\n";

echo "Adicional carga fragil: R$ " .
    number_format($resultado['adicionalFragil'], 2, ',', '.') . "\n";

echo "Adicional carga refrigerada: R$ " .
    number_format($resultado['adicionalRefrigerada'], 2, ',', '.') . "\n";

echo "Desconto corporativo: R$ " .
    number_format($resultado['desconto'], 2, ',', '.') . "\n";

echo "Valor do frete: R$ " .
    number_format($resultado['valorFrete'], 2, ',', '.') . "\n";

echo "\n----------- SEGURO -----------\n";

echo "Seguro obrigatorio: " .
    ($resultado['seguroObrigatorio'] ? "SIM" : "NAO") . "\n";

echo "Valor do seguro: R$ " .
    number_format($resultado['premioSeguro'], 2, ',', '.') . "\n";

if ($resultado['numeroApolice'] !== null) {
    echo "Numero da apolice: " . $resultado['numeroApolice'] . "\n";
}

echo "\n----------- TOTAL -----------\n";

echo "VALOR TOTAL A PAGAR: R$ " .
    number_format($resultado['valorFinal'], 2, ',', '.') . "\n";

echo "\n========================================\n";
echo " TESTE FINALIZADO\n";
echo "========================================\n";
