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

<?php $render('footer'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        carregarAssinaturas();
    });

    async function carregarAssinaturas() {
        const tbody = document.querySelector('#dataTableAssinaturas tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-3 text-muted">Carregando assinaturas...</p>
                </td>
            </tr>
        `;

        try {
            // Rota de API configurada em src/routes.php
            const response = await fetchComToken('<?php echo $base; ?>/api/assinaturas/listar');
            const data = await response.json();

            if (data.error) {
                throw new Error(data.message || 'Erro desconhecido ao carregar assinaturas.');
            }

            const assinaturas = data.result || [];
            
            if (assinaturas.length > 0) {
                tbody.innerHTML = assinaturas.map(assinatura => {
                    const statusBadge = getStatusBadge(assinatura.status);
                    const proximoVencimento = formatarData(assinatura.proximo_vencimento);
                    
                    return `
                        <tr>
                            <td>${assinatura.idassinatura}</td>
                            <td>${escapeHtml(assinatura.nome_cliente || 'N/A')}</td>
                            <td>${escapeHtml(assinatura.nome_sistema || 'N/A')}</td>
                            <td>${escapeHtml(assinatura.nome_plano || 'N/A')}</td>
                            <td>${statusBadge}</td>
                            <td>${proximoVencimento}</td>
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
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Nenhuma assinatura encontrada.</p>
                        </td>
                    </tr>
                `;
            }

        } catch (error) {
            console.error('Erro ao carregar assinaturas:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-times-circle text-danger fa-3x mb-3"></i>
                        <p class="text-danger">Erro ao carregar assinaturas: ${error.message}</p>
                        <button class="btn btn-primary" onclick="carregarAssinaturas()">
                            <i class="fas fa-sync"></i> Tentar Novamente
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    function getStatusBadge(status) {
        switch (status) {
            case 'ativa':
                return '<span class="badge badge-success">Ativa</span>';
            case 'suspensa':
                return '<span class="badge badge-warning">Suspensa</span>';
            case 'cancelada':
                return '<span class="badge badge-danger">Cancelada</span>';
            case 'expirada':
                return '<span class="badge badge-secondary">Expirada</span>';
            default:
                return `<span class="badge badge-light">${status}</span>`;
        }
    }

    function formatarData(dataString) {
        if (!dataString) return 'N/A';
        const data = new Date(dataString);
        return data.toLocaleDateString('pt-BR');
    }
</script>
