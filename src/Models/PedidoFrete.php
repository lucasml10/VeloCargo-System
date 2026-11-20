
<?php

require_once __DIR__ . '/Cliente.php';
require_once __DIR__ . '/Volume.php';
require_once __DIR__ . '/Veiculo.php';

class PedidoFrete
{
    private static int $proximoCodigo = 1001;
    private static int $totalPedidosCriados = 0;

    private string $codigoRastreamento;
    private Cliente $clienteRemetente;
    private string $destinatario;
    private float $distanciaKm;
    private string $status;
    private array $volumes = [];
    private ?Veiculo $veiculo = null;
    private array $historico = [];

    public function __construct(
        Cliente $clienteRemetente,
        string $destinatario,
        float $distanciaKm
    ) {
        if (empty(trim($destinatario))) {
            throw new InvalidArgumentException(
                "O destinatario e obrigatorio."
            );
        }

        if ($distanciaKm <= 0) {
            throw new InvalidArgumentException(
                "A distancia deve ser maior que zero."
            );
        }

        $this->codigoRastreamento =
            "VC-" . self::$proximoCodigo++;

        $this->clienteRemetente = $clienteRemetente;
        $this->destinatario = trim($destinatario);
        $this->distanciaKm = $distanciaKm;
        $this->status = "Registrado";

        self::$totalPedidosCriados++;

        $this->registrarEvento(
            "Pedido registrado",
            "Pedido criado no sistema VeloCargo."
        );
    }

    public function getCodigoRastreamento(): string
    {
        return $this->codigoRastreamento;
    }

    public function getClienteRemetente(): Cliente
    {
        return $this->clienteRemetente;
    }

    public function getDestinatario(): string
    {
        return $this->destinatario;
    }

    public function getDistanciaKm(): float
    {
        return $this->distanciaKm;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getVolumes(): array
    {
        return $this->volumes;
    }

    public function getVeiculo(): ?Veiculo
    {
        return $this->veiculo;
    }

    public function getPesoTotal(): float
    {
        $pesoTotal = 0;

        foreach ($this->volumes as $volume) {
            $pesoTotal += $volume->getPeso();
        }

        return $pesoTotal;
    }

    public function getTotalPedidosCriados(): int
    {
        return self::$totalPedidosCriados;
    }

    public function adicionarVolume(Volume $volume): void
    {
        if ($this->veiculo !== null) {
            throw new RuntimeException(
                "Nao e possivel adicionar volumes apos a alocacao do veiculo."
            );
        }

        $this->volumes[] = $volume;
    }

    private function registrarEvento(
        string $status,
        string $descricao
    ): void {
        $this->historico[] = [
            'dataHora' => date('d/m/Y H:i:s'),
            'status' => $status,
            'descricao' => $descricao
        ];
    }

    public function atualizarStatus(
        string $novoStatus,
        string $descricao
    ): void {
        $novoStatus = trim($novoStatus);
        $descricao = trim($descricao);

        if (empty($novoStatus)) {
            throw new InvalidArgumentException(
                "O novo status e obrigatorio."
            );
        }

        if (empty($descricao)) {
            throw new InvalidArgumentException(
                "A descricao do evento e obrigatoria."
            );
        }

        if ($novoStatus === $this->status) {
            throw new RuntimeException(
                "O pedido ja possui esse status."
            );
        }

        $transicoesPermitidas = [
            "Registrado" => [
                "Veiculo alocado"
            ],
            "Veiculo alocado" => [
                "Em separacao"
            ],
            "Em separacao" => [
                "Saiu para entrega"
            ],
            "Saiu para entrega" => [
                "Entregue",
                "Ocorrencia na entrega"
            ],
            "Ocorrencia na entrega" => [
                "Saiu para entrega",
                "Entregue"
            ],
            "Entregue" => []
        ];

        if (!isset($transicoesPermitidas[$this->status])) {
            throw new RuntimeException(
                "Status atual desconhecido."
            );
        }

        if (!in_array(
            $novoStatus,
            $transicoesPermitidas[$this->status],
            true
        )) {
            throw new RuntimeException(
                "Transicao de status nao permitida: " .
                $this->status . " para " . $novoStatus . "."
            );
        }

        $this->status = $novoStatus;

        $this->registrarEvento(
            $this->status,
            $descricao
        );
    }

    public function getHistorico(): array
    {
        return $this->historico;
    }

    public function alocarVeiculo(Veiculo $veiculo): void
    {
        if (empty($this->volumes)) {
            throw new RuntimeException(
                "Nao e possivel alocar veiculo para pedido sem volumes."
            );
        }

        if ($this->veiculo !== null) {
            throw new RuntimeException(
                "Este pedido ja possui um veiculo alocado."
            );
        }

        if (
            !$veiculo->validarEncomenda(
                $this->getPesoTotal(),
                'carga geral'
            )
        ) {
            throw new RuntimeException(
                "Veiculo indisponivel ou com capacidade insuficiente."
            );
        }

        foreach ($this->volumes as $volume) {
            if (
                !$veiculo->validarEncomenda(
                    $volume->getPeso(),
                    $volume->getNatureza()
                )
            ) {
                throw new RuntimeException(
                    "O veiculo nao esta autorizado para transportar: " .
                    $volume->getNatureza()
                );
            }
        }

        $veiculo->reservar();

        $this->veiculo = $veiculo;

        $this->atualizarStatus(
            "Veiculo alocado",
            "Veiculo " . $veiculo->getPlaca() .
            " (" . $veiculo->getModelo() . ") alocado ao pedido."
        );
    }

    // Reconstrói um pedido utilizando os dados do JSON.
    public static function restaurar(
        string $codigo,
        Cliente $cliente,
        string $destinatario,
        float $distancia,
        string $status,
        array $volumes,
        ?Veiculo $veiculo,
        array $historico
    ): self {
        $pedido = new self(
            $cliente,
            $destinatario,
            $distancia
        );

        $pedido->codigoRastreamento = $codigo;
        $pedido->status = $status;
        $pedido->volumes = $volumes;
        $pedido->veiculo = $veiculo;
        $pedido->historico = $historico;

        return $pedido;
    }

    // Restaura a sequência dos códigos e o contador de pedidos.
    public static function restaurarContadores(
        int $proximoCodigo,
        int $totalPedidos
    ): void {
        self::$proximoCodigo = $proximoCodigo;
        self::$totalPedidosCriados = $totalPedidos;
    }
}
