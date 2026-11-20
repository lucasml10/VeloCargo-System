
<?php

class Volume
{
    private string $descricao;
    private float $peso;
    private string $natureza;

    public function __construct(
        string $descricao,
        float $peso,
        string $natureza
    ) {
        if (empty(trim($descricao))) {
            throw new InvalidArgumentException(
                "A descrição do volume é obrigatória."
            );
        }

        if ($peso <= 0) {
            throw new InvalidArgumentException(
                "O peso deve ser maior que zero."
            );
        }

        $naturezasPermitidas = [
            'carga geral',
            'carga frágil',
            'carga refrigerada'
        ];

        $naturezaNormalizada = mb_strtolower(
            trim($natureza),
            'UTF-8'
        );

        if (!in_array(
            $naturezaNormalizada,
            $naturezasPermitidas,
            true
        )) {
            throw new InvalidArgumentException(
                "Natureza da carga inválida."
            );
        }

        $this->descricao = trim($descricao);
        $this->peso = $peso;
        $this->natureza = $naturezaNormalizada;
    }

    // Retorna a descrição do volume
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    // Retorna o peso individual
    public function getPeso(): float
    {
        return $this->peso;
    }

    // Retorna a natureza da carga
    public function getNatureza(): string
    {
        return $this->natureza;
    }

    // Verifica se a carga é frágil
    public function isFragil(): bool
    {
        return $this->natureza === 'carga frágil';
    }

    // Verifica se a carga é refrigerada
    public function isRefrigerada(): bool
    {
        return $this->natureza === 'carga refrigerada';
    }
}
