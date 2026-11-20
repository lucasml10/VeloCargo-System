
<?php

require_once __DIR__ . '/../Models/PedidoFrete.php';
require_once __DIR__ . '/../Services/CalculadoraFrete.php';

class RelatorioPedidos
{
    public function gerarRelatorio(
        array $pedidos,
        string $codigoRastreamento
    ): void {

        $pedidoEncontrado = null;

        // Localiza o pedido pelo código de rastreamento.
        foreach ($pedidos as $pedido) {

            if (
                $pedido instanceof PedidoFrete &&
                strtoupper(trim($pedido->getCodigoRastreamento())) ===
                strtoupper(trim($codigoRastreamento))
            ) {
                $pedidoEncontrado = $pedido;
                break;
            }
        }

        if ($pedidoEncontrado === null) {
            echo "\nPedido não encontrado.\n";
            return;
        }

        $pedido = $pedidoEncontrado;

        echo "\n";
        echo "====================================================\n";
        echo "              RELATORIO DO PEDIDO\n";
        echo "                  VELOCARGO\n";
        echo "====================================================\n\n";

        // Dados gerais.
        echo "CODIGO DE RASTREAMENTO: " .
            $pedido->getCodigoRastreamento() . "\n";

        echo "STATUS ATUAL: " .
            $pedido->getStatus() . "\n";

        echo "DESTINATARIO: " .
            $pedido->getDestinatario() . "\n";

        echo "DISTANCIA: " .
            number_format($pedido->getDistanciaKm(), 2, ',', '.') .
            " km\n";

        // Dados do cliente.
        $cliente = $pedido->getClienteRemetente();

        echo "\n----------- DADOS DO CLIENTE -----------\n";

        echo "Nome: " . $cliente->getNome() . "\n";
        echo "Documento: " . $cliente->getDocumento() . "\n";
        echo "Endereco de coleta: " .
            $cliente->getEnderecoColeta() . "\n";
        echo "Tipo de cliente: " .
            $cliente->getTipoCliente() . "\n";

        // Dados do veículo.
        echo "\n----------- VEICULO ALOCADO -----------\n";

        $veiculo = $pedido->getVeiculo();

        if ($veiculo !== null) {
            echo "Placa: " . $veiculo->getPlaca() . "\n";
            echo "Modelo: " . $veiculo->getModelo() . "\n";
            echo "Ano: " . $veiculo->getAnoFabricacao() . "\n";
            echo "Capacidade: " .
                number_format(
                    $veiculo->getCapacidadeMaxima(),
                    2,
                    ',',
                    '.'
                ) . " kg\n";
            echo "Custo por km: R$ " .
                number_format(
                    $veiculo->calcularCustoPorKm(),
                    2,
                    ',',
                    '.'
                ) . "\n";
        } else {
            echo "Nenhum veiculo alocado.\n";
        }

        // Volumes do pedido.
        echo "\n----------- VOLUMES DA ENCOMENDA -----------\n";

        $volumes = $pedido->getVolumes();

        if (empty($volumes)) {
            echo "Nenhum volume cadastrado.\n";
        } else {
            $contador = 1;

            foreach ($volumes as $volume) {
                echo "Volume " . $contador . "\n";
                echo "Descricao: " .
                    $volume->getDescricao() . "\n";
                echo "Peso: " .
                    number_format(
                        $volume->getPeso(),
                        2,
                        ',',
                        '.'
                    ) . " kg\n";
                echo "Natureza: " .
                    $volume->getNatureza() . "\n";
                echo "------------------------------------\n";

                $contador++;
            }
        }

        echo "PESO TOTAL: " .
            number_format(
                $pedido->getPesoTotal(),
                2,
                ',',
                '.'
            ) . " kg\n";

        // Detalhamento financeiro.
        echo "\n----------- DETALHAMENTO FINANCEIRO -----------\n";

        if ($veiculo !== null && !empty($volumes)) {

            $calculadora = new CalculadoraFrete();
            $resultado = $calculadora->calcular($pedido);

            echo "Custo de deslocamento: R$ " .
                number_format(
                    $resultado['custoDeslocamento'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Custo por peso: R$ " .
                number_format(
                    $resultado['custoPeso'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Custo base: R$ " .
                number_format(
                    $resultado['custoBase'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Adicional fragil: R$ " .
                number_format(
                    $resultado['adicionalFragil'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Adicional refrigerada: R$ " .
                number_format(
                    $resultado['adicionalRefrigerada'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Desconto: R$ " .
                number_format(
                    $resultado['desconto'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Valor do frete: R$ " .
                number_format(
                    $resultado['valorFrete'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            echo "Seguro obrigatorio: " .
                ($resultado['seguroObrigatorio'] ? "SIM" : "NAO") . "\n";

            echo "Valor do seguro: R$ " .
                number_format(
                    $resultado['premioSeguro'],
                    2,
                    ',',
                    '.'
                ) . "\n";

            if ($resultado['numeroApolice'] !== null) {
                echo "Numero da apolice: " .
                    $resultado['numeroApolice'] . "\n";
            }

            echo "VALOR FINAL: R$ " .
                number_format(
                    $resultado['valorFinal'],
                    2,
                    ',',
                    '.'
                ) . "\n";

        } else {
            echo "Calculo indisponivel: pedido sem veiculo ou volumes.\n";
        }

        // Histórico de eventos.
        echo "\n----------- HISTORICO DE EVENTOS -----------\n";

        $historico = $pedido->getHistorico();

        if (empty($historico)) {
            echo "Nenhum evento registrado.\n";
        } else {
            foreach ($historico as $evento) {
                echo "Data e hora: " .
                    $evento['dataHora'] . "\n";

                echo "Status: " .
                    $evento['status'] . "\n";

                echo "Descricao: " .
                    $evento['descricao'] . "\n";

                echo "------------------------------------\n";
            }
        }

        echo "\n====================================================\n";
        echo "             FIM DO RELATORIO\n";
        echo "====================================================\n";
    }
}
