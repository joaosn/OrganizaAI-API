<?php

/*
3 parametro define se rota e privada ou não
passar token jwt cadastrado no config.php por enqunato
abra Pasta DOCS
*/



use core\Router;

$router = new Router();

//-----------------Relatorios-----------------//

// Rota principal
$router->get('/', 'HomeController@index');


//-----------------LOGIN-----------------//
$router->get('/sair', 'LoginController@logout', true);
$router->get('/validaToken', 'LoginController@validaToken');
$router->post('/login', 'LoginController@verificarLogin');
$router->get('/listar-usuarios', 'LoginController@listarUsuarios', true);
$router->post('/atuliza-tema', 'LoginController@atulizaThema', true);

//-----------------CLIENTES-----------------//

// CRUD Clientes
$router->get('/clientes', 'ClientesController@listarClientes', true);
$router->get('/clientes/{id}', 'ClientesController@buscarCliente', true);
$router->get('/clientes/cpf/{cpf_cnpj}', 'ClientesController@buscarPorCpfCnpj', true);
$router->get('/clientes/pesquisar', 'ClientesController@pesquisarClientes', true);
$router->post('/clientes', 'ClientesController@criarCliente', true);
$router->put('/clientes', 'ClientesController@atualizarCliente', true);
$router->delete('/clientes', 'ClientesController@excluirCliente', true);

// Endereços dos Clientes
$router->get('/clientes/{id}/enderecos', 'ClientesController@listarEnderecos', true);
$router->post('/clientes/enderecos', 'ClientesController@adicionarEndereco', true);
$router->put('/clientes/enderecos', 'ClientesController@atualizarEndereco', true);
$router->delete('/clientes/enderecos', 'ClientesController@removerEndereco', true);
$router->put('/clientes/enderecos/principal', 'ClientesController@definirEnderecoPrincipal', true);

// Contatos dos Clientes  
$router->get('/clientes/{id}/contatos', 'ClientesController@listarContatos', true);
$router->post('/clientes/contatos', 'ClientesController@adicionarContato', true);
$router->put('/clientes/contatos', 'ClientesController@atualizarContato', true);
$router->delete('/clientes/contatos', 'ClientesController@removerContato', true);
$router->put('/clientes/contatos/principal', 'ClientesController@definirContatoPrincipal', true);

//-----------------SISTEMAS-----------------//

// CRUD Sistemas
$router->get('/sistemas', 'SistemasController@listarSistemas', true);
$router->get('/sistemas/ativos', 'SistemasController@listarSistemasAtivos', true);
$router->get('/sistemas/{id}', 'SistemasController@buscarSistema', true);
$router->get('/sistemas/pesquisar', 'SistemasController@pesquisarSistemas', true);
$router->post('/sistemas', 'SistemasController@criarSistema', true);
$router->put('/sistemas', 'SistemasController@atualizarSistema', true);
$router->delete('/sistemas', 'SistemasController@excluirSistema', true);

// Planos dos Sistemas
$router->get('/sistemas/{id}/planos', 'SistemasController@listarPlanos', true);
$router->get('/sistemas/planos/ativos', 'SistemasController@listarPlanosAtivos', true);
$router->get('/sistemas/planos/{id}', 'SistemasController@buscarPlano', true);
$router->post('/sistemas/planos', 'SistemasController@criarPlano', true);
$router->put('/sistemas/planos', 'SistemasController@atualizarPlano', true);
$router->delete('/sistemas/planos', 'SistemasController@excluirPlano', true);

// Add-ons dos Sistemas
$router->get('/sistemas/{id}/addons', 'SistemasController@listarAddons', true);
$router->get('/sistemas/addons/ativos', 'SistemasController@listarAddonsAtivos', true);
$router->get('/sistemas/addons/{id}', 'SistemasController@buscarAddon', true);
$router->post('/sistemas/addons', 'SistemasController@criarAddon', true);
$router->put('/sistemas/addons', 'SistemasController@atualizarAddon', true);
$router->delete('/sistemas/addons', 'SistemasController@excluirAddon', true);

//-----------------ASSINATURAS-----------------//

// CRUD Assinaturas
$router->get('/assinaturas', 'AssinaturasController@listarAssinaturas', true);
$router->get('/assinaturas/ativas', 'AssinaturasController@listarAtivas', true);
$router->get('/assinaturas/vencendo', 'AssinaturasController@listarVencendo', true);
$router->get('/assinaturas/{idassinatura}', 'AssinaturasController@buscarAssinatura', true);
$router->get('/assinaturas/cliente/{idcliente}', 'AssinaturasController@listarPorCliente', true);
$router->get('/assinaturas/sistema/{idsistema}', 'AssinaturasController@listarPorSistema', true);
$router->get('/assinaturas/{idassinatura}/addons', 'AssinaturasController@listarAddons', true);
$router->get('/assinaturas/custo-total/{idassinatura}', 'AssinaturasController@calcularCustoTotal', true);
$router->post('/assinaturas', 'AssinaturasController@criarAssinatura', true);
$router->put('/assinaturas', 'AssinaturasController@atualizarAssinatura', true);
$router->put('/assinaturas/alterar-status', 'AssinaturasController@alterarStatus', true);
$router->delete('/assinaturas', 'AssinaturasController@excluirAssinatura', true);

// Add-ons de Assinaturas
$router->post('/assinaturas/addon', 'AssinaturasController@adicionarAddon', true);
$router->put('/assinaturas/addon', 'AssinaturasController@atualizarAddon', true);
$router->delete('/assinaturas/addon', 'AssinaturasController@removerAddon', true);

//-----------------AUDITORIA DE PREÇOS-----------------//

// Histórico de Preços
$router->get('/auditoria/precos', 'AuditoriaController@listarHistorico', true);
$router->get('/auditoria/precos/{id}', 'AuditoriaController@buscarAlteracao', true);
$router->get('/auditoria/precos/referencia/{tipo}/{id}', 'AuditoriaController@historicoCompleto', true);
$router->get('/auditoria/precos/recentes', 'AuditoriaController@recentes', true);
$router->get('/auditoria/precos/relatorio/periodo', 'AuditoriaController@relatorioPeriodo', true);
$router->post('/auditoria/precos/registrar', 'AuditoriaController@registrarAlteracao', true);
$router->post('/auditoria/precos/cleanup', 'AuditoriaController@cleanup', true);

//-----------------RELATÓRIOS-----------------//

// Relatórios
$router->get('/relatorios/assinaturas-resumo', 'RelatoriosController@assinaturasResumo', true);
$router->get('/relatorios/receita-mensal', 'RelatoriosController@receitaMensal', true);
$router->get('/relatorios/sistemas-vendidos', 'RelatoriosController@sistemasVendidos', true);
$router->get('/relatorios/clientes-ativos', 'RelatoriosController@clientesAtivos', true);
$router->get('/relatorios/dashboard', 'RelatoriosController@dashboard', true);
$router->get('/relatorios/receita-periodo', 'RelatoriosController@receitaPeriodo', true);

//-----------------ASSINATURAS AVANÇADO-----------------//

// Funcionalidades Avançadas
$router->get('/assinaturas-avancado/para-renovar', 'AssinaturasAvancadoController@obterParaRenovar', true);
$router->post('/assinaturas-avancado/renovar', 'AssinaturasAvancadoController@renovar', true);
$router->post('/assinaturas-avancado/calcular-prorrata', 'AssinaturasAvancadoController@calcularProrrata', true);
$router->post('/assinaturas-avancado/mudar-plano', 'AssinaturasAvancadoController@mudarPlano', true);
$router->post('/assinaturas-avancado/cancelar', 'AssinaturasAvancadoController@cancelar', true);
$router->get('/assinaturas-avancado/historico/{idassinatura}', 'AssinaturasAvancadoController@obterHistorico', true);
