classDiagram
direction TB

class Veiculo {
    <<abstract>>
    -string placa
    -string modelo
    -int anoFabricacao
    -float capacidadeMaxima
    -bool disponivel
    +getPlaca() string
    +getModelo() string
    +getAnoFabricacao() int
    +getCapacidadeMaxima() float
    +isDisponivel() bool
    +reservar() void
    +liberar() void
    +validarEncomenda(float peso, string natureza) void
    +calcularCustoPorKm()* float
    #validarCaracteristicasCarga()* void
}

class Motocicleta {
    -int cilindrada
    -bool bauTermico
    -float custoPorKm
    +getCilindrada() int
    +possuiBauTermico() bool
    +calcularCustoPorKm() float
    #validarCaracteristicasCarga() void
}

class Utilitario {
    -float volumeInterno
    -bool eixoReforcado
    -float custoPorKm
    +getVolumeInterno() float
    +possuiEixoReforcado() bool
    +calcularCustoPorKm() float
    #validarCaracteristicasCarga() void
}

class CaminhaoRefrigerado {
    -float temperaturaMinima
    -bool certificadoSanitarioVigente
    -float custoPorKm
    +getTemperaturaMinima() float
    +possuiCertificadoSanitario() bool
    +calcularCustoPorKm() float
    #validarCaracteristicasCarga() void
}

class Cliente {
    -string nome
    -string documento
    -string enderecoColeta
    -string tipoCliente
    +getNome() string
    +getDocumento() string
    +getEnderecoColeta() string
    +getTipoCliente() string
    +isCorporativo() bool
}

class Volume {
    -string descricao
    -float peso
    -string natureza
    +getDescricao() string
    +getPeso() float
    +getNatureza() string
    +isFragil() bool
    +isRefrigerada() bool
}

class PedidoFrete {
    -static int proximoCodigo
    -static int totalPedidosCriados
    -string codigoRastreamento
    -Cliente clienteRemetente
    -string destinatario
    -float distanciaKm
    -string status
    -array volumes
    -Veiculo veiculo
    -array historico
    +getCodigoRastreamento() string
    +getCliente() Cliente
    +getDestinatario() string
    +getDistanciaKm() float
    +getStatus() string
    +getPesoTotal() float
    +getTotalPedidosCriados() int
    +adicionarVolume(Volume volume) void
    +atualizarStatus(string novoStatus) void
    +getHistorico() array
    +alocarVeiculo(Veiculo veiculo) void
    +restaurar() PedidoFrete
    +restaurarContadores() void
}

class Seguravel {
    <<interface>>
    +calcularPremio(float valorBase) float
    +emitirApolice() string
}

class SeguroFrete {
    -static int proximaApolice
    -static array segurosEmitidos
    -float taxaPremio
    +calcularPremio(float valorBase) float
    +emitirApolice() string
    +exigeSeguro(PedidoFrete pedido) bool
    +obterSeguroPedido() array
    +getTaxaPremio() float
}

class CalculadoraFrete {
    +calcular(PedidoFrete pedido) array
}

class PersistenciaJSON {
    -string caminhoArquivo
    +salvar(array clientes, array veiculos, array pedidos) void
    +carregar() array
    -exportarVeiculo(Veiculo veiculo) array
    -importarVeiculo(array dados) Veiculo
}

class RelatorioFrota {
    +gerarRelatorio(array veiculos) void
}

class RelatorioPedidos {
    +gerarRelatorio(array pedidos) void
}

class RelatorioFinanceiro {
    +gerarRelatorio(array pedidos) void
}

Veiculo <|-- Motocicleta
Veiculo <|-- Utilitario
Veiculo <|-- CaminhaoRefrigerado

Seguravel <|.. SeguroFrete

PedidoFrete --> "1" Cliente : cliente remetente
PedidoFrete "1" *-- "1..*" Volume : volumes
PedidoFrete --> "0..1" Veiculo : veiculo alocado

CalculadoraFrete ..> PedidoFrete : calcula frete
SeguroFrete ..> PedidoFrete : verifica seguro
PersistenciaJSON ..> Cliente : persiste
PersistenciaJSON ..> Veiculo : persiste
PersistenciaJSON ..> PedidoFrete : persiste

RelatorioFrota ..> Veiculo : consulta frota
RelatorioPedidos ..> PedidoFrete : consulta pedidos
RelatorioFinanceiro ..> PedidoFrete : consulta financeiro