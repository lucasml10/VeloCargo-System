
<?php

require_once __DIR__ . '/src/Models/Motocicleta.php';

$moto = new Motocicleta(
    "ABC1234",
    "Honda CG 160",
    2023,
    20,
    160,
    true,
    2.50
);

echo "TESTE 1 - Carga geral:\n";

$resultado1 = $moto->validarEncomenda(
    10,
    "carga geral"
);

echo $resultado1 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 2 - Carga refrigerada:\n";

$resultado2 = $moto->validarEncomenda(
    10,
    "carga refrigerada"
);

echo $resultado2 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 3 - Carga acima da capacidade:\n";

$resultado3 = $moto->validarEncomenda(
    30,
    "carga geral"
);

echo $resultado3 ? "ACEITA\n" : "REJEITADA\n";
