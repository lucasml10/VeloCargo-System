
<?php

class Cliente
{
    private string $nome;
    private string $documento;
    private string $enderecoColeta;
    private string $tipoCliente;

    public function __construct(
        string $nome,
        string $documento,
        string $enderecoColeta,
        string $tipoCliente = 'Comum'
    ) {
        if (empty(trim($nome))) {
            throw new InvalidArgumentException(
                "O nome do cliente é obrigatório."
            );
        }

        if (empty(trim($enderecoColeta))) {
            throw new InvalidArgumentException(
                "O endereço de coleta é obrigatório."
            );
        }

        // Remove pontos, traços e outros caracteres
        $documentoLimpo = preg_replace(
            '/\D/',
            '',
            $documento
        );

        if (!in_array(strlen($documentoLimpo), [11, 14], true)) {
            throw new InvalidArgumentException(
                "Informe um CPF ou CNPJ válido em quantidade de dígitos."
            );
        }

        $tipoCliente = ucfirst(
            strtolower(trim($tipoCliente))
        );

        if (!in_array($tipoCliente, ['Comum', 'Corporativo'], true)) {
            throw new InvalidArgumentException(
                "Tipo de cliente inválido."
            );
        }

        $this->nome = $nome;
        $this->documento = $documentoLimpo;
        $this->enderecoColeta = $enderecoColeta;
        $this->tipoCliente = $tipoCliente;
    }

    // Retorna o nome do cliente
    public function getNome(): string
    {
        return $this->nome;
    }

    // Retorna o CPF ou CNPJ
    public function getDocumento(): string
    {
        return $this->documento;
    }

    // Retorna o endereço de coleta
    public function getEnderecoColeta(): string
    {
        return $this->enderecoColeta;
    }

    // Retorna o tipo de cliente
    public function getTipoCliente(): string
    {
        return $this->tipoCliente;
    }

    // Verifica se o cliente é corporativo
    public function isCorporativo(): bool
    {
        return $this->tipoCliente === 'Corporativo';
    }
}
