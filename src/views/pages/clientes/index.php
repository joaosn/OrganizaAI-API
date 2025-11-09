<?php
// OrganizaAI-API/src/views/pages/clientes/index.php

// Simplesmente renderiza a view de clientes. O conteúdo será preenchido via JS.
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Clientes</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Clientes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableClientes" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome/Razão Social</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Nome/Razão Social</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
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

<?php $render('footer'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        carregarClientes();
    });

    async function carregarClientes() {
        const tbody = document.querySelector('#dataTableClientes tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-3 text-muted">Carregando clientes...</p>
                </td>
            </tr>
        `;

        try {
            // Rota de API configurada em src/routes.php
            const response = await fetchComToken('<?php echo $base; ?>/api/clientes/listar');
            const data = await response.json();

            if (data.error) {
                throw new Error(data.message || 'Erro desconhecido ao carregar clientes.');
            }

            const clientes = data.result || [];
            
            if (clientes.length > 0) {
                tbody.innerHTML = clientes.map(cliente => {
                    const statusBadge = cliente.status === 'Ativo' 
                        ? '<span class="badge badge-success">Ativo</span>' 
                        : '<span class="badge badge-danger">Inativo</span>';
                    
                    return `
                        <tr>
                            <td>${cliente.idcliente}</td>
                            <td>${escapeHtml(cliente.nome_razao)}</td>
                            <td>${cliente.cpf_cnpj}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" title="Detalhes"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Nenhum cliente encontrado.</p>
                        </td>
                    </tr>
                `;
            }

        } catch (error) {
            console.error('Erro ao carregar clientes:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-times-circle text-danger fa-3x mb-3"></i>
                        <p class="text-danger">Erro ao carregar clientes: ${error.message}</p>
                        <button class="btn btn-primary" onclick="carregarClientes()">
                            <i class="fas fa-sync"></i> Tentar Novamente
                        </button>
                    </td>
                </tr>
            `;
        }
    }
</script>
