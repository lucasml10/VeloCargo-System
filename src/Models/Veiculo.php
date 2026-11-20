
<?php

abstract class Veiculo
{
    // Atributos protegidos por encapsulamento
    private string $placa;
    private string $modelo;
    private int $anoFabricacao;
    private float $capacidadeMaxima;
    private bool $disponivel;

    // Construtor
    public function __construct(
        string $placa,
        string $modelo,
        int $anoFabricacao,
        float $capacidadeMaxima
    ) {
        if (empty(trim($placa))) {
            throw new InvalidArgumentException(
                "A placa do veículo é obrigatória."
            );
        }

        if (empty(trim($modelo))) {
            throw new InvalidArgumentException(
                "O modelo do veículo é obrigatório."
            );
        }

        if ($capacidadeMaxima <= 0) {
            throw new InvalidArgumentException(
                "A capacidade deve ser maior que zero."
            );
        }

        $this->placa = strtoupper($placa);
        $this->modelo = $modelo;
        $this->anoFabricacao = $anoFabricacao;
        $this->capacidadeMaxima = $capacidadeMaxima;
        $this->disponivel = true;
    }

    // Getters
    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function getAnoFabricacao(): int
    {
        return $this->anoFabricacao;
    }

    public function getCapacidadeMaxima(): float
    {
        return $this->capacidadeMaxima;
    }

    public function isDisponivel(): bool
    {
        return $this->disponivel;
    }

    // Controle de disponibilidade
    public function reservar(): void
    {
        if (!$this->disponivel) {
            throw new RuntimeException(
                "Veículo já está ocupado."
            );
        }

        $this->disponivel = false;
    }

    public function liberar(): void
    {
        $this->disponivel = true;
    }

    // Validação geral da encomenda
    public function validarEncomenda(
        float $peso,
        string $natureza
    ): bool {
        if (!$this->disponivel || $peso <= 0) {
            return false;
        }

        if ($peso > $this->capacidadeMaxima) {
            return false;
        }

        return $this->validarCaracteristicasCarga($natureza);
    }

    // Contratos que as classes-filhas deverão implementar
    abstract public function calcularCustoPorKm(): float;

    abstract protected function validarCaracteristicasCarga(
        string $natureza
    ): bool;
}
