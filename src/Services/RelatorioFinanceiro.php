
<?php

require_once __DIR__ . '/../Models/PedidoFrete.php';
require_once __DIR__ . '/CalculadoraFrete.php';

class RelatorioFinanceiro
{
    public function gerarRelatorio(array $pedidos): void
    {
        echo "\n";
        echo "====================================================\n";
        echo "             RELATORIO FINANCEIRO\n";
        echo "                  VELOCARGO\n";
        echo "====================================================\n\n";

        if (empty($pedidos)) {
            echo "Nenhum pedido cadastrado para o relatorio.\n";
            return;
        }

        $faturamentoBruto = 0;
        $totalSeguros = 0;
        $pesoTotalTransportado = 0;
        $totalRecebido = 0;
        $quantidadePedidos = 0;

        $maiorPedido = null;
        $maiorValor = -1;

        $calculadora = new CalculadoraFrete();

        foreach ($pedidos as $pedido) {

            if (!$pedido instanceof PedidoFrete) {
                continue;
            }

            if (
                $pedido->getVeiculo() === null ||
                empty($pedido->getVolumes())
            ) {
                continue;
            }

            // Calcula os valores financeiros do pedido.
            $resultado = $calculadora->calcular($pedido);

            $faturamentoBruto += $resultado['valorFrete'];
            $totalSeguros += $resultado['premioSeguro'];
            $totalRecebido += $resultado['valorFinal'];

            $pesoTotalTransportado += $pedido->getPesoTotal();

            $quantidadePedidos++;

            // Identifica o pedido de maior valor.
            if ($resultado['valorFinal'] > $maiorValor) {
                $maiorValor = $resultado['valorFinal'];
                $maiorPedido = $pedido;
            }
        }

        if ($quantidadePedidos === 0) {
            echo "Nenhum pedido com veiculo e volumes alocados.\n";
            return;
        }

        // Exibição do relatório.
        echo "----------- RESUMO FINANCEIRO -----------\n\n";

        echo "Quantidade de pedidos: " .
            $quantidadePedidos . "\n";

        echo "Faturamento bruto dos fretes: R$ " .
            number_format(
                $faturamentoBruto,
                2,
                ',',
                '.'
            ) . "\n";

        echo "Total arrecadado com seguros: R$ " .
            number_format(
                $totalSeguros,
                2,
                ',',
                '.'
            ) . "\n";

        echo "PESO TOTAL TRANSPORTADO: " .
            number_format(
                $pesoTotalTransportado,
                2,
                ',',
                '.'
            ) . " kg\n";

        echo "\n----------- TOTAL RECEBIDO -----------\n";

        echo "Fretes + seguros: R$ " .
            number_format(
                $totalRecebido,
                2,
                ',',
                '.'
            ) . "\n";

        echo "\n----------- PEDIDO DE MAIOR VALOR -----------\n";

        if ($maiorPedido !== null) {

            echo "Codigo de rastreamento: " .
                $maiorPedido->getCodigoRastreamento() . "\n";

            echo "Destinatario: " .
                $maiorPedido->getDestinatario() . "\n";

            echo "Cliente: " .
                $maiorPedido->getClienteRemetente()->getNome() . "\n";

            echo "Valor: R$ " .
                number_format(
                    $maiorValor,
                    2,
                    ',',
                    '.'
                ) . "\n";
        }

        echo "\n====================================================\n";
        echo "          FIM DO RELATORIO FINANCEIRO\n";
        echo "====================================================\n";
    }
}
