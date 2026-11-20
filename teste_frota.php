
<?php

require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';

echo "========================================\n";
echo "       TESTE DA FROTA VELOCARGO\n";
echo "========================================\n\n";

// Criando os veículos
$moto = new Motocicleta(
    "ABC1234",
    "Honda CG 160",
    2024,
    20,
    160,
    true,
    2.50
);

$utilitario = new Utilitario(
    "DEF5678",
    "Fiat Fiorino",
    2023,
    1000,
    3.5,
    true,
    4.50
);

$caminhao = new CaminhaoRefrigerado(
    "GHI9012",
    "Mercedes-Benz Atego",
    2024,
    5000,
    -10,
    true,
    7.00
);

// Exibindo os veículos
$veiculos = [$moto, $utilitario, $caminhao];

foreach ($veiculos as $veiculo) {
    echo "Placa: " . $veiculo->getPlaca() . "\n";
    echo "Modelo: " . $veiculo->getModelo() . "\n";
    echo "Capacidade: " . $veiculo->getCapacidadeMaxima() . " kg\n";
    echo "Custo por KM: R$ "
        . number_format($veiculo->calcularCustoPorKm(), 2, ',', '.')
        . "\n";
    echo "Disponível: "
        . ($veiculo->isDisponivel() ? "Sim" : "Não")
        . "\n\n";
}

// Testando capacidade
echo "========== TESTE DE CAPACIDADE ==========\n";

echo "Moto aceita 15 kg: ";
echo $moto->validarEncomenda(15, 'geral') ? "SIM\n" : "NÃO\n";

echo "Moto aceita 30 kg: ";
echo $moto->validarEncomenda(30, 'geral') ? "SIM\n" : "NÃO\n";

// Testando carga refrigerada
echo "\n======= TESTE DE CARGA REFRIGERADA =======\n";

echo "Moto aceita carga refrigerada: ";
echo $moto->validarEncomenda(10, 'refrigerada') ? "SIM\n" : "NÃO\n";

echo "Utilitário aceita carga refrigerada: ";
echo $utilitario->validarEncomenda(100, 'refrigerada') ? "SIM\n" : "NÃO\n";

echo "Caminhão certificado aceita carga refrigerada: ";
echo $caminhao->validarEncomenda(1000, 'refrigerada') ? "SIM\n" : "NÃO\n";

// Testando certificado sanitário
$caminhaoSemCertificado = new CaminhaoRefrigerado(
    "JKL3456",
    "Volvo VM",
    2023,
    4000,
    -15,
    false,
    8.00
);

echo "Caminhão sem certificado aceita carga refrigerada: ";
echo $caminhaoSemCertificado->validarEncomenda(1000, 'refrigerada')
    ? "SIM\n" : "NÃO\n";

// Testando disponibilidade
echo "\n========== TESTE DE DISPONIBILIDADE ==========\n";

$moto->reservar();

echo "Moto após reserva: ";
echo $moto->isDisponivel() ? "DISPONÍVEL\n" : "EM ROTA\n";

echo "Moto ocupada aceita novo pedido: ";
echo $moto->validarEncomenda(10, 'geral') ? "SIM\n" : "NÃO\n";

echo "\n========================================\n";
echo "          TESTES FINALIZADOS\n";
echo "========================================\n";
