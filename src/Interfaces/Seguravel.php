
<?php

interface Seguravel
{
    public function calcularPremio(float $valorBase): float;

    public function emitirApolice(): string;
}
