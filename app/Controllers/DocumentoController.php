<?php

class DocumentoController
{
    public function index(): void
    {
        $this->render('index');
    }

    public function create(): void
    {
        $this->render('create');
    }

    public function show(): void
    {
        $this->render('show');
    }

    private function render(string $nombre): void
    {
        $vista = __DIR__ . '/../Views/documentos/' . $nombre . '.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
