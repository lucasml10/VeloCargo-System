
<?php

require_once __DIR__ . '/Veiculo.php';

class Utilitario extends Veiculo
{
    private float $volumeInterno;
    private bool $eixoReforcado;
    private float $custoPorKm;

    public function __construct(
        string $placa,
        string $modelo,
        int $anoFabricacao,
        float $capacidadeMaxima,
        float $volumeInterno,
        bool $eixoReforcado,
        float $custoPorKm
    ) {
        parent::__construct(
            $placa,
            $modelo,
            $anoFabricacao,
            $capacidadeMaxima
        );

        if ($volumeInterno <= 0) {
            throw new InvalidArgumentException(
                "O volume interno deve ser maior que zero."
            );
        }

        if ($custoPorKm <= 0) {
            throw new InvalidArgumentException(
                "Custo por quilometro invalido."
            );
        }

        $this->volumeInterno = $volumeInterno;
        $this->eixoReforcado = $eixoReforcado;
        $this->custoPorKm = $custoPorKm;
    }

    public function getVolumeInterno(): float
    {
        return $this->volumeInterno;
    }

    public function possuiEixoReforcado(): bool
    {
        return $this->eixoReforcado;
    }

    public function calcularCustoPorKm(): float
    {
        return $this->custoPorKm;
    }

    protected function validarCaracteristicasCarga(
        string $natureza
    ): bool {

        $natureza = strtolower(trim($natureza));

        $natureza = strtr($natureza, [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ç' => 'c'
        ]);

        $natureza = preg_replace(
            '/^carga\s+/',
            '',
            $natureza
        );

        $cargasRestritas = [
            'refrigerada',
            'perecivel',
            'pereciveis',
            'farmaceutica',
            'farmaceutico',
            'farmaceuticas',
            'farmaceuticos'
        ];

        return !in_array(
            $natureza,
            $cargasRestritas,
            true
        );
    }
}
