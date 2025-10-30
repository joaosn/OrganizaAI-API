# GitHub Copilot - Instruções do Projeto ORGANIZAAI

## 📋 Visão Geral

Este documento define os padrões, convenções e melhores práticas que devem ser seguidos em **TODAS** as contribuições de código no projeto ORGANIZAAI.

**Sistema**: OrganizaAI - Plataforma de gestão de assinaturas de software  
**Domínio**: Clientes, Sistemas, Assinaturas, Add-ons, Auditoria de Preços  
**Banco**: MySQL com estrutura relacional complexa  
**Plano de Ação**: Consulte `PLANO_ACAO_API_ORGANIZAAI.md` para roadmap completo

## 🏗️ Entidades do Sistema OrganizaAI

### 👥 Módulo Clientes
- **clientes**: PF/PJ com CPF/CNPJ, dados básicos
- **clientes_enderecos**: Múltiplos endereços, um principal  
- **clientes_contatos**: Múltiplos contatos, um principal

### 💻 Módulo Sistemas
- **sistemas**: Catálogo de softwares oferecidos
- **sistemas_planos**: Planos de cada sistema (preços base)
- **sistemas_addons**: Módulos opcionais (complementos)

### 📋 Módulo Assinaturas  
- **assinaturas**: Contratos dos clientes (com preços negociados)
- **assinaturas_addons**: Add-ons contratados por assinatura

### 📊 Módulo Auditoria
- **precos_historico**: Log de alterações de preços/alíquotas

### 📈 Views e Relatórios
- **v_assinaturas_resumo**: Valores atuais com impostos
- **v_assinaturas_total_mensal**: Total mensal incluindo add-ons

## 🏗️ Arquitetura do Backend (PHP)

### Estrutura de Pastas

```
api/
├── core/                  # Classes fundamentais do framework
│   ├── Database.php       # ⭐ Classe de acesso ao banco de dados
│   ├── Controller.php     # Base para todos os controllers
│   ├── Model.php          # Base para todos os models
│   ├── Router.php         # Sistema de rotas
│   └── Request.php        # Manipulação de requisições HTTP
├── src/
│   ├── models/            # Camada de acesso a dados
│   ├── handlers/          # Lógica de negócio
│   ├── controllers/       # Camada de controle HTTP
│   └── routes.php         # Definição de rotas
└── SQL/                   # Queries SQL parametrizadas
    ├── clientes/          # CRUD de clientes
    ├── clientes_enderecos/# Endereços dos clientes  
    ├── clientes_contatos/ # Contatos dos clientes
    ├── sistemas/          # Catálogo de sistemas
    ├── sistemas_planos/   # Planos dos sistemas
    ├── sistemas_addons/   # Add-ons dos sistemas
    ├── assinaturas/       # Contratos de assinatura
    ├── assinaturas_addons/# Add-ons contratados
    ├── precos_historico/  # Auditoria de preços
    └── relatorios/        # Views e relatórios
```

---

## ⚠️ REGRA FUNDAMENTAL: Uso do Database::switchParams()

### ❌ NUNCA FAÇA ISSO (Query Direta com PDO)

```php
// ❌ ERRADO - NÃO USAR
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### ✅ SEMPRE FAÇA ISSO (switchParams)

```php
// ✅ CORRETO - USAR EM 100% DOS CASOS
$resultado = Database::switchParams(
    [':id' => $id],            // 1º: Parâmetros (array)
    'caminho/para/query',      // 2º: Nome do arquivo SQL (sem .sql)
    true,                      // 3º: Executar? true=executa, false=retorna SQL
    false,                     // 4º: Salvar log? (opcional, padrão false)
    true                       // 5º: Usar transação? (opcional, padrão true)
);

// Acessar resultado
if ($resultado['error']) {
    // Tratar erro
    echo $resultado['error'];
} else {
    // Usar dados
    $dados = $resultado['retorno'];
}
```

---

## 📁 Padrão de Organização SQL

### Estrutura de Arquivos SQL

Para cada entidade/funcionalidade, crie uma pasta em `api/SQL/[entidade]/` com as queries necessárias:

```
api/SQL/produto_menu/
├── get_proxima_ordem.sql          # Buscar próximo número de ordem
├── get_ordem_atual.sql            # Buscar ordem atual de um item
├── get_produto_na_ordem.sql       # Buscar item em posição específica
├── set_ordem_temp.sql             # Definir ordem temporária (-1)
└── update_ordem.sql               # Atualizar ordem de um item
```

### Exemplo de Query SQL Parametrizada (OrganizaAI)

**Arquivo**: `api/SQL/clientes/select_by_cpf_cnpj.sql`

```sql
SELECT 
    c.idcliente,
    c.tipo_pessoa,
    c.nome,
    c.nome_fantasia,
    c.cpf_cnpj,
    c.email,
    c.telefone,
    c.ativo,
    c.data_cadastro
FROM clientes c
WHERE c.cpf_cnpj = :cpf_cnpj
  AND c.ativo = 1;
```

**Uso no Model**:

```php
public function buscarPorCpfCnpj($cpf_cnpj) {
    $resultado = Database::switchParams(
        ['cpf_cnpj' => $cpf_cnpj],
        'clientes/select_by_cpf_cnpj',  // Caminho relativo a /SQL/
        true,   // Executar
        false,  // Sem log
        false   // Sem transação (apenas SELECT)
    );

    if ($resultado['error']) {
        throw new Exception($resultado['error']);
    }

    return $resultado['retorno'][0] ?? null;
}
```

---

## 🎯 Padrão MVC + Handler

### 1. Model (Camada de Dados)

**Responsabilidade**: APENAS acesso ao banco de dados

```php
// api/src/models/Clientes.php

class ClientesModel extends Model {
    /**
     * Busca cliente por CPF/CNPJ
     */
    public function buscarPorCpfCnpj($cpf_cnpj) {
        $resultado = Database::switchParams(
            ['cpf_cnpj' => $cpf_cnpj],
            'clientes/select_by_cpf_cnpj',
            true,   // Executar
            false,  // Sem log
            false   // Sem transação (SELECT)
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }

    /**
     * Atualiza a ordem de um produto no menu (com troca de posições)
     */
    public function atualizarOrdem($idempresa, $idparmsmenu, $novaOrdem, $idmenu) {
        // 1. Busca ordem atual
        $ordemAtualResult = Database::switchParams(
            'SQL/produto_menu/get_ordem_atual.sql',
            [
                ':idempresa' => $idempresa,
                ':idparmsmenu' => $idparmsmenu
            ],
            'select'
        );

        if (empty($ordemAtualResult)) {
            throw new Exception("Produto não encontrado");
        }

        $ordemAtual = $ordemAtualResult[0]['ordem'];

        if ($ordemAtual == $novaOrdem) {
            return; // Sem mudança necessária
        }

        // 2. Busca produto na posição desejada
        $produtoNaPosicaoResult = Database::switchParams(
            'SQL/produto_menu/get_produto_na_ordem.sql',
            [
                ':idempresa' => $idempresa,
                ':idmenu' => $idmenu,
                ':ordem' => $novaOrdem
            ],
            'select'
        );

        // 3. Define ordem temporária para o produto atual
        Database::switchParams(
            'SQL/produto_menu/set_ordem_temp.sql',
            [
                ':idempresa' => $idempresa,
                ':idparmsmenu' => $idparmsmenu
            ],
            'update'
        );

        // 4. Se existe produto na posição desejada, move para posição antiga
        if (!empty($produtoNaPosicaoResult)) {
            $produtoNaPosicao = $produtoNaPosicaoResult[0];
            Database::switchParams(
                'SQL/produto_menu/update_ordem.sql',
                [
                    ':idempresa' => $idempresa,
                    ':idparmsmenu' => $produtoNaPosicao['idparmsmenu'],
                    ':ordem' => $ordemAtual
                ],
                'update'
            );
        }

        // 5. Move produto atual para nova posição
        Database::switchParams(
            'SQL/produto_menu/update_ordem.sql',
            [
                ':idempresa' => $idempresa,
                ':idparmsmenu' => $idparmsmenu,
                ':ordem' => $novaOrdem
            ],
            'update'
        );
    }
}
```

### 2. Handler (Lógica de Negócio)

**Responsabilidade**: Validações e orquestração de models

```php
// api/src/handlers/Menu.php

class MenuHandler {
    private $menuModel;
    private $produtoMenuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
        $this->produtoMenuModel = new ProdutoMenuModel();
    }

    /**
     * Vincula produtos ao menu com ordem automática
     */
    public function vincularProdutoMenu($idempresa, $idmenu, array $idprodutos) {
        // Validações
        if (empty($idprodutos)) {
            throw new Exception("Nenhum produto selecionado");
        }

        // Busca próxima ordem disponível
        $ordemInicial = $this->produtoMenuModel->getProximaOrdem($idempresa, $idmenu);

        // Insere produtos com ordem sequencial
        foreach ($idprodutos as $index => $idproduto) {
            $this->produtoMenuModel->vincular(
                $idempresa,
                $idmenu,
                $idproduto,
                $ordemInicial + $index
            );
        }

        return [
            'success' => true,
            'message' => count($idprodutos) . ' produto(s) vinculado(s) com sucesso'
        ];
    }

    /**
     * Atualiza ordem de produto no menu
     */
    public function atualizarOrdemProdutoMenu($idempresa, $idparmsmenu, $novaOrdem, $idmenu) {
        // Validações
        if ($novaOrdem < 1) {
            throw new Exception("Ordem deve ser maior que zero");
        }

        // Executa atualização
        $this->produtoMenuModel->atualizarOrdem($idempresa, $idparmsmenu, $novaOrdem, $idmenu);

        return [
            'success' => true,
            'message' => 'Ordem atualizada com sucesso'
        ];
    }
}
```

### 3. Controller (Camada HTTP)

**Responsabilidade**: Receber requisição, chamar handler, retornar resposta

```php
// api/src/controllers/MenuController.php

class MenuController extends Controller {
    private $menuHandler;

    // Constantes para validação de campos obrigatórios
    const UPDATEORDEMCAMPOS = ['idempresa', 'idparmsmenu', 'nova_ordem', 'idmenu'];

    public function __construct() {
        parent::__construct();
        $this->menuHandler = new MenuHandler();
    }

    /**
     * PUT /updateOrdemProdutoMenu
     */
    public function updateOrdemProdutoMenu() {
        try {
            // Obtém dados do body
            $data = Controller::getBody();
            
            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, self::UPDATEORDEMCAMPOS);

            // Chama handler
            $result = $this->menuHandler->atualizarOrdemProdutoMenu(
                $data['idempresa'],
                $data['idparmsmenu'],
                $data['nova_ordem'],
                $data['idmenu']
            );

            // Retorna resposta de sucesso
            Controller::response($result, 200);
        } catch (Exception $e) {
            // Retorna resposta de erro
            Controller::rejectResponse($e);
        }
    }
}
```

---

## 🔧 Métodos Principais do Controller

### 1. `Controller::getBody($valida_body = true)`

Obtém dados JSON do body da requisição.

```php
// Obtém dados do body
$data = Controller::getBody();

// $data = [
//     'idempresa' => 1,
//     'nome' => 'Produto X'
// ]

// Permite body vazio
$data = Controller::getBody(false);
```

### 2. `Controller::verificarCamposVazios($campos, $validar)`

Valida campos obrigatórios. Lança `Exception` se algum campo estiver vazio ou ausente.

```php
// Define campos obrigatórios na classe
const ADDCAMPOS = ['idempresa', 'nome', 'preco'];

// Valida
Controller::verificarCamposVazios($data, self::ADDCAMPOS);

// Se algum campo estiver vazio/ausente, lança:
// Exception: "Campo obrigatório não encontrado: nome"
// Exception: "Campo obrigatório vazio: preco"
```

### 3. `Controller::response($item, $status)`

Retorna resposta JSON de sucesso.

```php
// Sucesso (200, 201, etc)
Controller::response(['id' => 123, 'nome' => 'Produto'], 200);

// Retorna:
// {
//     "result": {"id": 123, "nome": "Produto"},
//     "error": false
// }

// Erro (400, 404, etc)
Controller::response(['mensagem' => 'Não encontrado'], 404);

// Retorna:
// {
//     "result": {"mensagem": "Não encontrado"},
//     "error": true
// }
```

### 4. `Controller::rejectResponse(Throwable $msg)`

Retorna resposta JSON de erro (HTTP 400).

```php
try {
    // Código que pode dar erro
    throw new Exception('Produto não encontrado');
} catch (Exception $e) {
    Controller::rejectResponse($e);
}

// Retorna:
// HTTP 400
// {
//     "result": "",
//     "error": "Produto não encontrado"
// }
```

### 5. `Controller::empresa()` e `Controller::usuario()`

Obtém ID da empresa ou usuário logado da sessão.

```php
$idempresa = Controller::empresa();
$iduser = Controller::usuario();
```

### 4. Routes (Definição de Rotas)

```php
// api/src/routes.php

use core\Router;

$router = new Router();

// PUT - Atualizar ordem de produto no menu
$router->put('/updateOrdemProdutoMenu', 'MenuController@updateOrdemProdutoMenu', true);

// PUT - Atualizar ordem de menu
$router->put('/updateOrdemMenu', 'MenuController@updateOrdemMenu', true);

// GET - Buscar produtos do menu
$router->get('/getProdutoMenu/{idempresa}', 'MenuController@getProdutosMenu', true);

// POST - Adicionar produto ao menu
$router->post('/addProdutoMenu', 'MenuController@addProdutoMenu', true);

// DELETE - Remover produto do menu
$router->delete('/deleteProdutoMenu', 'MenuController@deleteProdutoMenu', true);
```

**Padrão das Rotas**:
- Formato: `$router->metodo('/rota', 'Controller@metodo', autenticado)`
- Métodos HTTP: `get`, `post`, `put`, `delete`
- 3º parâmetro (`true`): Rota requer autenticação (JWT token)
- Parâmetros na URL: `{nome_parametro}` são passados como argumentos para o método

**Exemplos de Rotas com Parâmetros**:

```php
// GET com múltiplos parâmetros na URL
$router->get('/getProdutosById/{idempresa}/{idproduto}', 'ProdutosController@getProdutosById', true);

// No Controller, recebe como argumento:
public function getProdutosById($args) {
    $idempresa = $args['idempresa'];
    $idproduto = $args['idproduto'];
    // ...
}

// GET sem parâmetros na URL (vem no body ou query string)
$router->get('/getProdutos/{idempresa}', 'ProdutosController@getProdutos', true);

// POST - recebe dados no body via Controller::getBody()
$router->post('/addProduto', 'ProdutosController@addProduto', true);

// PUT - recebe dados no body via Controller::getBody()
$router->put('/editProduto', 'ProdutosController@editProduto', true);

// DELETE - pode receber parâmetros na URL ou no body
$router->delete('/deleteProduto', 'ProdutosController@deleteProduto', true);
```

**Rotas Públicas vs Privadas**:

```php
// ✅ Rota PRIVADA (requer autenticação JWT)
$router->get('/getProdutos/{idempresa}', 'ProdutosController@getProdutos', true);

// ✅ Rota PÚBLICA (sem autenticação)
$router->get('/getProdutosPaginados', 'ProdutosController@getProdutosPaginados');

// ✅ Rota de login (pública)
$router->post('/login', 'LoginController@verificarLogin');
```

---

## 🔄 Fluxo Completo de uma Requisição

```
Cliente (Frontend)
    ↓
    PUT /updateOrdemProdutoMenu
    {
        idempresa: 1,
        idparmsmenu: 123,
        nova_ordem: 5,
        idmenu: 10
    }
    ↓
routes.php
    ↓
MenuController::updateOrdemProdutoMenu()
    ├─ Controller::getBody() - Obtém dados da requisição
    ├─ Controller::verificarCamposVazios() - Valida campos
    └─ Chama MenuHandler
        ↓
MenuHandler::atualizarOrdemProdutoMenu()
    ├─ Validações de negócio
    └─ Chama ProdutoMenuModel
        ↓
ProdutoMenuModel::atualizarOrdem()
    ├─ Database::switchParams(['idempresa' => 1, ...], 'produto_menu/get_ordem_atual', true)
    ├─ Database::switchParams(['idmenu' => 10, ...], 'produto_menu/get_produto_na_ordem', true)
    ├─ Database::switchParams(['idparmsmenu' => 123], 'produto_menu/set_ordem_temp', true)
    ├─ Database::switchParams(['ordem' => 3], 'produto_menu/update_ordem', true)
    └─ Database::switchParams(['ordem' => 5], 'produto_menu/update_ordem', true)
        ↓
Controller::response($result, 200)
    ↓
    HTTP 200
    {
        "result": {
            "success": true,
            "message": "Ordem atualizada com sucesso"
        },
        "error": false
    }
```

---

## 📊 Database::switchParams() - Referência Completa

### Assinatura do Método

```php
public static function switchParams(
    array $params,          // Parâmetros nomeados [:nome => valor]
    string|array $sqlnome,  // Nome do arquivo SQL OU array ['sql' => '...']
    bool $exec = false,     // Executar? true=executa, false=retorna SQL string
    bool $log = false,      // Salvar log de execução?
    bool $trasaction = true // Usar transação? (rollback automático em erro)
): array                    // Retorna ['retorno' => mixed, 'error' => string|false]
```

### Parâmetros Detalhados

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `$params` | `array` | - | Parâmetros nomeados (sem `:` na chave) |
| `$sqlnome` | `string\|array` | - | Caminho do arquivo SQL OU array com SQL inline |
| `$exec` | `bool` | `false` | `true` = executa, `false` = retorna SQL |
| `$log` | `bool` | `false` | Salva log em `logs/exec{date}-sql.txt` |
| `$trasaction` | `bool` | `true` | Usa transação com rollback automático |

### Retorno

```php
[
    'retorno' => mixed,  // Array de dados (se exec=true) ou string SQL (se exec=false)
    'error'   => string|false  // Mensagem de erro ou false
]
```

### Modos de Uso

#### 1. Executar SQL de Arquivo (Modo Comum)

```php
$resultado = Database::switchParams(
    ['idempresa' => 1, 'idmenu' => 5],
    'produto_menu/get_proxima_ordem',  // Lê de SQL/produto_menu/get_proxima_ordem.sql
    true    // Executa
);

// $resultado['retorno'] = [['proxima_ordem' => 3], ...]
```

#### 2. Retornar SQL sem Executar (Debug)

```php
$resultado = Database::switchParams(
    ['idproduto' => 123],
    'produtos/buscar',
    false   // NÃO executa, apenas retorna SQL montada
);

// $resultado['retorno'] = "SELECT * FROM produtos WHERE idproduto = 123"
```

#### 3. SQL Inline (sem arquivo)

```php
$resultado = Database::switchParams(
    ['id' => 10],
    ['sql' => 'SELECT * FROM tabela WHERE id = :id'],
    true
);
```

#### 4. Com Log e Sem Transação (SELECT)

```php
// Para SELECT não precisa de transação
$resultado = Database::switchParams(
    ['idempresa' => 1],
    'produtos/listar',
    true,   // Executa
    true,   // Salva log
    false   // SEM transação (mais rápido para SELECTs)
);
```

#### 5. Com Transação (INSERT/UPDATE/DELETE)

```php
// Para operações de escrita, USE transação
$resultado = Database::switchParams(
    ['idproduto' => 123, 'preco' => 99.90],
    'produtos/atualizar_preco',
    true,   // Executa
    false,  // Sem log
    true    // COM transação (rollback automático em erro)
);
```

### Exemplos Práticos

#### SELECT

```php
// Buscar produtos por categoria
$resultado = Database::switchParams(
    [
        'idempresa' => 1,
        'idcategoria' => 5
    ],
    'produtos/buscar_por_categoria',
    true,   // Executa
    false,  // Sem log
    false   // Sem transação (é SELECT)
);

if ($resultado['error']) {
    throw new Exception($resultado['error']);
}

$produtos = $resultado['retorno'];
// $produtos = [
//     ['idproduto' => 1, 'nome' => 'Produto A'],
//     ['idproduto' => 2, 'nome' => 'Produto B']
// ]
```

#### INSERT

```php
// Inserir produto
$resultado = Database::switchParams(
    [
        'idempresa' => 1,
        'nome' => 'Produto Novo',
        'preco' => 99.90
    ],
    'produtos/inserir',
    true,   // Executa
    false,  // Sem log
    true    // COM transação
);

if ($resultado['error']) {
    throw new Exception($resultado['error']);
}

// Sucesso: $resultado['retorno'] é array vazio (INSERT não retorna dados)
```

#### UPDATE

```php
// Atualizar preço
$resultado = Database::switchParams(
    [
        'idproduto' => 123,
        'preco' => 149.90
    ],
    'produtos/atualizar_preco',
    true,   // Executa
    false,  // Sem log
    true    // COM transação
);

if ($resultado['error']) {
    throw new Exception($resultado['error']);
}
```

#### DELETE

```php
// Deletar produto
$resultado = Database::switchParams(
    ['idproduto' => 123],
    'produtos/deletar',
    true,   // Executa
    false,  // Sem log
    true    // COM transação
);

if ($resultado['error']) {
    throw new Exception($resultado['error']);
}
```

### Substituição de Parâmetros

**IMPORTANTE**: Os parâmetros são passados **SEM** o prefixo `:` no array, mas **COM** `:` no SQL.

```sql
-- Arquivo SQL: SQL/produtos/buscar.sql
SELECT * FROM produtos 
WHERE idempresa = :idempresa 
  AND categoria = :categoria
```

```php
// PHP: parâmetros SEM ':'
$resultado = Database::switchParams(
    [
        'idempresa' => 1,    // ✅ Sem ':'
        'categoria' => 5     // ✅ Sem ':'
    ],
    'produtos/buscar',
    true
);
```

### Tipos de Valores Suportados

```php
Database::switchParams([
    'string'  => 'Texto',           // Strings são escapadas
    'int'     => 123,                // Números mantidos como números
    'float'   => 99.90,              // Decimais mantidos
    'null'    => null,               // Convertido para NULL SQL
    'array'   => [1, 2, 3]           // Convertido para "1,2,3" (IN clause)
], ...);
```

### Tratamento de Arrays (IN Clause)

```sql
-- SQL
SELECT * FROM produtos WHERE id IN (:ids)
```

```php
// PHP
$resultado = Database::switchParams(
    ['ids' => [1, 2, 3, 4, 5]],
    'produtos/buscar_varios',
    true
);

// SQL executada: SELECT * FROM produtos WHERE id IN (1,2,3,4,5)
```

### Logs Automáticos

#### Log de Sucesso (quando $log = true)

**Arquivo**: `logs/exec2025-10-10-sql.txt`

```
Array
(
    [data] => 2025-10-10 14:30:45
    [sql] => SELECT * FROM produtos WHERE idempresa = 1
    [params] => Array
        (
            [idempresa] => 1
        )
    [res] => Array
        (
            [0] => Array
                (
                    [idproduto] => 1
                    [nome] => Produto A
                )
        )
)
```

#### Log de Erro (automático em caso de erro)

**Arquivo**: `logs/error2025-10-10-sql.txt`

```
Array
(
    [data] => 2025-10-10 14:30:45
    [msg] => SQLSTATE[42S02]: Base table or view not found
    [sql] => SELECT * FROM tabela_inexistente WHERE id = 1
    [params] => Array
        (
            [id] => 1
        )
)
```

### Transações Automáticas

Quando `$trasaction = true`:

```php
// Automático:
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $pdo->commit();  // ✅ Sucesso
} catch (Exception $e) {
    $pdo->rollBack();  // ❌ Erro - reverte tudo
    throw $e;
}
```

**Quando usar transação?**

| Operação | Usar Transação? | Motivo |
|----------|----------------|---------|
| SELECT | ❌ `false` | Não modifica dados, não precisa |
| INSERT | ✅ `true` | Garante atomicidade |
| UPDATE | ✅ `true` | Rollback se falhar |
| DELETE | ✅ `true` | Segurança contra erros |
| Múltiplas operações | ✅ `true` | Tudo ou nada |

---

## 🎨 Frontend (React + TypeScript)

### Estrutura de Pastas

```
web/src/
├── components/           # Componentes reutilizáveis
│   └── layout/
├── views/               # Páginas/telas
│   ├── menu-parameterization/
│   │   ├── List.tsx                    # Componente principal
│   │   └── components/
│   │       ├── ProductsAvailable.tsx   # Lista de produtos disponíveis
│   │       └── MenuProductsList.tsx    # Lista drag-and-drop
│   └── menus/
├── models/              # Interfaces TypeScript
├── hooks/               # Custom hooks (React Query)
├── services/            # API client (axios)
└── utils/               # Funções auxiliares
```

### Padrões de Componentes

#### 1. Interfaces TypeScript

```typescript
// Sempre defina interfaces para props e dados
export interface Product {
  idproduto: number;
  nome: string;
  descricao: string;
  preco: number;
  permite_acrescimo: boolean;
}

export interface MenuProduct extends Product {
  idparmsmenu: number;
  ordem: number; // ⭐ Campo de ordenação
}

export interface MenuWithProducts {
  idempresa: number;
  idmenu: number;
  descricao: string;
  produtos: MenuProduct[];
}
```

#### 2. Custom Hooks (React Query)

```typescript
// web/src/hooks/useMenus.ts

export function useMenus() {
  const { user } = useAuthContext();

  // Mutation para atualizar ordem
  const updateOrdemMutation = useMutation({
    mutationFn: async (data: {
      idempresa: number;
      idparmsmenu: number;
      nova_ordem: number;
      idmenu: number;
    }) => {
      await api.put('/updateOrdemProdutoMenu', data);
    },
    onSuccess: () => {
      queryClient.invalidateQueries(['menu-products']);
    },
  });

  return {
    updateOrdem: updateOrdemMutation.mutate,
    isUpdatingOrdem: updateOrdemMutation.isPending,
  };
}
```

#### 3. Componentes com Performance

```typescript
import { useMemo } from 'react';

// Use useMemo para cálculos pesados
const availableProducts = useMemo(() => {
  return products
    .filter((p) => !linkedProductIds.has(p.idproduto))
    .filter((p) =>
      p.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
      p.descricao?.toLowerCase().includes(searchTerm.toLowerCase())
    );
}, [products, linkedProductIds, searchTerm]);
```

### Drag-and-Drop com @dnd-kit

```typescript
import {
  DndContext,
  closestCenter,
  PointerSensor,
  useSensor,
  useSensors,
} from '@dnd-kit/core';
import {
  arrayMove,
  SortableContext,
  useSortable,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable';

const handleDragEnd = async (event: DragEndEvent) => {
  const { active, over } = event;
  
  if (!over || active.id === over.id) return;

  // 1. Atualiza UI imediatamente (otimistic update)
  const newProducts = arrayMove(products, oldIndex, newIndex);
  setLocalProducts(newProducts);

  // 2. Sincroniza com backend
  try {
    await api.put('/updateOrdemProdutoMenu', {
      idempresa: user?.idempresa,
      idparmsmenu: active.id,
      nova_ordem: newIndex + 1,
      idmenu: menuId,
    });
    showSuccessToast('Ordem atualizada!');
  } catch (err) {
    // 3. Reverte em caso de erro
    setLocalProducts(products);
    showErrorToast(err);
  }
};
```

---

## ✅ Checklist de Implementação

### Backend

- [ ] Criar arquivos SQL em `api/SQL/[entidade]/`
- [ ] Usar **SEMPRE** `Database::switchParams()` (NUNCA PDO direto)
- [ ] Criar métodos no Model
- [ ] Criar lógica de negócio no Handler
- [ ] Criar método no Controller
- [ ] Definir constantes de validação
- [ ] Adicionar rota em `routes.php`
- [ ] Testar endpoint com Postman/Insomnia

### Frontend

- [ ] Definir interfaces TypeScript
- [ ] Criar custom hook se necessário
- [ ] Implementar componente
- [ ] Usar `useMemo` para otimização
- [ ] Implementar loading states
- [ ] Implementar error states
- [ ] Adicionar feedback visual (toast)
- [ ] Testar responsividade

---

## 🚫 Anti-Patterns (O que NÃO fazer)

### ❌ NÃO usar PDO diretamente

```php
// ERRADO
$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM produtos");
```

### ❌ NÃO misturar SQL inline com lógica

```php
// ERRADO
public function buscarProdutos($id) {
    return Database::switchParams(
        'SELECT * FROM produtos WHERE id = :id', // ❌ SQL inline
        [':id' => $id],
        'select'
    );
}
```

### ❌ NÃO colocar lógica de negócio no Controller

```php
// ERRADO - Controller com lógica de negócio
public function updateOrdem() {
    $ordemAtual = $this->model->getOrdem(...);
    if ($ordemAtual == $novaOrdem) return; // ❌ Lógica no controller
    $this->model->update(...);
}
```

### ❌ NÃO usar concatenação de strings em SQL

```php
// ERRADO - SQL Injection vulnerability
$sql = "SELECT * FROM produtos WHERE nome = '" . $nome . "'"; // ❌ PERIGOSO
```

### ❌ NÃO renderizar listas enormes sem virtualização

```tsx
{/* ERRADO - Renderiza 6000 itens */}
{products.map(product => <ProductRow key={product.id} {...product} />)}

{/* CORRETO - Lista com scroll e altura fixa */}
<List sx={{ maxHeight: 400, overflow: 'auto' }}>
  {filteredProducts.map(...)}
</List>
```

---

## 📚 Documentação de Referência

### Documentos Criados

1. **IMPLEMENTACAO_ORDEM_PRODUTOS_MENU.md** - Documentação técnica completa
2. **ORDEM_PRODUTOS_MENU_README.md** - Guia rápido de uso
3. **CHECKLIST_ORDEM_PRODUTOS.md** - Checklist de testes
4. **REFATORACAO_SWITCHPARAMS.md** - Padrão switchParams
5. **SISTEMA_ORDENACAO_MENUS_COMPLETO.md** - Sistema completo de ordenação
6. **MELHORIAS_TELA_PARAMETRIZACAO_MENU.md** - Melhorias de UI/UX

### Exemplos de Implementação

#### Caso de Uso: Sistema de Ordenação

Veja implementação completa em:
- Backend: `api/src/models/Produto_menu.php`
- Handler: `api/src/handlers/Menu.php`
- Controller: `api/src/controllers/MenuController.php`
- Frontend: `web/src/views/menu-parameterization/`

---

## 🔧 Troubleshooting

### Erro: "SQL file not found"

**Causa**: Caminho incorreto para arquivo SQL

**Solução**:
```php
// ✅ CORRETO - Caminho relativo a SQL/ (sem .sql no final)
Database::switchParams(
    ['idempresa' => 1],
    'produto_menu/get_proxima_ordem',  // ✅ Sem 'SQL/' e sem '.sql'
    true
);

// ❌ ERRADO - Com SQL/ ou com .sql
Database::switchParams(
    ['idempresa' => 1],
    'SQL/produto_menu/get_proxima_ordem.sql',  // ❌
    true
);
```

### Erro: "Parameter not bound"

**Causa**: Parâmetro no SQL não corresponde ao array de parâmetros

**Solução**:
```sql
-- Arquivo SQL - COM ':' nos parâmetros
WHERE idempresa = :idempresa AND idmenu = :idmenu
```

```php
// PHP - SEM ':' nas chaves do array
Database::switchParams(
    [
        'idempresa' => 1,  // ✅ Sem ':' na chave
        'idmenu' => 10     // ✅ Sem ':' na chave
    ],
    'produto_menu/buscar',
    true
);
```

---

## 🎯 Resumo de Boas Práticas

1. **SEMPRE** use `Database::switchParams()` para queries
2. **SEMPRE** crie arquivos SQL separados em `SQL/[entidade]/`
3. **SEMPRE** siga o padrão MVC + Handler
4. **SEMPRE** defina interfaces TypeScript no frontend
5. **SEMPRE** use React Query para chamadas de API
6. **SEMPRE** implemente loading e error states
7. **SEMPRE** use `useMemo` para otimizações
8. **SEMPRE** documente funcionalidades complexas
9. **NUNCA** use PDO diretamente
10. **NUNCA** coloque SQL inline no código PHP

---

## 🚀 Versão

**Documento**: v1.0.0  
**Data**: 10/10/2025  
**Mantido por**: GitHub Copilot & Equipe ClickJoias

---

**💡 Dica**: Este documento será lido automaticamente pelo GitHub Copilot. Sempre que fizer alterações no código, certifique-se de seguir estes padrões!
