
<?php

require_once __DIR__ . '/Veiculo.php';

class CaminhaoRefrigerado extends Veiculo
{
    private float $temperaturaMinima;
    private bool $certificadoSanitarioVigente;
    private float $custoPorKm;

    public function __construct(
        string $placa,
        string $modelo,
        int $anoFabricacao,
        float $capacidadeMaxima,
        float $temperaturaMinima,
        bool $certificadoSanitarioVigente,
        float $custoPorKm
    ) {
        parent::__construct(
            $placa,
            $modelo,
            $anoFabricacao,
            $capacidadeMaxima
        );

        if ($custoPorKm <= 0) {
            throw new InvalidArgumentException(
                "Custo por quilometro invalido."
            );
        }

        $this->temperaturaMinima = $temperaturaMinima;
        $this->certificadoSanitarioVigente =
            $certificadoSanitarioVigente;
        $this->custoPorKm = $custoPorKm;
    }

    public function getTemperaturaMinima(): float
    {
        return $this->temperaturaMinima;
    }

    public function possuiCertificadoSanitario(): bool
    {
        return $this->certificadoSanitarioVigente;
    }

    public function calcularCustoPorKm(): float
    {
        return $this->custoPorKm;
    }

    protected function validarCaracteristicasCarga(
        string $natureza
    ): bool {

        $natureza = strtolower(trim($natureza));

        // Padroniza os caracteres com acentos.
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

        // Remove o prefixo "carga".
        $natureza = preg_replace(
            '/^carga\s+/',
            '',
            $natureza
        );

        // Cargas que exigem certificado sanitário.
        $cargasControladas = [
            'refrigerada',
            'perecivel',
            'pereciveis',
            'farmaceutica',
            'farmaceutico',
            'farmaceuticas',
            'farmaceuticos'
        ];

        if (in_array(
            $natureza,
            $cargasControladas,
            true
        )) {
            return $this->certificadoSanitarioVigente;
        }

        return true;
    }
}
