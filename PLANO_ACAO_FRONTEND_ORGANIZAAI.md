# 🎨 PLANO DE AÇÃO - FRONTEND ORGANIZAAI

## 📋 Visão Geral

Plano completo para desenvolvimento e otimização do frontend React + TypeScript da OrganizaAI.

**Status Geral**: 🟡 Em Configuração (Estrutura Base Existente)  
**Última Atualização**: 05/11/2025  
**Progresso**: 10% (Base + Dependências)  
**Meta**: 90% em 2-3 semanas com React Query + React Table

---

## 🏗️ Stack Atual (Já Instalado)

✅ **Framework & Build**
- Vite 6.2.3
- React 18.3.1
- TypeScript 5.5.3
- React Router DOM 6.22.1

✅ **State Management & Data Fetching**
- @tanstack/react-query 5.24.1 (React Query) ⭐
- Axios 1.6.7 (HTTP Client)

✅ **UI Components & Styling**
- TailwindCSS 3.4.1 (Styling)
- Material-UI 5.15.10 (Components)
- Radix UI (Primitives)
- Lucide React (Icons)
- Framer Motion (Animations)

✅ **Data Table & Grid**
- @mui/x-data-grid 8.1.0 (Data Grid)
- React Window 2.2.0 (Virtualization)

✅ **Forms & Validation**
- Yup 1.3.3 (Schema Validation)
- Zod 3.24.1 (Type-safe Validation)
- React Number Format 5.4.3 (Input Formatting)

✅ **Utilities**
- React Helmet Async (SEO)
- Sonner (Toast Notifications)
- Date-fns 4.1.0 (Date Utilities)
- Dayjs 1.11.13 (Date Library)
- jsPDF 3.0.1 (PDF Generation)
- HTML2Canvas 1.4.1 (Screenshot)

✅ **Drag & Drop**
- @dnd-kit (Modern DND)

---

## 📁 Estrutura Atual (10 arquivos)

```
web/src/
├── App.tsx                    ✅ Configurado
├── main.tsx                   ✅ Entry point
├── index.css                  ✅ Global styles
├── env.d.ts                   ✅ Type definitions
├── vite-env.d.ts              ✅ Vite types
├── contexts/
│   ├── AuthContext.tsx        ⚠️ Exists (needs auth flow)
│   ├── ThemeContext.tsx       ⚠️ Exists (needs theme config)
│   └── LabelPrintSettingsContext.tsx
├── hooks/                     ❌ Vazio (precisa implementar)
├── services/                  ❌ Vazio (precisa API client)
├── routes/                    ❌ Vazio (precisa routing)
├── views/                     ⚠️ Parcial (só estrutura)
│   ├── auth/
│   ├── public/
│   └── users/
└── assets/
    └── images/
```

---

## 🎯 MÓDULOS - PLANO DE AÇÃO

### ✅ MÓDULO 1: Configuração Base (ATUAL - Dia 1)
**Status**: 🟡 Em Progresso  
**Tempo**: 2-3 horas  
**Prioridade**: 🔴 CRÍTICA

#### Tasks:
- [x] Stack instalado e testado
- [ ] `.env.example` → `.env` configurado
- [ ] API client (axios) configurado em `services/api.ts`
- [ ] React Query configurado (QueryClient + Provider)
- [ ] Types base para API responses criados
- [ ] ESLint + Prettier configurados
- [ ] Git workflow estabelecido

#### Arquivos a Criar:
```
services/
├── api.ts                     # Axios instance + interceptors
└── api/
    └── types.ts              # Global API types

config/
├── constants.ts              # URLs, timeouts
├── http-client.ts            # HTTP config
└── query-client.ts           # React Query setup

types/
├── api.ts                     # API request/response types
├── auth.ts                    # Auth types
├── common.ts                  # Shared types
└── index.ts                   # Barrel exports
```

#### Checklist:
```typescript
// services/api.ts - Exemplo de estrutura
import axios from 'axios';
import { API_BASE_URL } from '@/config/constants';

export const api = axios.create({
  baseURL: API_BASE_URL,
  headers: { 'Content-Type': 'application/json' },
});

// Interceptor: adicionar token JWT
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('jwt_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

---

### 📌 MÓDULO 2: Authentication & Auth Context (Dia 2)
**Status**: 🔴 Não Iniciado  
**Tempo**: 3-4 horas  
**Prioridade**: 🔴 CRÍTICA

#### Tasks:
- [ ] AuthContext refatorado com hooks
- [ ] Login form component
- [ ] Protected routes com PrivateRoute
- [ ] Token storage (localStorage/sessionStorage)
- [ ] Auto-logout on token expiration
- [ ] Refresh token flow
- [ ] useAuth hook criado

#### Arquivos a Criar:
```
contexts/
└── AuthContext.tsx           # Refatorado

hooks/
└── useAuth.ts               # Auth hook

types/
└── auth.ts                  # Auth types

services/
└── auth.ts                  # Auth API calls

components/
└── PrivateRoute.tsx         # Protected routes wrapper

views/
└── auth/
    ├── LoginPage.tsx
    ├── RegisterPage.tsx
    └── ForgotPasswordPage.tsx
```

#### API Endpoints:
```typescript
// POST /login
POST http://localhost:8000/api/login
{
  email: string,
  senha: string
}

// Response
{
  jwt_token: string,
  usuario: { id, email, nome }
}
```

---

### 🎨 MÓDULO 3: Layout & Navigation (Dia 2-3)
**Status**: 🔴 Não Iniciado  
**Tempo**: 3-4 horas  
**Prioridade**: 🟡 ALTA

#### Tasks:
- [ ] Layout base component
- [ ] Sidebar navigation
- [ ] Top header com user menu
- [ ] Mobile responsive
- [ ] Dark/Light theme toggle
- [ ] Breadcrumb component
- [ ] Loading skeleton components

#### Arquivos a Criar:
```
components/
├── layout/
│   ├── Layout.tsx
│   ├── Sidebar.tsx
│   ├── Header.tsx
│   └── Footer.tsx
├── common/
│   ├── Breadcrumb.tsx
│   ├── Skeleton.tsx
│   └── EmptyState.tsx
└── ui/
    ├── Button.tsx
    ├── Input.tsx
    ├── Select.tsx
    ├── Modal.tsx
    ├── Card.tsx
    └── Badge.tsx
```

#### Estrutura de Routing:
```typescript
// routes/index.tsx
const routes = [
  {
    path: '/',
    element: <Layout />,
    children: [
      { path: 'dashboard', element: <Dashboard /> },
      { path: 'clientes', element: <ClientesPage /> },
      { path: 'sistemas', element: <SistemasPage /> },
      { path: 'assinaturas', element: <AssinaturasPage /> },
    ]
  },
  {
    path: '/auth',
    children: [
      { path: 'login', element: <LoginPage /> },
      { path: 'register', element: <RegisterPage /> },
    ]
  }
];
```

---

### 📊 MÓDULO 4: Dashboard (Dia 3)
**Status**: 🔴 Não Iniciado  
**Tempo**: 2-3 horas  
**Prioridade**: 🟡 ALTA

#### Tasks:
- [ ] Dashboard layout
- [ ] KPI cards (revenue, clientes, assinaturas)
- [ ] Charts (Recharts)
- [ ] Recent activity table
- [ ] Upcoming renewals widget
- [ ] Quick actions buttons

#### Arquivos a Criar:
```
views/
└── dashboard/
    ├── DashboardPage.tsx
    ├── components/
    │   ├── KPICard.tsx
    │   ├── RevenueChart.tsx
    │   ├── SubscriptionsChart.tsx
    │   ├── RecentActivity.tsx
    │   ├── UpcomingRenewals.tsx
    │   └── QuickActions.tsx
    └── hooks/
        └── useDashboardStats.ts (React Query)

hooks/
└── useDashboardStats.ts      # Query hook
```

#### API Endpoint:
```typescript
// GET /relatorios/dashboard
GET http://localhost:8000/api/relatorios/dashboard

Response:
{
  total_clientes: number,
  total_assinaturas: number,
  receita_mensal: number,
  assinaturas_vencendo: number,
  sistemas_mais_vendidos: [...],
  receita_por_mes: [...]
}
```

---

### 👥 MÓDULO 5: Clientes (CRUD) (Dia 4-5)
**Status**: 🔴 Não Iniciado  
**Tempo**: 4-5 horas  
**Prioridade**: 🔴 CRÍTICA

#### Tasks:
- [ ] Clientes list page com React Table
- [ ] Clientes create form
- [ ] Clientes edit modal
- [ ] Clientes delete confirmation
- [ ] Search & filter
- [ ] Pagination
- [ ] Validações com Zod/Yup

#### Arquivos a Criar:
```
views/
└── clientes/
    ├── ClientesPage.tsx
    ├── components/
    │   ├── ClientesTable.tsx
    │   ├── ClientesForm.tsx
    │   ├── ClientesModal.tsx
    │   └── ClientesFilters.tsx
    └── hooks/
        ├── useClientes.ts           # Query list
        ├── useCreateCliente.ts      # Mutation create
        ├── useUpdateCliente.ts      # Mutation update
        └── useDeleteCliente.ts      # Mutation delete

types/
└── clientes.ts                # Cliente types

services/
└── clientes.ts               # Clientes API calls
```

#### API Endpoints (Usados):
```typescript
GET /clientes                           # List
POST /clientes                          # Create
PUT /clientes                           # Update
DELETE /clientes                        # Delete
GET /clientes/{id}                      # Get by ID
GET /clientes/cpf/{cpf_cnpj}           # Get by CPF
GET /clientes/{id}/enderecos            # Get addresses
POST /clientes/enderecos                # Create address
GET /clientes/{id}/contatos             # Get contacts
POST /clientes/contatos                 # Create contact
```

#### Hooks Pattern:
```typescript
// hooks/useClientes.ts
export function useClientes(page = 1, limit = 20) {
  return useQuery({
    queryKey: ['clientes', page],
    queryFn: () => api.get('/clientes', { 
      params: { page, limit } 
    }),
  });
}

export function useCreateCliente() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (data) => api.post('/clientes', data),
    onSuccess: () => {
      queryClient.invalidateQueries(['clientes']);
    },
  });
}
```

---

### 💻 MÓDULO 6: Sistemas (Catálogo) (Dia 5-6)
**Status**: 🔴 Não Iniciado  
**Tempo**: 3-4 horas  
**Prioridade**: 🟡 ALTA

#### Tasks:
- [ ] Sistemas list (com cache 24h)
- [ ] Sistema detail view
- [ ] Planos list por sistema
- [ ] Add-ons list
- [ ] Price display com impostos
- [ ] Search & categorization

#### Arquivos a Criar:
```
views/
└── sistemas/
    ├── SistemasPage.tsx
    ├── SistemaDetailPage.tsx
    ├── components/
    │   ├── SistemasGrid.tsx
    │   ├── SistemaCard.tsx
    │   ├── PlanosTable.tsx
    │   ├── AddonsTable.tsx
    │   └── PriceDisplay.tsx
    └── hooks/
        ├── useSistemas.ts
        ├── useSistemaDetail.ts
        ├── usePlanos.ts
        └── useAddons.ts

services/
└── sistemas.ts
```

#### API Endpoints:
```typescript
GET /sistemas                    # List (cached)
GET /sistemas/{id}               # Detail
GET /sistemas/{id}/planos        # Plans
GET /sistemas/{id}/addons        # Add-ons
```

---

### 📋 MÓDULO 7: Assinaturas (Core) (Dia 6-8)
**Status**: 🔴 Não Iniciado  
**Tempo**: 5-6 horas  
**Prioridade**: 🔴 CRÍTICA

#### Tasks:
- [ ] Assinaturas list com filtros
- [ ] Criar assinatura (form multi-step)
- [ ] Editar assinatura
- [ ] Renovar assinatura
- [ ] Cancelar assinatura
- [ ] Status badge colors
- [ ] Upcoming renewals highlight

#### Arquivos a Criar:
```
views/
└── assinaturas/
    ├── AssinaturasPage.tsx
    ├── CriarAssinaturaPage.tsx
    ├── AssinaturaDetailPage.tsx
    ├── components/
    │   ├── AssinaturasTable.tsx
    │   ├── AssinaturaForm.tsx
    │   ├── ClienteSelect.tsx
    │   ├── SistemaSelect.tsx
    │   ├── PlanoSelect.tsx
    │   ├── AddonSelect.tsx
    │   ├── AddonsMultiSelect.tsx
    │   ├── PriceCalculator.tsx
    │   └── RenewalActions.tsx
    └── hooks/
        ├── useAssinaturas.ts
        ├── useCreateAssinatura.ts
        ├── useUpdateAssinatura.ts
        ├── useRenovarAssinatura.ts
        ├── useCancelarAssinatura.ts
        └── useAssinaturasVencendo.ts

types/
└── assinaturas.ts              # Subscription types

services/
└── assinaturas.ts
```

#### API Endpoints:
```typescript
GET /assinaturas
POST /assinaturas
PUT /assinaturas
DELETE /assinaturas
GET /assinaturas/{id}
GET /assinaturas/cliente/{id}
GET /assinaturas/vencendo/{dias}
POST /assinaturas-avancado/renovar
POST /assinaturas-avancado/cancelar
```

---

### 🎁 MÓDULO 8: Add-ons Management (Dia 8)
**Status**: 🔴 Não Iniciado  
**Tempo**: 2-3 horas  
**Prioridade**: 🟡 MÉDIA

#### Tasks:
- [ ] Add-ons gerenciamento por assinatura
- [ ] Add-ons modal
- [ ] Pricing calculation with addons
- [ ] Visual indicator de add-ons ativos

#### Arquivos a Criar:
```
views/
└── assinaturas/
    └── components/
        ├── AssinaturasAddonsModal.tsx
        ├── AssinaturasAddonsList.tsx
        └── AddonsSelector.tsx

hooks/
├── useAssinaturasAddons.ts
├── useAddAssinaturasAddon.ts
└── useRemoveAssinaturasAddon.ts
```

---

### 📊 MÓDULO 9: Relatórios (Dia 9)
**Status**: 🔴 Não Iniciado  
**Tempo**: 3-4 horas  
**Prioridade**: 🟡 MÉDIA

#### Tasks:
- [ ] Receita por período
- [ ] Clientes ativos ranking
- [ ] Sistemas vendidos ranking
- [ ] Export para PDF
- [ ] Date range filters

#### Arquivos a Criar:
```
views/
└── relatorios/
    ├── RelatoriosPage.tsx
    ├── components/
    │   ├── ReceitaReport.tsx
    │   ├── ClientesReport.tsx
    │   ├── SistemasReport.tsx
    │   ├── DateRangeFilter.tsx
    │   └── ExportPDF.tsx
    └── hooks/
        ├── useRelatorioReceita.ts
        ├── useRelatorioCientes.ts
        └── useRelatorioSistemas.ts

services/
└── relatorios.ts
```

---

### ⚙️ MÓDULO 10: Configurações & Admin (Dia 10)
**Status**: 🔴 Não Iniciado  
**Tempo**: 2-3 horas  
**Prioridade**: 🟡 BAIXA

#### Tasks:
- [ ] User profile page
- [ ] User preferences
- [ ] System settings
- [ ] Account management
- [ ] Logout function

#### Arquivos a Criar:
```
views/
└── admin/
    ├── SettingsPage.tsx
    ├── ProfilePage.tsx
    └── components/
        ├── ProfileForm.tsx
        └── SettingsForm.tsx
```

---

## 📂 Estrutura Final Recomendada

```
web/src/
├── App.tsx
├── main.tsx
├── index.css
│
├── config/                              # Configurações
│   ├── constants.ts
│   ├── http-client.ts
│   └── query-client.ts
│
├── types/                               # Types compartilhados
│   ├── api.ts
│   ├── auth.ts
│   ├── clientes.ts
│   ├── sistemas.ts
│   ├── assinaturas.ts
│   └── index.ts
│
├── services/                            # API clients
│   ├── api.ts
│   ├── auth.ts
│   ├── clientes.ts
│   ├── sistemas.ts
│   ├── assinaturas.ts
│   └── relatorios.ts
│
├── contexts/                            # Global state
│   ├── AuthContext.tsx
│   ├── ThemeContext.tsx
│   └── LabelPrintSettingsContext.tsx
│
├── hooks/                               # Custom hooks
│   ├── useAuth.ts
│   ├── useClientes.ts
│   ├── useCreateCliente.ts
│   ├── useSistemas.ts
│   ├── useAssinaturas.ts
│   ├── useRenovarAssinatura.ts
│   ├── useDashboardStats.ts
│   └── index.ts (barrel export)
│
├── components/                          # Reusable components
│   ├── layout/
│   │   ├── Layout.tsx
│   │   ├── Sidebar.tsx
│   │   ├── Header.tsx
│   │   └── Footer.tsx
│   ├── common/
│   │   ├── PrivateRoute.tsx
│   │   ├── Breadcrumb.tsx
│   │   ├── EmptyState.tsx
│   │   ├── LoadingSkeleton.tsx
│   │   └── Modals.tsx
│   ├── ui/
│   │   ├── Button.tsx
│   │   ├── Input.tsx
│   │   ├── Select.tsx
│   │   ├── Modal.tsx
│   │   ├── Card.tsx
│   │   └── Badge.tsx
│   └── Toast/
│       └── ToastProvider.tsx
│
├── routes/
│   └── index.tsx
│
├── views/                               # Pages
│   ├── auth/
│   │   ├── LoginPage.tsx
│   │   ├── RegisterPage.tsx
│   │   └── ForgotPasswordPage.tsx
│   ├── dashboard/
│   │   └── DashboardPage.tsx
│   ├── clientes/
│   │   ├── ClientesPage.tsx
│   │   ├── ClienteDetailPage.tsx
│   │   └── components/
│   ├── sistemas/
│   │   ├── SistemasPage.tsx
│   │   ├── SistemaDetailPage.tsx
│   │   └── components/
│   ├── assinaturas/
│   │   ├── AssinaturasPage.tsx
│   │   ├── CriarAssinaturaPage.tsx
│   │   ├── AssinaturaDetailPage.tsx
│   │   └── components/
│   ├── relatorios/
│   │   └── RelatoriosPage.tsx
│   ├── admin/
│   │   ├── SettingsPage.tsx
│   │   └── ProfilePage.tsx
│   └── public/
│       └── NotFoundPage.tsx
│
├── theme/
│   ├── ThemeProvider.tsx
│   ├── colors.ts
│   └── typography.ts
│
├── assets/
│   └── images/
│       ├── logo.svg
│       └── icons/
│
└── utils/
    ├── formatters.ts
    ├── validators.ts
    ├── api-error.ts
    └── storage.ts
```

---

## 🚀 Timeline Recomendado

| Módulo | Dias | Status |
|--------|------|--------|
| **1. Configuração Base** | 1 | 🟡 In Progress |
| **2. Authentication** | 1 | ⏳ Upcoming |
| **3. Layout & Navigation** | 1-2 | ⏳ Upcoming |
| **4. Dashboard** | 1 | ⏳ Upcoming |
| **5. Clientes (CRUD)** | 1-2 | ⏳ Upcoming |
| **6. Sistemas** | 1 | ⏳ Upcoming |
| **7. Assinaturas (Core)** | 2 | ⏳ Upcoming |
| **8. Add-ons** | 1 | ⏳ Upcoming |
| **9. Relatórios** | 1 | ⏳ Upcoming |
| **10. Configurações** | 1 | ⏳ Upcoming |
| **TOTAL** | **10-12 dias** | **90%** |

---

## 🎯 Padrões de Codificação

### React Query Hooks Pattern

```typescript
// Queries (GET)
export function useClientes(page = 1) {
  return useQuery({
    queryKey: ['clientes', page],
    queryFn: () => api.get('/clientes', { params: { page } }),
  });
}

// Mutations (POST, PUT, DELETE)
export function useCreateCliente() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (data) => api.post('/clientes', data),
    onSuccess: () => {
      queryClient.invalidateQueries(['clientes']);
      toast.success('Cliente criado!');
    },
    onError: (error) => {
      toast.error(error.message);
    },
  });
}
```

### Component Pattern

```typescript
interface ClientesTableProps {
  data: Cliente[];
  isLoading: boolean;
  onEdit: (cliente: Cliente) => void;
  onDelete: (id: number) => void;
}

export function ClientesTable({
  data,
  isLoading,
  onEdit,
  onDelete,
}: ClientesTableProps) {
  // Component implementation
}
```

### Form Validation Pattern

```typescript
import { z } from 'zod';

const clienteSchema = z.object({
  nome: z.string().min(3, 'Mínimo 3 caracteres'),
  cpf_cnpj: z.string().regex(/^\d{11,14}$/),
  email: z.string().email(),
});

type ClienteForm = z.infer<typeof clienteSchema>;
```

---

## ✅ Checklist de Qualidade

- [ ] TypeScript strict mode habilitado
- [ ] ESLint + Prettier configurados
- [ ] React Query DevTools instalado
- [ ] Error boundaries implementados
- [ ] Skeleton loading screens
- [ ] Optimistic updates em mutations
- [ ] Error handling global
- [ ] Toast notifications
- [ ] Responsive design mobile-first
- [ ] Accessibility (a11y) checklist
- [ ] Performance optimization (lazy loading, memoization)
- [ ] Tests com Vitest (opcional)

---

## 🔗 Próximas Ações (Priority Order)

1. ✅ **TODAY**: Setup `.env`, API client, React Query config
2. 🟡 **TOMORROW**: Auth context, login form, protected routes
3. 🟡 **DAY 3**: Layout base, sidebar, header
4. 🟡 **DAY 4**: Dashboard com widgets
5. 🟡 **DAY 5-6**: Clientes CRUD + Sistemas list
6. 🟡 **DAY 7-8**: Assinaturas core functionality
7. 🟡 **DAY 9**: Relatórios
8. 🟡 **DAY 10**: Final polish + deploy

---

**Repositório**: https://github.com/joaosn/OrganizaAI-API  
**Documentação API**: `DOCS/README_COMPLETO.md`  
**Endpoints**: `DOCS/openapi.yaml`

**Vamos começar! 🚀**
