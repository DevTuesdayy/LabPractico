<?php

class DocumentoController extends Controller
{
    public function index(): void
    {
        $this->render(
            'documentos/index',
            'Registro de Oficios | DocuFlow',
            'dashboard-page',
            ['dashboard.css', 'ui-refresh.css', 'ui-interactions.css']
        );
    }

    public function create(): void
    {
        $this->render(
            'documentos/create',
            'Registrar documento | DocuFlow',
            'register-page',
            ['register.css', 'ui-refresh.css', 'ui-interactions.css']
        );
    }

    public function show(): void
    {
        $this->render(
            'documentos/show',
            'Detalle del oficio | DocuFlow',
            'dashboard-page detail-page',
            ['dashboard.css', 'ui-refresh.css', 'ui-interactions.css', 'detail.css']
        );
    }
}
