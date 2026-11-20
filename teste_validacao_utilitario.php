
<?php

require_once __DIR__ . '/src/Models/Utilitario.php';

$utilitario = new Utilitario(
    "DEF5678",
    "Fiat Fiorino",
    2022,
    1000,
    3.5,
    true,
    4.50
);

echo "TESTE 1 - Carga geral:\n";

$resultado1 = $utilitario->validarEncomenda(
    500,
    "carga geral"
);

echo $resultado1 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 2 - Carga refrigerada:\n";

$resultado2 = $utilitario->validarEncomenda(
    100,
    "carga refrigerada"
);

echo $resultado2 ? "ACEITA\n" : "REJEITADA\n";

echo "\nTESTE 3 - Carga acima da capacidade:\n";

$resultado3 = $utilitario->validarEncomenda(
    1200,
    "carga geral"
);

echo $resultado3 ? "ACEITA\n" : "REJEITADA\n";
