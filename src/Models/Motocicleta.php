
<?php

require_once __DIR__ . '/Veiculo.php';

class Motocicleta extends Veiculo
{
    private int $cilindrada;
    private bool $bauTermico;
    private float $custoPorKm;

    public function __construct(
        string $placa,
        string $modelo,
        int $anoFabricacao,
        float $capacidadeMaxima,
        int $cilindrada,
        bool $bauTermico,
        float $custoPorKm
    ) {
        parent::__construct(
            $placa,
            $modelo,
            $anoFabricacao,
            $capacidadeMaxima
        );

        if ($cilindrada <= 0) {
            throw new InvalidArgumentException(
                "Cilindrada invalida."
            );
        }

        if ($custoPorKm <= 0) {
            throw new InvalidArgumentException(
                "Custo por quilometro invalido."
            );
        }

        $this->cilindrada = $cilindrada;
        $this->bauTermico = $bauTermico;
        $this->custoPorKm = $custoPorKm;
    }

    public function getCilindrada(): int
    {
        return $this->cilindrada;
    }

    public function possuiBauTermico(): bool
    {
        return $this->bauTermico;
    }

    public function calcularCustoPorKm(): float
    {
        return $this->custoPorKm;
    }

    protected function validarCaracteristicasCarga(
        string $natureza
    ): bool {

        $natureza = strtolower(trim($natureza));

        // Remove acentos para padronizar a comparacao.
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

        // Remove o prefixo utilizado pela classe Volume.
        $natureza = preg_replace(
            '/^carga\s+/',
            '',
            $natureza
        );

        // Motocicletas nao transportam cargas
        // refrigeradas, pereciveis ou farmaceuticas.
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
