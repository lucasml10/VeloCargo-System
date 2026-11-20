
<?php

require_once __DIR__ . '/../Interfaces/Seguravel.php';
require_once __DIR__ . '/../Models/PedidoFrete.php';

class SeguroFrete implements Seguravel
{
    private static int $proximaApolice = 1;

    // Armazena os seguros emitidos durante a execução.
    private static array $segurosEmitidos = [];

    private float $taxaPremio;

    public function __construct(float $taxaPremio = 0.02)
    {
        if ($taxaPremio <= 0 || $taxaPremio > 1) {
            throw new InvalidArgumentException(
                "A taxa do seguro deve ser maior que zero e menor ou igual a 100%."
            );
        }

        $this->taxaPremio = $taxaPremio;
    }

    public function calcularPremio(float $valorBase): float
    {
        if ($valorBase < 0) {
            throw new InvalidArgumentException(
                "O valor base do seguro não pode ser negativo."
            );
        }

        return $valorBase * $this->taxaPremio;
    }

    public function emitirApolice(): string
    {
        $numero = "VC-SEG-" . str_pad(
            (string) self::$proximaApolice++,
            4,
            "0",
            STR_PAD_LEFT
        );

        return $numero;
    }

    public function exigeSeguro(PedidoFrete $pedido): bool
    {
        foreach ($pedido->getVolumes() as $volume) {
            if ($volume->isFragil() || $volume->isRefrigerada()) {
                return true;
            }
        }

        return false;
    }

    // Retorna o seguro existente ou cria um novo para o pedido.
    public function obterSeguroPedido(
        PedidoFrete $pedido,
        float $valorBase
    ): array {

        $codigo = $pedido->getCodigoRastreamento();

        if (isset(self::$segurosEmitidos[$codigo])) {
            return self::$segurosEmitidos[$codigo];
        }

        $premio = $this->calcularPremio($valorBase);
        $apolice = $this->emitirApolice();

        $seguro = [
            'premioSeguro' => $premio,
            'numeroApolice' => $apolice
        ];

        self::$segurosEmitidos[$codigo] = $seguro;

        return $seguro;
    }

    public function getTaxaPremio(): float
    {
        return $this->taxaPremio;
    }
}
