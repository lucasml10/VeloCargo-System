
<?php

// =============================================
// VELOCARGO SYSTEM - MENU PRINCIPAL
// =============================================

// MODELOS
require_once __DIR__ . '/src/Models/Veiculo.php';
require_once __DIR__ . '/src/Models/Motocicleta.php';
require_once __DIR__ . '/src/Models/Utilitario.php';
require_once __DIR__ . '/src/Models/CaminhaoRefrigerado.php';
require_once __DIR__ . '/src/Models/Cliente.php';
require_once __DIR__ . '/src/Models/Volume.php';
require_once __DIR__ . '/src/Models/PedidoFrete.php';

// SERVICOS
require_once __DIR__ . '/src/Services/CalculadoraFrete.php';
require_once __DIR__ . '/src/Services/SeguroFrete.php';
require_once __DIR__ . '/src/Services/RelatorioFrota.php';
require_once __DIR__ . '/src/Services/RelatorioPedidos.php';
require_once __DIR__ . '/src/Services/RelatorioFinanceiro.php';
require_once __DIR__ . '/src/Services/PersistenciaJSON.php';

// =============================================
// FUNCOES AUXILIARES
// =============================================

function lerTexto(string $mensagem): string
{
    echo $mensagem;
    return trim(fgets(STDIN));
}

function lerNumero(string $mensagem): float
{
    while (true) {
        $entrada = lerTexto($mensagem);
        $entrada = str_replace(',', '.', $entrada);

        if (is_numeric($entrada) && (float)$entrada >= 0) {
            return (float)$entrada;
        }

        echo "Valor invalido. Digite um numero nao negativo.\n";
    }
}

function lerInteiro(string $mensagem): int
{
    while (true) {
        $entrada = lerTexto($mensagem);

        if (filter_var($entrada, FILTER_VALIDATE_INT) !== false) {
            return (int)$entrada;
        }

        echo "Digite um numero inteiro valido.\n";
    }
}

function pausar(): void
{
    echo "\nPressione ENTER para continuar...";
    fgets(STDIN);
}

function localizarPedido(array $pedidos, string $codigo): ?PedidoFrete
{
    foreach ($pedidos as $pedido) {
        if (
            strtoupper($pedido->getCodigoRastreamento()) ===
            strtoupper(trim($codigo))
        ) {
            return $pedido;
        }
    }

    return null;
}

function localizarCliente(array $clientes, int $indice): ?Cliente
{
    return $clientes[$indice] ?? null;
}

function exibirClientes(array $clientes): void
{
    echo "\n----------- CLIENTES CADASTRADOS -----------\n";

    foreach ($clientes as $indice => $cliente) {
        echo ($indice + 1) . " - " .
            $cliente->getNome() . " | " .
            $cliente->getTipoCliente() . "\n";
    }
}

function exibirVeiculos(array $veiculos): void
{
    echo "\n----------- VEICULOS CADASTRADOS -----------\n";

    foreach ($veiculos as $indice => $veiculo) {
        echo ($indice + 1) . " - " .
            $veiculo->getPlaca() . " | " .
            $veiculo->getModelo() . " | ";

        echo $veiculo->isDisponivel()
            ? "DISPONIVEL\n"
            : "EM ROTA\n";
    }
}

// =============================================
// INICIALIZACAO E PERSISTENCIA
// =============================================

$persistencia = new PersistenciaJSON();

$arquivoDados = __DIR__ . '/dados/velocargo.json';

if (file_exists($arquivoDados)) {

    // Carrega os dados existentes.
    $dados = $persistencia->carregar();

    $clientes = $dados['clientes'];
    $veiculos = $dados['veiculos'];
    $pedidos = $dados['pedidos'];

    echo "\nDados anteriores carregados com sucesso!\n";

} else {

    // Primeira execucao: cria os dados iniciais.

    $clientes = [
        new Cliente(
            "Joao da Silva",
            "12345678901",
            "Rua das Flores, 100",
            "Comum"
        ),

        new Cliente(
            "Empresa ABC Ltda",
            "12345678000199",
            "Rua das Empresas, 100",
            "Corporativo"
        )
    ];

    $veiculos = [
        new Motocicleta(
            "ABC1234",
            "Honda CG 160",
            2023,
            20,
            160,
            true,
            2.50
        ),

        new Utilitario(
            "DEF5678",
            "Fiat Fiorino",
            2022,
            1000,
            3.5,
            true,
            4.50
        ),

        new CaminhaoRefrigerado(
            "GHI9012",
            "Mercedes-Benz Atego",
            2022,
            5000,
            -10,
            true,
            7.00
        )
    ];

    $pedidos = [];

    // Pedido de exemplo 1.
    $pedido1 = new PedidoFrete(
        $clientes[0],
        "Loja Central",
        30
    );

    $pedido1->adicionarVolume(
        new Volume(
            "Caixas de produtos",
            50,
            "carga geral"
        )
    );

    $pedido1->alocarVeiculo($veiculos[1]);

    $pedidos[] = $pedido1;

    // Pedido de exemplo 2.
    $pedido2 = new PedidoFrete(
        $clientes[1],
        "Hospital Central",
        120
    );

    $pedido2->adicionarVolume(
        new Volume(
            "Equipamentos eletronicos",
            15,
            "carga frágil"
        )
    );

    $pedido2->adicionarVolume(
        new Volume(
            "Medicamentos",
            10,
            "carga refrigerada"
        )
    );

    $pedido2->alocarVeiculo($veiculos[2]);

    $pedidos[] = $pedido2;

    // Salva os dados iniciais.
    $persistencia->salvar(
        $clientes,
        $veiculos,
        $pedidos
    );

    echo "\nDados iniciais cadastrados e salvos!\n";
}

// =============================================
// INSTANCIAR RELATORIOS
// =============================================

$relatorioFrota = new RelatorioFrota();
$relatorioPedidos = new RelatorioPedidos();
$relatorioFinanceiro = new RelatorioFinanceiro();

// =============================================
// MENU PRINCIPAL
// =============================================

$opcao = "";

do {

    echo "\n";
    echo "====================================================\n";
    echo "                 VELOCARGO SYSTEM\n";
    echo "             Sistema de Gestao Logistica\n";
    echo "====================================================\n";

    echo "1  - Cadastrar cliente\n";
    echo "2  - Cadastrar veiculo\n";
    echo "3  - Criar pedido de frete\n";
    echo "4  - Alocar veiculo ao pedido\n";
    echo "5  - Atualizar status do pedido\n";
    echo "6  - Consultar pedido por rastreamento\n";
    echo "7  - Relatorio da frota\n";
    echo "8  - Relatorio financeiro\n";
    echo "9  - Sair do sistema\n";

    echo "====================================================\n";

    $opcao = lerTexto("Escolha uma opcao: ");

    try {

        switch ($opcao) {

            // =====================================
            // 1 - CADASTRAR CLIENTE
            // =====================================

            case "1":

                echo "\n----------- CADASTRO DE CLIENTE -----------\n";

                $nome = lerTexto("Nome: ");
                $documento = lerTexto("CPF ou CNPJ: ");
                $endereco = lerTexto("Endereco de coleta: ");

                echo "\nTipo de cliente:\n";
                echo "1 - Comum\n";
                echo "2 - Corporativo\n";

                $tipo = lerTexto("Escolha: ");

                if ($tipo === "1") {
                    $tipoCliente = "Comum";
                } elseif ($tipo === "2") {
                    $tipoCliente = "Corporativo";
                } else {
                    throw new InvalidArgumentException(
                        "Tipo de cliente invalido."
                    );
                }

                $cliente = new Cliente(
                    $nome,
                    $documento,
                    $endereco,
                    $tipoCliente
                );

                $clientes[] = $cliente;

                echo "\nCliente cadastrado com sucesso!\n";

                break;

            // =====================================
            // 2 - CADASTRAR VEICULO
            // =====================================

            case "2":

                echo "\n----------- CADASTRO DE VEICULO -----------\n";

                echo "1 - Motocicleta\n";
                echo "2 - Utilitario urbano\n";
                echo "3 - Caminhao refrigerado\n";

                $categoria = lerTexto("Categoria: ");

                $placa = lerTexto("Placa: ");
                $modelo = lerTexto("Modelo: ");
                $ano = lerInteiro("Ano de fabricacao: ");
                $capacidade = lerNumero("Capacidade maxima em kg: ");
                $custo = lerNumero("Custo operacional por km: ");

                if ($categoria === "1") {

                    $cilindrada = lerInteiro("Cilindrada: ");

                    $bau = lerTexto("Possui bau termico? (s/n): ");

                    $veiculo = new Motocicleta(
                        $placa,
                        $modelo,
                        $ano,
                        $capacidade,
                        $cilindrada,
                        strtolower($bau) === "s",
                        $custo
                    );

                } elseif ($categoria === "2") {

                    $volume = lerNumero("Volume interno em m3: ");

                    $eixo = lerTexto("Possui eixo reforcado? (s/n): ");

                    $veiculo = new Utilitario(
                        $placa,
                        $modelo,
                        $ano,
                        $capacidade,
                        $volume,
                        strtolower($eixo) === "s",
                        $custo
                    );

                } elseif ($categoria === "3") {

                    $temperatura = lerNumero(
                        "Temperatura minima (digite o valor absoluto, ex. 10 para -10): "
                    );

                    $certificado = lerTexto(
                        "Possui certificado sanitario valido? (s/n): "
                    );

                    $veiculo = new CaminhaoRefrigerado(
                        $placa,
                        $modelo,
                        $ano,
                        $capacidade,
                        -$temperatura,
                        strtolower($certificado) === "s",
                        $custo
                    );

                } else {
                    throw new InvalidArgumentException(
                        "Categoria de veiculo invalida."
                    );
                }

                $veiculos[] = $veiculo;

                echo "\nVeiculo cadastrado com sucesso!\n";

                break;

            // =====================================
            // 3 - CRIAR PEDIDO
            // =====================================

            case "3":

                if (empty($clientes)) {
                    echo "Cadastre um cliente primeiro.\n";
                    break;
                }

                echo "\n----------- NOVO PEDIDO DE FRETE -----------\n";

                exibirClientes($clientes);

                $indiceCliente = lerInteiro(
                    "Numero do cliente: "
                ) - 1;

                $cliente = localizarCliente(
                    $clientes,
                    $indiceCliente
                );

                if ($cliente === null) {
                    echo "Cliente nao encontrado.\n";
                    break;
                }

                $destinatario = lerTexto("Destinatario: ");
                $distancia = lerNumero("Distancia em km: ");

                $pedido = new PedidoFrete(
                    $cliente,
                    $destinatario,
                    $distancia
                );

                $quantidadeVolumes = lerInteiro(
                    "Quantidade de volumes: "
                );

                if ($quantidadeVolumes <= 0) {
                    throw new InvalidArgumentException(
                        "O pedido precisa ter pelo menos um volume."
                    );
                }

                for ($i = 1; $i <= $quantidadeVolumes; $i++) {

                    echo "\nVolume " . $i . "\n";

                    $descricao = lerTexto("Descricao: ");
                    $peso = lerNumero("Peso em kg: ");

                    echo "Natureza da carga:\n";
                    echo "1 - Carga geral\n";
                    echo "2 - Carga fragil\n";
                    echo "3 - Carga refrigerada\n";

                    $naturezaOpcao = lerTexto("Escolha: ");

                    if ($naturezaOpcao === "1") {
                        $natureza = "carga geral";
                    } elseif ($naturezaOpcao === "2") {
                        $natureza = "carga frágil";
                    } elseif ($naturezaOpcao === "3") {
                        $natureza = "carga refrigerada";
                    } else {
                        throw new InvalidArgumentException(
                            "Natureza de carga invalida."
                        );
                    }

                    $volume = new Volume(
                        $descricao,
                        $peso,
                        $natureza
                    );

                    $pedido->adicionarVolume($volume);
                }

                $pedidos[] = $pedido;

                echo "\nPedido criado com sucesso!\n";
                echo "Codigo de rastreamento: " .
                    $pedido->getCodigoRastreamento() . "\n";

                echo "Peso total: " .
                    number_format(
                        $pedido->getPesoTotal(),
                        2,
                        ',',
                        '.'
                    ) . " kg\n";

                echo "Agora aloque um veiculo para o pedido.\n";

                break;

            // =====================================
            // 4 - ALOCAR VEICULO
            // =====================================

            case "4":

                if (empty($pedidos) || empty($veiculos)) {
                    echo "Cadastre pedidos e veiculos primeiro.\n";
                    break;
                }

                $codigo = lerTexto(
                    "Codigo de rastreamento do pedido: "
                );

                $pedido = localizarPedido($pedidos, $codigo);

                if ($pedido === null) {
                    echo "Pedido nao encontrado.\n";
                    break;
                }

                exibirVeiculos($veiculos);

                $indiceVeiculo = lerInteiro(
                    "Numero do veiculo: "
                ) - 1;

                if (!isset($veiculos[$indiceVeiculo])) {
                    echo "Veiculo nao encontrado.\n";
                    break;
                }

                $pedido->alocarVeiculo(
                    $veiculos[$indiceVeiculo]
                );

                echo "\nVeiculo alocado com sucesso!\n";

                break;

            // =====================================
            // 5 - ATUALIZAR STATUS
            // =====================================

            case "5":

                $codigo = lerTexto(
                    "Codigo de rastreamento: "
                );

                $pedido = localizarPedido($pedidos, $codigo);

                if ($pedido === null) {
                    echo "Pedido nao encontrado.\n";
                    break;
                }

                echo "Status atual: " .
                    $pedido->getStatus() . "\n";

                echo "\nNovo status:\n";
                echo "1 - Em separacao\n";
                echo "2 - Saiu para entrega\n";
                echo "3 - Entregue\n";
                echo "4 - Ocorrencia na entrega\n";

                $statusOpcao = lerTexto("Escolha: ");

                $statusMap = [
                    "1" => "Em separacao",
                    "2" => "Saiu para entrega",
                    "3" => "Entregue",
                    "4" => "Ocorrencia na entrega"
                ];

                if (!isset($statusMap[$statusOpcao])) {
                    echo "Opcao de status invalida.\n";
                    break;
                }

                $statusAnterior = $pedido->getStatus();
                $novoStatus = $statusMap[$statusOpcao];

                if ($statusAnterior === "Entregue") {
                    echo "Este pedido ja foi entregue.\n";
                    break;
                }

                $descricao = lerTexto(
                    "Descricao do evento: "
                );

                $pedido->atualizarStatus(
                    $novoStatus,
                    $descricao
                );

                if ($novoStatus === "Entregue") {

                    $veiculo = $pedido->getVeiculo();

                    if ($veiculo !== null) {
                        $veiculo->liberar();

                        echo "\nVeiculo liberado com sucesso!\n";
                    }
                }

                echo "\nStatus atualizado com sucesso!\n";

                break;

            // =====================================
            // 6 - CONSULTAR PEDIDO
            // =====================================

            case "6":

                $codigo = lerTexto(
                    "Digite o codigo de rastreamento: "
                );

                $relatorioPedidos->gerarRelatorio(
                    $pedidos,
                    $codigo
                );

                break;

            // =====================================
            // 7 - RELATORIO DA FROTA
            // =====================================

            case "7":

                $relatorioFrota->gerarRelatorio(
                    $veiculos
                );

                break;

            // =====================================
            // 8 - RELATORIO FINANCEIRO
            // =====================================

            case "8":

                $relatorioFinanceiro->gerarRelatorio(
                    $pedidos
                );

                break;

            // =====================================
            // 9 - SAIR
            // =====================================

            case "9":

                echo "\nEncerrando o sistema VeloCargo...\n";
                echo "Obrigado por utilizar nosso sistema!\n";

                break;

            default:

                echo "\nOpcao invalida. Tente novamente.\n";

                break;
        }

        // Salva os dados após cada operação concluída.
        $persistencia->salvar(
            $clientes,
            $veiculos,
            $pedidos
        );

    } catch (Throwable $erro) {

        echo "\nERRO: " . $erro->getMessage() . "\n";
    }

    if ($opcao !== "9") {
        pausar();
    }

} while ($opcao !== "9");
