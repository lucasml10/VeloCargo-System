
<?php

require_once __DIR__ . '/src/Models/Cliente.php';

echo "====================================\n";
echo "     TESTE DE CLIENTES VELOCARGO\n";
echo "====================================\n\n";

// Cadastro de cliente comum
$clienteComum = new Cliente(
    "João da Silva",
    "12345678901",
    "Rua das Flores, 100",
    "Comum"
);

// Cadastro de cliente corporativo
$clienteCorporativo = new Cliente(
    "Empresa ABC Ltda",
    "12345678000199",
    "Avenida Brasil, 500",
    "Corporativo"
);

// Exibindo cliente comum
echo "===== CLIENTE COMUM =====\n";

echo "Nome: " . $clienteComum->getNome() . "\n";
echo "Documento: " . $clienteComum->getDocumento() . "\n";
echo "Endereço: " . $clienteComum->getEnderecoColeta() . "\n";
echo "Tipo: " . $clienteComum->getTipoCliente() . "\n";

echo "É corporativo? ";
echo $clienteComum->isCorporativo() ? "SIM\n" : "NÃO\n";

// Exibindo cliente corporativo
echo "\n===== CLIENTE CORPORATIVO =====\n";

echo "Nome: " . $clienteCorporativo->getNome() . "\n";
echo "Documento: " . $clienteCorporativo->getDocumento() . "\n";
echo "Endereço: " . $clienteCorporativo->getEnderecoColeta() . "\n";
echo "Tipo: " . $clienteCorporativo->getTipoCliente() . "\n";

echo "É corporativo? ";
echo $clienteCorporativo->isCorporativo() ? "SIM\n" : "NÃO\n";

// Testando documento inválido
echo "\n===== TESTE DE DOCUMENTO INVÁLIDO =====\n";

try {
    $clienteInvalido = new Cliente(
        "Cliente Teste",
        "12345",
        "Rua de Teste, 10",
        "Comum"
    );

    echo "Cadastro realizado.\n";

} catch (InvalidArgumentException $erro) {

    echo "Cadastro bloqueado: " . $erro->getMessage() . "\n";
}

echo "\n====================================\n";
echo "       TESTES FINALIZADOS\n";
echo "====================================\n";
