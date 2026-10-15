<?php

abstract class Controller
{
    protected function render(
        string $archivo,
        string $titulo,
        string $claseBody,
        array $estilos,
        bool $cargarScripts = true
    ): void {
        $vista = __DIR__ . '/../Views/' . $archivo . '.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
