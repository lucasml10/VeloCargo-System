
<?php

require_once __DIR__ . '/src/Models/Volume.php';

echo "====================================\n";
echo "      TESTE DE VOLUMES VELOCARGO\n";
echo "====================================\n\n";

// Criando volumes
$volume1 = new Volume(
    "Caixa de produtos eletrônicos",
    15,
    "Carga Frágil"
);

$volume2 = new Volume(
    "Caixa de mercadorias",
    25,
    "Carga Geral"
);

$volume3 = new Volume(
    "Medicamentos refrigerados",
    10,
    "Carga Refrigerada"
);

// Armazenando os volumes
$volumes = [$volume1, $volume2, $volume3];

$pesoTotal = 0;

// Exibindo os volumes
foreach ($volumes as $indice => $volume) {

    echo "Volume " . ($indice + 1) . "\n";

    echo "Descrição: " . $volume->getDescricao() . "\n";

    echo "Peso: " . $volume->getPeso() . " kg\n";

    echo "Natureza: " . $volume->getNatureza() . "\n";

    echo "Carga frágil? ";
    echo $volume->isFragil() ? "SIM\n" : "NÃO\n";

    echo "Carga refrigerada? ";
    echo $volume->isRefrigerada() ? "SIM\n" : "NÃO\n";

    $pesoTotal += $volume->getPeso();

    echo "------------------------------------\n";
}

echo "\nPeso total dos volumes: "
    . number_format($pesoTotal, 2, ',', '.')
    . " kg\n";

// Testando peso inválido
echo "\n===== TESTE DE PESO INVÁLIDO =====\n";

try {

    $volumeInvalido = new Volume(
        "Caixa de teste",
        -5,
        "Carga Geral"
    );

    echo "Volume cadastrado.\n";

} catch (InvalidArgumentException $erro) {

    echo "Cadastro bloqueado: "
        . $erro->getMessage()
        . "\n";
}

echo "\n====================================\n";
echo "       TESTES FINALIZADOS\n";
echo "====================================\n";
