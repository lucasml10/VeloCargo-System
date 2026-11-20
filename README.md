# VeloCargo System

### Sistema de Gestão de Logística e Distribuição

Projeto acadêmico desenvolvido em PHP, com foco nos conceitos de **Programação Orientada a Objetos (POO)**, para gerenciamento de veículos, clientes, pedidos de frete, seguros, rastreamento e relatórios operacionais.

## Sobre o projeto

O VeloCargo System é uma aplicação executada via terminal que simula a operação de uma empresa de logística e distribuição.

O sistema permite cadastrar clientes e veículos, criar pedidos de frete, validar cargas de acordo com as características dos veículos, calcular valores de transporte, gerenciar seguros e acompanhar o histórico de movimentações dos pedidos.

A aplicação utiliza persistência de dados em arquivos JSON, permitindo que as informações continuem disponíveis após o encerramento do programa.

## Funcionalidades

- Cadastro de clientes comuns e corporativos.
- Cadastro de motocicletas, utilitários urbanos (VUC) e caminhões refrigerados.
- Criação de pedidos de frete com volumes e diferentes naturezas de carga.
- Geração automática de códigos de rastreamento.
- Cálculo automático do peso total dos pedidos.
- Validação de capacidade e compatibilidade dos veículos.
- Alocação e liberação de veículos.
- Controle de disponibilidade da frota.
- Atualização de status e histórico cronológico dos pedidos.
- Cálculo de frete considerando distância, veículo e características da carga.
- Aplicação de descontos para clientes corporativos conforme as regras do sistema.
- Cálculo de seguro e emissão de apólices.
- Relatórios de frota, pedidos e movimentação financeira.
- Persistência de dados em arquivos JSON.

## Tecnologias utilizadas

- PHP 8.5
- Programação Orientada a Objetos (POO)
- JSON
- Mermaid para documentação UML
- Visual Studio Code

## Conceitos de Programação Orientada a Objetos

O projeto utiliza os seguintes conceitos:

- **Encapsulamento:** atributos privados e métodos de acesso para proteger os dados.
- **Herança:** classes específicas de veículos herdam características da classe abstrata `Veiculo`.
- **Polimorfismo:** cada categoria de veículo implementa seu próprio cálculo de custo por quilômetro e validação de carga.
- **Abstração:** utilização de classe abstrata para definir comportamentos comuns aos veículos.
- **Interfaces:** utilização da interface `Seguravel` para padronizar operações de seguro.
- **Composição:** pedidos de frete são constituídos por volumes.
- **Associação:** relacionamento entre pedidos, clientes e veículos.

## Estrutura do projeto

```text
VeloCargo/
│
├── docs/
│   ├── diagrama-classes.md
│   ├── diagrama-classes.svg
│   └── diagrama-classes.pdf
│
├── dados/
│   ├── velocargo.json
│   └── teste_velocargo.json
│
├── src/
│   ├── Interfaces/
│   │   └── Seguravel.php
│   │
│   ├── Models/
│   │   ├── Veiculo.php
│   │   ├── Motocicleta.php
│   │   ├── Utilitario.php
│   │   ├── CaminhaoRefrigerado.php
│   │   ├── Cliente.php
│   │   ├── Volume.php
│   │   └── PedidoFrete.php
│   │
│   └── Services/
│       ├── CalculadoraFrete.php
│       ├── SeguroFrete.php
│       ├── PersistenciaJSON.php
│       ├── RelatorioFrota.php
│       ├── RelatorioPedidos.php
│       └── RelatorioFinanceiro.php
│
├── index.php
├── README.md
└── arquivos de teste
```

## Como executar o projeto

### Pré-requisitos

- PHP instalado.
- Terminal ou ambiente de desenvolvimento compatível.
- Visual Studio Code (opcional).

### Execução

1. Abra o terminal na pasta principal do projeto.
2. Verifique a instalação do PHP:

```bash
php -v
```

3. Execute a aplicação:

```bash
php index.php
```

4. Utilize as opções apresentadas no menu para acessar as funcionalidades do sistema.

## Persistência de dados

O sistema utiliza arquivos JSON para armazenar informações de clientes, veículos e pedidos.

O arquivo principal de dados é:

```text
dados/velocargo.json
```

As informações são carregadas na inicialização e atualizadas após as operações realizadas no sistema.

## Testes realizados

O projeto possui arquivos de testes independentes para validar diferentes funcionalidades.

Exemplos de execução:

```bash
php teste_frota.php
php teste_clientes.php
php teste_volumes.php
php teste_pedidos.php
php teste_calculo_frete.php
php teste_seguro.php
php teste_historico.php
php teste_persistencia.php
php teste_alocacao_veiculos.php
php teste_validacao_moto.php
php teste_validacao_utilitario.php
php teste_validacao_caminhao.php
```

Também existem testes específicos para os relatórios de frota, pedidos e financeiro.

## Diagrama UML

O diagrama de classes documenta a arquitetura do sistema, suas classes, atributos, métodos e relacionamentos.

Os arquivos estão disponíveis na pasta `docs/`:

- `diagrama-classes.pdf`
- `diagrama-classes.svg`
- `diagrama-classes.md`

## Objetivo acadêmico

O projeto foi desenvolvido como parte da avaliação acadêmica de Análise e Desenvolvimento de Sistemas, com o objetivo de aplicar conceitos de orientação a objetos na construção de uma solução prática para o gerenciamento de operações logísticas.

---

**VeloCargo System**  
Sistema de Gestão de Logística e Distribuição  
Projeto acadêmico — Análise e Desenvolvimento de Sistemas.
