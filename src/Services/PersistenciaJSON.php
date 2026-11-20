
<?php

require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Volume.php';
require_once __DIR__ . '/../Models/Veiculo.php';
require_once __DIR__ . '/../Models/Motocicleta.php';
require_once __DIR__ . '/../Models/Utilitario.php';
require_once __DIR__ . '/../Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/../Models/PedidoFrete.php';

class PersistenciaJSON
{
    private string $caminhoArquivo;

    public function __construct(?string $caminhoArquivo = null)
    {
        $this->caminhoArquivo = $caminhoArquivo ??
            dirname(__DIR__, 2) . '/dados/velocargo.json';
    }

    public function salvar(
        array $clientes,
        array $veiculos,
        array $pedidos
    ): void {
        $diretorio = dirname($this->caminhoArquivo);

        if (
            !is_dir($diretorio) &&
            !mkdir($diretorio, 0775, true) &&
            !is_dir($diretorio)
        ) {
            throw new RuntimeException(
                "Nao foi possivel criar a pasta de dados."
            );
        }

        $dados = [
            'clientes' => [],
            'veiculos' => [],
            'pedidos' => [],
            'proximoCodigo' => 1001,
            'totalPedidos' => count($pedidos)
        ];

        foreach ($clientes as $cliente) {
            $dados['clientes'][] = [
                'nome' => $cliente->getNome(),
                'documento' => $cliente->getDocumento(),
                'enderecoColeta' => $cliente->getEnderecoColeta(),
                'tipoCliente' => $cliente->getTipoCliente()
            ];
        }

        foreach ($veiculos as $veiculo) {
            $dados['veiculos'][] = $this->exportarVeiculo($veiculo);
        }

        $maiorCodigo = 1000;

        foreach ($pedidos as $pedido) {
            $codigo = $pedido->getCodigoRastreamento();

            $numeroCodigo = (int) str_replace('VC-', '', $codigo);

            $maiorCodigo = max($maiorCodigo, $numeroCodigo);

            $volumes = [];

            foreach ($pedido->getVolumes() as $volume) {
                $volumes[] = [
                    'descricao' => $volume->getDescricao(),
                    'peso' => $volume->getPeso(),
                    'natureza' => $volume->getNatureza()
                ];
            }

            $veiculo = $pedido->getVeiculo();

            $dados['pedidos'][] = [
                'codigo' => $codigo,
                'documentoCliente' =>
                    $pedido->getClienteRemetente()->getDocumento(),
                'destinatario' => $pedido->getDestinatario(),
                'distanciaKm' => $pedido->getDistanciaKm(),
                'status' => $pedido->getStatus(),
                'volumes' => $volumes,
                'placaVeiculo' => $veiculo?->getPlaca(),
                'historico' => $pedido->getHistorico()
            ];
        }

        $dados['proximoCodigo'] = $maiorCodigo + 1;

        $json = json_encode(
            $dados,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new RuntimeException(
                "Erro ao converter os dados para JSON."
            );
        }

        if (file_put_contents($this->caminhoArquivo, $json) === false) {
            throw new RuntimeException(
                "Nao foi possivel salvar os dados JSON."
            );
        }
    }

    private function exportarVeiculo(Veiculo $veiculo): array
    {
        $dados = [
            'placa' => $veiculo->getPlaca(),
            'modelo' => $veiculo->getModelo(),
            'anoFabricacao' => $veiculo->getAnoFabricacao(),
            'capacidadeMaxima' => $veiculo->getCapacidadeMaxima(),
            'disponivel' => $veiculo->isDisponivel(),
            'categoria' => ''
        ];

        if ($veiculo instanceof Motocicleta) {
            $dados['categoria'] = 'motocicleta';
            $dados['cilindrada'] = $veiculo->getCilindrada();
            $dados['bauTermico'] = $veiculo->possuiBauTermico();
            $dados['custoPorKm'] = $veiculo->calcularCustoPorKm();

        } elseif ($veiculo instanceof Utilitario) {
            $dados['categoria'] = 'utilitario';
            $dados['volumeInterno'] = $veiculo->getVolumeInterno();
            $dados['eixoReforcado'] = $veiculo->possuiEixoReforcado();
            $dados['custoPorKm'] = $veiculo->calcularCustoPorKm();

        } elseif ($veiculo instanceof CaminhaoRefrigerado) {
            $dados['categoria'] = 'caminhao_refrigerado';
            $dados['temperaturaMinima'] =
                $veiculo->getTemperaturaMinima();
            $dados['certificadoSanitario'] =
                $veiculo->possuiCertificadoSanitario();
            $dados['custoPorKm'] = $veiculo->calcularCustoPorKm();

        } else {
            throw new RuntimeException(
                "Categoria de veiculo desconhecida."
            );
        }

        return $dados;
    }

    public function carregar(): array
    {
        if (!file_exists($this->caminhoArquivo)) {
            return [
                'clientes' => [],
                'veiculos' => [],
                'pedidos' => []
            ];
        }

        $conteudo = file_get_contents($this->caminhoArquivo);

        if ($conteudo === false || trim($conteudo) === '') {
            throw new RuntimeException(
                "O arquivo JSON esta vazio ou nao pode ser lido."
            );
        }

        try {
            $dados = json_decode(
                $conteudo,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $erro) {
            throw new RuntimeException(
                "Arquivo JSON invalido: " . $erro->getMessage()
            );
        }

        $clientes = [];
        $veiculos = [];
        $pedidos = [];

        $clientesPorDocumento = [];
        $veiculosPorPlaca = [];

        foreach ($dados['clientes'] ?? [] as $item) {
            $cliente = new Cliente(
                $item['nome'],
                $item['documento'],
                $item['enderecoColeta'],
                $item['tipoCliente']
            );

            $clientes[] = $cliente;

            $clientesPorDocumento[$cliente->getDocumento()] = $cliente;
        }

        foreach ($dados['veiculos'] ?? [] as $item) {
            $veiculo = $this->importarVeiculo($item);

            if (($item['disponivel'] ?? true) === false) {
                $veiculo->reservar();
            }

            $veiculos[] = $veiculo;
            $veiculosPorPlaca[$veiculo->getPlaca()] = $veiculo;
        }

        foreach ($dados['pedidos'] ?? [] as $item) {
            $documento = $item['documentoCliente'];

            if (!isset($clientesPorDocumento[$documento])) {
                throw new RuntimeException(
                    "Cliente nao encontrado ao restaurar o pedido."
                );
            }

            $volumes = [];

            foreach ($item['volumes'] ?? [] as $volumeDados) {
                $volumes[] = new Volume(
                    $volumeDados['descricao'],
                    (float) $volumeDados['peso'],
                    $volumeDados['natureza']
                );
            }

            $veiculo = null;
            $placa = $item['placaVeiculo'] ?? null;

            if ($placa !== null) {
                if (!isset($veiculosPorPlaca[$placa])) {
                    throw new RuntimeException(
                        "Veiculo nao encontrado ao restaurar o pedido."
                    );
                }

                $veiculo = $veiculosPorPlaca[$placa];
            }

            $pedido = PedidoFrete::restaurar(
                $item['codigo'],
                $clientesPorDocumento[$documento],
                $item['destinatario'],
                (float) $item['distanciaKm'],
                $item['status'],
                $volumes,
                $veiculo,
                $item['historico'] ?? []
            );

            $pedidos[] = $pedido;
        }

        PedidoFrete::restaurarContadores(
            (int) ($dados['proximoCodigo'] ?? 1001),
            (int) ($dados['totalPedidos'] ?? count($pedidos))
        );

        return [
            'clientes' => $clientes,
            'veiculos' => $veiculos,
            'pedidos' => $pedidos
        ];
    }

    private function importarVeiculo(array $dados): Veiculo
    {
        switch ($dados['categoria']) {
            case 'motocicleta':
                return new Motocicleta(
                    $dados['placa'],
                    $dados['modelo'],
                    (int) $dados['anoFabricacao'],
                    (float) $dados['capacidadeMaxima'],
                    (int) $dados['cilindrada'],
                    (bool) $dados['bauTermico'],
                    (float) $dados['custoPorKm']
                );

            case 'utilitario':
                return new Utilitario(
                    $dados['placa'],
                    $dados['modelo'],
                    (int) $dados['anoFabricacao'],
                    (float) $dados['capacidadeMaxima'],
                    (float) $dados['volumeInterno'],
                    (bool) $dados['eixoReforcado'],
                    (float) $dados['custoPorKm']
                );

            case 'caminhao_refrigerado':
                return new CaminhaoRefrigerado(
                    $dados['placa'],
                    $dados['modelo'],
                    (int) $dados['anoFabricacao'],
                    (float) $dados['capacidadeMaxima'],
                    (float) $dados['temperaturaMinima'],
                    (bool) $dados['certificadoSanitario'],
                    (float) $dados['custoPorKm']
                );

            default:
                throw new RuntimeException(
                    "Categoria de veiculo invalida no arquivo JSON."
                );
        }
    }
}
