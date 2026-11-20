
<?php

require_once __DIR__ . '/../Models/PedidoFrete.php';
require_once __DIR__ . '/../Models/Motocicleta.php';
require_once __DIR__ . '/../Models/Utilitario.php';
require_once __DIR__ . '/../Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/SeguroFrete.php';

class CalculadoraFrete
{
    public function calcular(PedidoFrete $pedido): array
    {
        $veiculo = $pedido->getVeiculo();

        if ($veiculo === null) {
            throw new RuntimeException(
                "O pedido precisa ter um veículo alocado para calcular o frete."
            );
        }

        // Tarifas por quilograma.
        if ($veiculo instanceof Motocicleta) {
            $tarifaPorKg = 0.50;
        } elseif ($veiculo instanceof Utilitario) {
            $tarifaPorKg = 0.35;
        } elseif ($veiculo instanceof CaminhaoRefrigerado) {
            $tarifaPorKg = 0.80;
        } else {
            throw new RuntimeException(
                "Categoria de veículo não reconhecida."
            );
        }

        $distancia = $pedido->getDistanciaKm();
        $peso = $pedido->getPesoTotal();

        // Custo base.
        $custoDeslocamento = $distancia * $veiculo->calcularCustoPorKm();
        $custoPeso = $peso * $tarifaPorKg;
        $custoBase = $custoDeslocamento + $custoPeso;

        // Identificação das cargas especiais.
        $temCargaFragil = false;
        $temCargaRefrigerada = false;

        foreach ($pedido->getVolumes() as $volume) {
            if ($volume->isFragil()) {
                $temCargaFragil = true;
            }

            if ($volume->isRefrigerada()) {
                $temCargaRefrigerada = true;
            }
        }

        // Adicionais.
        $adicionalFragil = $temCargaFragil
            ? $custoBase * 0.12
            : 0;

        $adicionalRefrigerada = $temCargaRefrigerada
            ? $custoBase * 0.18
            : 0;

        $valorAntesDesconto = $custoBase
            + $adicionalFragil
            + $adicionalRefrigerada;

        // Desconto corporativo.
        $desconto = 0;

        if (
            $pedido->getClienteRemetente()->isCorporativo()
            && $distancia > 50
        ) {
            $desconto = $valorAntesDesconto * 0.08;
        }

        $valorFrete = $valorAntesDesconto - $desconto;

        // Seguro.
        $seguro = new SeguroFrete();

        $seguroObrigatorio = $seguro->exigeSeguro($pedido);

        $premioSeguro = 0;
        $numeroApolice = null;

        if ($seguroObrigatorio) {
            $dadosSeguro = $seguro->obterSeguroPedido(
                $pedido,
                $valorFrete
            );

            $premioSeguro = $dadosSeguro['premioSeguro'];
            $numeroApolice = $dadosSeguro['numeroApolice'];
        }

        // Total final.
        $valorFinal = $valorFrete + $premioSeguro;

        return [
            'distancia' => $distancia,
            'peso' => $peso,
            'tarifaPorKg' => $tarifaPorKg,
            'custoDeslocamento' => $custoDeslocamento,
            'custoPeso' => $custoPeso,
            'custoBase' => $custoBase,
            'adicionalFragil' => $adicionalFragil,
            'adicionalRefrigerada' => $adicionalRefrigerada,
            'desconto' => $desconto,
            'valorFrete' => $valorFrete,
            'seguroObrigatorio' => $seguroObrigatorio,
            'premioSeguro' => $premioSeguro,
            'numeroApolice' => $numeroApolice,
            'valorFinal' => $valorFinal
        ];
    }
}
