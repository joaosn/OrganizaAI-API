<?php $render('header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Sistemas</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Sistemas</h6>
        </div>
        <div class="card-bo<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Sistemas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabelaSistemas" width="100%" cellspacing="0">idth="100%" cellspacing="0">idth="100%" cellspacing="0">
                    <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-3 text-muted">Carregando sistemas...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para exibir Chave de API -->


<script>
    // Carregar sistemas via AJAX
    async function carregarSistemas() {
        const tbody = document.querySelector('#tabelaSistemas tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-3 text-muted">Carregando sistemas...</p>
                </td>
            </tr>
        `;

        try {
            const response = await fetchComToken('<?php echo $base; ?>/api/sistemas/listar');
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.message || 'Erro desconhecido ao carregar sistemas.');
            }

            const sistemas = data.result || [];
            
            if (sistemas.length > 0) {
                tbody.innerHTML = sistemas.map(sistema => `
                    <tr>
                        <td>${sistema.idsistema}</td>
                        <td><strong>${escapeHtml(sistema.nome)}</strong></td>
                        <td>${escapeHtml(sistema.descricao || 'N/A')}</td>
                        <td>
                            ${sistema.ativo == 1 ? 
                                '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Ativo</span>' : 
                                '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Inativo</span>'
                            }
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="#" class="btn btn-outline-secondary" title="Editar Sistema">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deletarSistema(${sistema.idsistema}, '${escapeHtml(sistema.nome)}', '<?php echo $base; ?>')"
                                        title="Deletar Sistema">
                                    <i class="fas fa-trash"></i> Deletar
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Nenhum sistema cadastrado ainda.</p>
                                <a href="#" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Criar Novo Sistema
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Erro ao carregar sistemas:', error);
            const tbody = document.querySelector('#tabelaSistemas tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-times-circle text-danger fa-3x mb-3"></i>
                        <p class="text-danger">Erro ao carregar sistemas: ${error.message}</p>
                        <button class="btn btn-primary" onclick="carregarSistemas()">
                            <i class="fas fa-sync"></i> Tentar Novamente
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text?.toString().replace(/[&<>"']/g, m => map[m]) || '';
    }

    // Carregar ao iniciar a página
    document.addEventListener('DOMContentLoaded', carregarSistemas);
</script>

<?php $render('footer'); ?>
