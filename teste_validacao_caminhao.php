
<?php

require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';

// Caminhao com certificado sanitario vigente
$caminhaoComCertificado = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2022,
    5000,
    -10,
    true,
    7.00
);

// Caminhao sem certificado sanitario
$caminhaoSemCertificado = new CaminhaoRefrigerado(
    "JKL3456",
    "Volvo VM",
    2023,
    5000,
    -15,
    false,
    7.50
);

echo "TESTE 1 - Carga refrigerada com certificado:\n";

$resultado1 = $caminhaoComCertificado->validarEncomenda(
    100,
    "carga refrigerada"
);

echo $resultado1 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 2 - Carga refrigerada sem certificado:\n";

$resultado2 = $caminhaoSemCertificado->validarEncomenda(
    100,
    "carga refrigerada"
);

echo $resultado2 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 3 - Carga acima da capacidade:\n";

$resultado3 = $caminhaoComCertificado->validarEncomenda(
    6000,
    "carga geral"
);

echo $resultado3 ? "ACEITA\n" : "REJEITADA\n";
