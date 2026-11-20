
<?php

require_once __DIR__ . '/../Models/Veiculo.php';
require_once __DIR__ . '/../Models/Motocicleta.php';
require_once __DIR__ . '/../Models/Utilitario.php';
require_once __DIR__ . '/../Models/CaminhaoRefrigerado.php';

class RelatorioFrota
{
    public function gerarRelatorio(array $veiculos): void
    {
        echo "\n";
        echo "====================================================\n";
        echo "              RELATORIO DA FROTA\n";
        echo "                  VELOCARGO\n";
        echo "====================================================\n\n";

        if (empty($veiculos)) {
            echo "Nenhum veiculo cadastrado na frota.\n";
            return;
        }

        $disponiveis = 0;
        $emRota = 0;

        foreach ($veiculos as $veiculo) {

            if (!$veiculo instanceof Veiculo) {
                continue;
            }

            echo "-----------------------------------------------\n";

            if ($veiculo instanceof Motocicleta) {
                $categoria = "Motocicleta";
            } elseif ($veiculo instanceof Utilitario) {
                $categoria = "Utilitario urbano";
            } elseif ($veiculo instanceof CaminhaoRefrigerado) {
                $categoria = "Caminhao refrigerado";
            } else {
                $categoria = "Veiculo nao identificado";
            }

            echo "Categoria: " . $categoria . "\n";
            echo "Placa: " . $veiculo->getPlaca() . "\n";
            echo "Modelo: " . $veiculo->getModelo() . "\n";
            echo "Ano: " . $veiculo->getAnoFabricacao() . "\n";

            echo "Capacidade maxima: " .
                number_format($veiculo->getCapacidadeMaxima(), 2, ',', '.') .
                " kg\n";

            echo "Custo operacional por km: R$ " .
                number_format($veiculo->calcularCustoPorKm(), 2, ',', '.') .
                "\n";

            if ($veiculo->isDisponivel()) {
                echo "Disponibilidade: DISPONIVEL\n";
                $disponiveis++;
            } else {
                echo "Disponibilidade: EM ROTA\n";
                $emRota++;
            }

            // Caracteristicas especificas da motocicleta.
            if ($veiculo instanceof Motocicleta) {
                echo "Cilindrada: " .
                    $veiculo->getCilindrada() . " cc\n";

                echo "Bau termico: " .
                    ($veiculo->possuiBauTermico() ? "SIM" : "NAO") . "\n";
            }

            // Caracteristicas especificas do utilitario.
            elseif ($veiculo instanceof Utilitario) {
                echo "Volume interno: " .
                    $veiculo->getVolumeInterno() . " m3\n";

                echo "Eixo reforcado: " .
                    ($veiculo->possuiEixoReforcado() ? "SIM" : "NAO") . "\n";
            }

            // Caracteristicas especificas do caminhao refrigerado.
            elseif ($veiculo instanceof CaminhaoRefrigerado) {
                echo "Temperatura minima: " .
                    $veiculo->getTemperaturaMinima() . " graus\n";

                echo "Certificado sanitario: " .
                    ($veiculo->possuiCertificadoSanitario() ? "SIM" : "NAO") . "\n";
            }

            echo "\n";
        }

        echo "====================================================\n";
        echo "             RESUMO DA FROTA\n";
        echo "====================================================\n";

        echo "Total de veiculos: " . ($disponiveis + $emRota) . "\n";
        echo "Veiculos disponiveis: " . $disponiveis . "\n";
        echo "Veiculos em rota: " . $emRota . "\n";

        echo "====================================================\n";
    }
}
