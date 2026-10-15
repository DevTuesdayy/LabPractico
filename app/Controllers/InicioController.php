<?php

class InicioController extends Controller
{
    public function index(): void
    {
        $this->render(
            'home/index',
            'DocuFlow | Gestión Documental',
            'home-page',
            ['ui-refresh.css'],
            false
        );
    }
}
