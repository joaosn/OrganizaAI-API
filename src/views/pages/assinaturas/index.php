<?php
// OrganizaAI-API/src/views/pages/assinaturas/index.php

// Simplesmente renderiza a view de assinaturas. O conteúdo será preenchido via JS.
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Assinaturas</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Assinaturas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableAssinaturas" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Sistema</th>
                            <th>Plano</th>
                            <th>Status</th>
                            <th>Próximo Vencimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Sistema</th>
                            <th>Plano</th>
                            <th>Status</th>
                            <th>Próximo Vencimento</th>
                            <th>Ações</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <!-- Dados serão carregados via AJAX/JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
