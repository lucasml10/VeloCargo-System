<?php

require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';
require_once __DIR__ . '/src/Services/PersistenciaJSON.php';

echo "====================================\n";
echo " TESTE DE PERSISTENCIA JSON\n";
echo "====================================\n\n";

try {
    $arquivoTeste = __DIR__ . '/dados/teste_velocargo.json';
    $persistencia = new PersistenciaJSON($arquivoTeste);

    if (file_exists($arquivoTeste)) {
        unlink($arquivoTeste);
    }

    $cliente = new Cliente(
        "Cliente Teste",
        "12345678901",
        "Rua de Teste, 100",
        "Corporativo"
    );

    $veiculo = new Utilitario(
        "TES1234",
        "Fiat Fiorino",
        2024,
        1000,
        3.5,
        true,
        4.50
    );

    $pedido = new PedidoFrete(
        $cliente,
        "Empresa Destinataria",
        80
    );

    $pedido->adicionarVolume(
        new Volume("Caixas de produtos", 100, "carga geral")
    );

    $pedido->alocarVeiculo($veiculo);

    $pedido->atualizarStatus(
        "Em separacao",
        "Pedido preparado para transporte."
    );

    $persistencia->salvar([$cliente], [$veiculo], [$pedido]);

    echo "[OK] Dados salvos em JSON.\n";

    $dados = $persistencia->carregar();

    $pedidoRecuperado = $dados['pedidos'][0];
    $veiculoRecuperado = $dados['veiculos'][0];

    if ($pedidoRecuperado->getCodigoRastreamento() !==
        $pedido->getCodigoRastreamento()) {
        throw new RuntimeException("Codigo de rastreamento incorreto.");
    }

    if ($pedidoRecuperado->getStatus() !== "Em separacao") {
        throw new RuntimeException("Status nao foi preservado.");
    }

    if ($pedidoRecuperado->getPesoTotal() !== 100.0) {
        throw new RuntimeException("Peso total incorreto.");
    }

    if ($pedidoRecuperado->getHistorico() !== $pedido->getHistorico()) {
        throw new RuntimeException("Historico nao foi preservado.");
    }

    if ($veiculoRecuperado->isDisponivel() !== false) {
        throw new RuntimeException("Disponibilidade incorreta.");
    }

    if ($pedidoRecuperado->getVeiculo() !== $veiculoRecuperado) {
        throw new RuntimeException("Relacionamento com veiculo incorreto.");
    }

    echo "[OK] Cliente recuperado.\n";
    echo "[OK] Veiculo recuperado.\n";
    echo "[OK] Pedido recuperado.\n";
    echo "[OK] Codigo de rastreamento preservado.\n";
    echo "[OK] Status preservado.\n";
    echo "[OK] Historico preservado.\n";
    echo "[OK] Disponibilidade preservada.\n";
    echo "[OK] Relacionamento entre pedido e veiculo preservado.\n";

    echo "\n====================================\n";
    echo " TODOS OS TESTES PASSARAM!\n";
    echo "====================================\n";

} catch (Throwable $erro) {
    echo "\nFALHA NO TESTE: " . $erro->getMessage() . "\n";
}
