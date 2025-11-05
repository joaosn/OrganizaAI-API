# 🗺️ MAPEAMENTO: 67 ENDPOINTS API → REACT FRONTEND

Mapeamento completo de todos os 67 endpoints do backend OrganizaAI para componentes, páginas e hooks do frontend React.

---

## 👥 CLIENTES (15 endpoints)

### Lista de Clientes
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/clientes` | GET | `useClientes()` | ClientesTable | ClientesPage |
| `/clientes` | POST | `useCreateCliente()` | ClientesForm | ClientesPage (Modal) |
| `/clientes/{id}` | GET | `useClienteDetail()` | ClienteDetail | ClienteDetailPage |
| `/clientes/{id}` | PUT | `useUpdateCliente()` | ClientesForm | ClienteDetailPage (Modal) |
| `/clientes/{id}` | DELETE | `useDeleteCliente()` | ClientesTable (action) | ClientesPage (confirm) |

### Busca de Clientes
| Endpoint | Método | Frontend Hook | Uso |
|----------|--------|---------------|-----|
| `/clientes/cpf/{cpf_cnpj}` | GET | `useBuscarClientePorCPF()` | ClienteSelect, Search |
| `/clientes?nome=X` | GET | `useClientes()` | Search/Filter |
| `/clientes?ativo=1` | GET | `useClientes()` | Filter |

### Endereços
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/clientes/{id}/enderecos` | GET | `useClienteEnderecos()` | ClienteEnderecosList | ClienteDetailPage |
| `/clientes/enderecos` | POST | `useCreateEndereço()` | EnderecoForm | Modal |
| `/clientes/enderecos/{id}` | PUT | `useUpdateEndereço()` | EnderecoForm | Modal |
| `/clientes/enderecos/{id}` | DELETE | `useDeleteEndereço()` | EnderecosList (action) | Modal |

### Contatos
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/clientes/{id}/contatos` | GET | `useClienteContatos()` | ClienteContatosList | ClienteDetailPage |
| `/clientes/contatos` | POST | `useCreateContato()` | ContatoForm | Modal |
| `/clientes/contatos/{id}` | DELETE | `useDeleteContato()` | ContatosList (action) | Modal |

---

## 💻 SISTEMAS (18 endpoints)

### Sistemas
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/sistemas` | GET | `useSistemas()` | SistemasGrid/Table | SistemasPage |
| `/sistemas` | POST | `useCreateSistema()` | SistemaForm | AdminPage (Modal) |
| `/sistemas/{id}` | GET | `useSistemaDetail()` | SistemaDetail | SistemaDetailPage |
| `/sistemas/{id}` | PUT | `useUpdateSistema()` | SistemaForm | AdminPage (Modal) |
| `/sistemas/{id}` | DELETE | `useDeleteSistema()` | SistemasTable (action) | AdminPage (confirm) |

**Cache**: 24 horas (dados estáticos)

### Planos
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/sistemas/{id}/planos` | GET | `usePlanosPorSistema()` | PlanosTable | SistemaDetailPage |
| `/sistemas/planos` | POST | `useCreatePlano()` | PlanoForm | AdminPage (Modal) |
| `/sistemas/planos/{id}` | PUT | `useUpdatePlano()` | PlanoForm | AdminPage (Modal) |
| `/sistemas/planos/{id}` | DELETE | `useDeletePlano()` | PlanosTable (action) | AdminPage (confirm) |
| `/sistemas/{id}/planos/preco/{mes}` | GET | `usePlanosPrecoMes()` | PriceDisplay | Dashboard |

### Add-ons
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/sistemas/{id}/addons` | GET | `useAddonsPorSistema()` | AddonsTable | SistemaDetailPage |
| `/sistemas/addons` | POST | `useCreateAddon()` | AddonForm | AdminPage (Modal) |
| `/sistemas/addons/{id}` | PUT | `useUpdateAddon()` | AddonForm | AdminPage (Modal) |
| `/sistemas/addons/{id}` | DELETE | `useDeleteAddon()` | AddonsTable (action) | AdminPage (confirm) |

---

## 📋 ASSINATURAS (15 endpoints)

### Assinaturas
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/assinaturas` | GET | `useAssinaturas()` | AssinaturasTable | AssinaturasPage |
| `/assinaturas` | POST | `useCreateAssinatura()` | AssinaturaForm | CriarAssinaturaPage |
| `/assinaturas/{id}` | GET | `useAssinaturaDetail()` | AssinaturaDetail | AssinaturaDetailPage |
| `/assinaturas/{id}` | PUT | `useUpdateAssinatura()` | AssinaturaForm | AssinaturaDetailPage (Modal) |
| `/assinaturas/{id}` | DELETE | `useDeleteAssinatura()` | AssinaturasTable (action) | AssinaturasPage (confirm) |

### Filtros & Buscas
| Endpoint | Método | Frontend Hook | Uso |
|----------|--------|---------------|-----|
| `/assinaturas?cliente_id=X` | GET | `useAssinaturasCliente()` | ClienteDetailPage |
| `/assinaturas?sistema_id=X` | GET | `useAssinaturasSistema()` | SistemaDetailPage |
| `/assinaturas?status=ativo` | GET | `useAssinaturas()` | Filter |
| `/assinaturas/vencendo/{dias}` | GET | `useAssinaturasVencendo()` | Dashboard, Alert |

### Add-ons da Assinatura
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/assinaturas/{id}/addons` | GET | `useAssinaturasAddons()` | AssinaturasAddonsList | AssinaturaDetailPage |
| `/assinaturas-addons` | POST | `useAddAssinaturasAddon()` | AddonSelector | Modal |
| `/assinaturas-addons/{id}` | DELETE | `useRemoveAssinaturasAddon()` | AssinaturasAddonsList (action) | Modal |

---

## 📊 HISTÓRICO DE PREÇOS (7 endpoints)

### Auditoria de Preços
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/precos-historico` | GET | `usePrecoHistorico()` | HistoricoTable | AuditoriaPage |
| `/precos-historico?sistema_id=X` | GET | `usePrecoHistoricoSistema()` | HistoricoTable | SistemaDetailPage |
| `/precos-historico?plano_id=X` | GET | `usePrecoHistoricoPlano()` | HistoricoTable | PlanoDetailPage |
| `/precos-historico?addon_id=X` | GET | `usePrecoHistoricoAddon()` | HistoricoTable | AddonDetailPage |

### Variações de Preço
| Endpoint | Método | Frontend Hook | Uso |
|----------|--------|---------------|-----|
| `/precos-historico/variacao/{mes}` | GET | `useVariacaoPrecoMes()` | Dashboard Chart |
| `/precos-historico/maior-variacao` | GET | `useMaiorVariacaoPreco()` | Dashboard Alert |
| `/precos-historico/media-preco` | GET | `useMediaPreco()` | Dashboard Stats |

---

## 📈 RELATÓRIOS (6 endpoints)

### Dashboard
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/relatorios/dashboard` | GET | `useDashboardStats()` | KPICard, Chart | DashboardPage |
| `/relatorios/receita-mensal` | GET | `useReceitaMensal()` | ReceitaChart | DashboardPage |
| `/relatorios/clientes-ativos` | GET | `useClientesAtivos()` | Chart | DashboardPage |

### Extratos & Reportes
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/relatorios/assinaturas-vencendo` | GET | `useAssinaturasVencendo()` | Table | DashboardPage |
| `/relatorios/receita-por-periodo` | GET | `useReceitaPorPeriodo()` | DateRangeReport | RelatoriosPage |
| `/relatorios/exportar-pdf` | GET | `useExportarRelatorio()` | ExportButton | RelatoriosPage |

---

## 🔧 FUNCIONALIDADES AVANÇADAS (6 endpoints)

### Operações Especializadas
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/assinaturas-avancado/simular-preco` | POST | `useSimularPreco()` | PriceCalculator | CriarAssinaturaPage |
| `/assinaturas-avancado/renovar` | POST | `useRenovarAssinatura()` | RenewalButton | AssinaturaDetailPage |
| `/assinaturas-avancado/cancelar` | POST | `useCancelarAssinatura()` | CancelButton | AssinaturaDetailPage |

### Sincronização & Processamento
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/assinaturas-avancado/processar-vencimentos` | POST | `useProcessarVencimentos()` | AdminButton | AdminPage |
| `/assinaturas-avancado/aplicar-promocao` | POST | `useAplicarPromocao()` | PromocaoModal | AdminPage |
| `/assinaturas-avancado/gerar-fatura` | POST | `useGerarFatura()` | InvoiceButton | AssinaturaDetailPage |

---

## 🔐 AUTENTICAÇÃO (4 endpoints - não listados acima)

### Login & Segurança
| Endpoint | Método | Frontend Hook | Componente | Página |
|----------|--------|---------------|-----------|--------|
| `/login` | POST | `useLogin()` | LoginForm | LoginPage |
| `/logout` | POST | `useLogout()` | LogoutButton | Header |
| `/me` | GET | `useMe()` | UserProfile | Header |
| `/refresh-token` | POST | (auto) | (auto) | (interceptor) |

---

## 📦 ESTRUTURA DE ARQUIVOS NECESSÁRIOS

```
web/src/
├── services/
│   ├── api.ts                    ✅ Já existe
│   ├── auth.ts                   🔴 Criar
│   ├── clientes.ts               🔴 Criar
│   ├── sistemas.ts               🔴 Criar
│   ├── assinaturas.ts            🔴 Criar
│   ├── precos-historico.ts       🔴 Criar
│   └── relatorios.ts             🔴 Criar
│
├── hooks/
│   ├── auth/
│   │   ├── useAuth.ts            🔴 Criar
│   │   ├── useLogin.ts           🔴 Criar
│   │   └── useLogout.ts          🔴 Criar
│   ├── clientes/
│   │   ├── useClientes.ts
│   │   ├── useCreateCliente.ts
│   │   ├── useUpdateCliente.ts
│   │   └── ... (15 hooks total)
│   ├── sistemas/
│   │   ├── useSistemas.ts
│   │   ├── usePlanos.ts
│   │   ├── useAddons.ts
│   │   └── ... (18 hooks total)
│   ├── assinaturas/
│   │   ├── useAssinaturas.ts
│   │   ├── useCreateAssinatura.ts
│   │   ├── useRenovarAssinatura.ts
│   │   └── ... (15 hooks total)
│   ├── relatorios/
│   │   ├── useDashboardStats.ts
│   │   ├── useReceitaMensal.ts
│   │   └── ... (6 hooks total)
│   └── index.ts                  (barrel export)
│
├── types/
│   ├── api.ts                    ✅ Já existe
│   ├── auth.ts                   🔴 Criar
│   ├── clientes.ts               🔴 Criar
│   ├── sistemas.ts               🔴 Criar
│   ├── assinaturas.ts            🔴 Criar
│   ├── precos-historico.ts       🔴 Criar
│   └── relatorios.ts             🔴 Criar
│
├── views/
│   ├── auth/                     ✅ Existe
│   │   ├── LoginPage.tsx         🟡 Implementar
│   │   ├── RegisterPage.tsx      🟡 Implementar
│   │   └── ForgotPasswordPage.tsx🟡 Implementar
│   ├── dashboard/
│   │   ├── DashboardPage.tsx     🔴 Criar
│   │   └── components/           🔴 Criar
│   ├── clientes/
│   │   ├── ClientesPage.tsx      🔴 Criar
│   │   ├── ClienteDetailPage.tsx 🔴 Criar
│   │   └── components/           🔴 Criar
│   ├── sistemas/
│   │   ├── SistemasPage.tsx      🔴 Criar
│   │   ├── SistemaDetailPage.tsx 🔴 Criar
│   │   └── components/           🔴 Criar
│   ├── assinaturas/
│   │   ├── AssinaturasPage.tsx   🔴 Criar
│   │   ├── AssinaturaDetailPage.tsx
│   │   ├── CriarAssinaturaPage.tsx
│   │   └── components/           🔴 Criar
│   ├── relatorios/
│   │   ├── RelatoriosPage.tsx    🔴 Criar
│   │   ├── AuditoriaPage.tsx     🔴 Criar
│   │   └── components/           🔴 Criar
│   └── admin/
│       ├── SettingsPage.tsx      🔴 Criar
│       └── components/           🔴 Criar
│
└── components/
    ├── layout/                   ✅ Existe
    ├── ui/                       ✅ Existe
    ├── form/
    │   ├── ClienteForm.tsx       🔴 Criar
    │   ├── SistemaForm.tsx       🔴 Criar
    │   ├── AssinaturaForm.tsx    🔴 Criar
    │   └── ValidationSchema.ts   🔴 Criar
    ├── table/
    │   ├── ClientesTable.tsx     🔴 Criar
    │   ├── AssinaturasTable.tsx  🔴 Criar
    │   └── HistoricoTable.tsx    🔴 Criar
    └── charts/
        ├── ReceitaChart.tsx      🔴 Criar
        └── SubscriptionsChart.tsx🔴 Criar
```

---

## 🎯 RESUMO DE HOOKS NECESSÁRIOS (67 Total)

### Por Categoria

**Authentication** (4 hooks)
- useAuth, useLogin, useLogout, useMe

**Clientes** (11 hooks)
- useClientes, useCreateCliente, useUpdateCliente, useDeleteCliente
- useClienteDetail, useBuscarClientePorCPF
- useClienteEnderecos, useCreateEndereço, useUpdateEndereço, useDeleteEndereço
- useClienteContatos, useCreateContato, useDeleteContato (+ 5 = 11)

**Sistemas** (14 hooks)
- useSistemas, useCreateSistema, useUpdateSistema, useDeleteSistema
- useSistemaDetail
- usePlanosPorSistema, useCreatePlano, useUpdatePlano, useDeletePlano, usePlanosPrecoMes
- useAddonsPorSistema, useCreateAddon, useUpdateAddon, useDeleteAddon (= 14)

**Assinaturas** (13 hooks)
- useAssinaturas, useCreateAssinatura, useUpdateAssinatura, useDeleteAssinatura
- useAssinaturaDetail
- useAssinaturasCliente, useAssinaturasSistema, useAssinaturasVencendo
- useAssinaturasAddons, useAddAssinaturasAddon, useRemoveAssinaturasAddon
- useRenovarAssinatura, useCancelarAssinatura (= 13)

**Preços** (7 hooks)
- usePrecoHistorico, usePrecoHistoricoSistema, usePrecoHistoricoPlano, usePrecoHistoricoAddon
- useVariacaoPrecoMes, useMaiorVariacaoPreco, useMediaPreco (= 7)

**Relatórios** (6 hooks)
- useDashboardStats, useReceitaMensal, useClientesAtivos
- useAssinaturasVencendo, useReceitaPorPeriodo, useExportarRelatorio (= 6)

**Avançados** (6 hooks)
- useSimularPreco, useRenovarAssinatura, useCancelarAssinatura (duplicados)
- useProcessarVencimentos, useAplicarPromocao, useGerarFatura (= 3 novos)

**Total**: 4 + 11 + 14 + 13 + 7 + 6 + 3 = **58 hooks únicos**

---

## 🔄 Padrão de Hook

Todos os hooks devem seguir este padrão:

```typescript
// hooks/[categoria]/use[Operacao].ts

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { api } from '@/services/api';
import type { Cliente, PaginatedResponse } from '@/types';

/**
 * Hook para buscar lista de clientes
 */
export function useClientes(page = 1, limit = 20) {
  return useQuery({
    queryKey: ['clientes', page, limit],
    queryFn: async () => {
      const response = await api.get<PaginatedResponse<Cliente>>(
        '/clientes',
        { params: { page, limit } }
      );
      return response.data.result;
    },
    staleTime: 5 * 60 * 1000, // 5 minutos
  });
}

/**
 * Hook para criar novo cliente
 */
export function useCreateCliente() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: Partial<Cliente>) => {
      const response = await api.post<Cliente>('/clientes', data);
      return response.data.result;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['clientes'],
      });
      toast.success('Cliente criado com sucesso!');
    },
    onError: (error) => {
      toast.error('Erro ao criar cliente');
      console.error(error);
    },
  });
}
```

---

## 📋 Próximas Ações

1. ✅ **MÓDULO 1**: Setup base (API client, React Query, types)
2. 🟡 **MÓDULO 2**: Auth context + login
3. 🟡 **MÓDULO 3**: Layout + navigation
4. 🟡 **MÓDULO 4**: Dashboard
5. 🟡 **MÓDULO 5**: Clientes CRUD (11 hooks)
6. 🟡 **MÓDULO 6**: Sistemas (14 hooks)
7. 🟡 **MÓDULO 7**: Assinaturas (13 hooks)
8. 🟡 **MÓDULO 8**: Add-ons management (3 hooks)
9. 🟡 **MÓDULO 9**: Relatórios (6 hooks)
10. 🟡 **MÓDULO 10**: Admin & settings

---

**Total de Endpoints**: 67  
**Total de Hooks Necessários**: ~58  
**Total de Componentes**: ~100+  
**Total de Páginas**: 10+

**Tempo Estimado**: 10-12 dias  
**Status**: 🟡 Em Planejamento
