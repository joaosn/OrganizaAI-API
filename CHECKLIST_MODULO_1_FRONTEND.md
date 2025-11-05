# ✅ CHECKLIST - MÓDULO 1: CONFIGURAÇÃO BASE

**Status**: 🟡 Em Progresso  
**Tempo**: 2-3 horas  
**Prioridade**: 🔴 CRÍTICA  
**Data**: 05/11/2025

---

## 📋 TAREFAS DO MÓDULO 1

### 1. Variáveis de Ambiente

**Arquivo**: `web/.env`

```bash
VITE_API_URL=http://localhost:8000/api
VITE_API_TIMEOUT=30000
VITE_APP_NAME=OrganizaAI
```

**Actions**:
- [ ] Copiar `example.env` para `.env`
- [ ] Configurar `VITE_API_URL` (local/dev/prod)
- [ ] Testar acesso à API

---

### 2. Axios HTTP Client

**Arquivo**: `web/src/services/api.ts`

**Criar arquivo com:**

```typescript
import axios, { AxiosError, AxiosRequestConfig } from 'axios';
import { toast } from 'sonner';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
const API_TIMEOUT = parseInt(import.meta.env.VITE_API_TIMEOUT || '30000');

/**
 * Axios instance com configurações padrão
 */
export const api = axios.create({
  baseURL: API_URL,
  timeout: API_TIMEOUT,
  headers: {
    'Content-Type': 'application/json',
  },
});

/**
 * Request Interceptor: Adiciona JWT token
 */
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('jwt_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

/**
 * Response Interceptor: Trata erros globalmente
 */
api.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    // 401: Token inválido/expirado
    if (error.response?.status === 401) {
      localStorage.removeItem('jwt_token');
      localStorage.removeItem('user');
      window.location.href = '/auth/login';
      toast.error('Sessão expirada. Faça login novamente.');
    }

    // 403: Acesso negado
    if (error.response?.status === 403) {
      toast.error('Você não tem permissão para acessar este recurso.');
    }

    // 404: Não encontrado
    if (error.response?.status === 404) {
      toast.error('Recurso não encontrado.');
    }

    // 500: Erro do servidor
    if (error.response?.status === 500) {
      toast.error('Erro no servidor. Tente novamente mais tarde.');
    }

    return Promise.reject(error);
  }
);

export default api;
```

**Actions**:
- [ ] Criar arquivo `web/src/services/api.ts`
- [ ] Copiar código acima
- [ ] Testar: `npm run dev` sem erros

---

### 3. Types Base para API

**Arquivo**: `web/src/types/api.ts`

```typescript
/**
 * Resposta padrão da API
 */
export interface ApiResponse<T = any> {
  result: T;
  error: boolean | string;
}

/**
 * Resposta paginada
 */
export interface PaginatedResponse<T> {
  data: T[];
  page: number;
  limit: number;
  total: number;
  pages: number;
}

/**
 * Erro da API
 */
export interface ApiError {
  status: number;
  message: string;
  details?: Record<string, any>;
}

/**
 * Usuário logado
 */
export interface User {
  id: number;
  email: string;
  nome: string;
  empresa_id: number;
  role?: string;
}

/**
 * Token JWT
 */
export interface AuthResponse {
  jwt_token: string;
  usuario: User;
}
```

**Actions**:
- [ ] Criar arquivo `web/src/types/api.ts`
- [ ] Copiar tipos acima
- [ ] Adicionar novos tipos conforme necessário

---

### 4. Configuração React Query

**Arquivo**: `web/src/config/query-client.ts`

```typescript
import { QueryClient } from '@tanstack/react-query';

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 1000 * 60 * 5, // 5 minutos
      gcTime: 1000 * 60 * 10,    // 10 minutos (antes era cacheTime)
      retry: 1,
      refetchOnWindowFocus: false,
    },
    mutations: {
      retry: 1,
    },
  },
});
```

**Actions**:
- [ ] Criar arquivo `web/src/config/query-client.ts`
- [ ] Copiar código
- [ ] Atualizar `App.tsx` para usar QueryClient

---

### 5. Atualizar App.tsx

**Arquivo**: `web/src/App.tsx`

```typescript
import { QueryClientProvider } from '@tanstack/react-query';
import { BrowserRouter } from 'react-router-dom';
import { Toaster } from 'sonner';

import { queryClient } from '@/config/query-client';
import { AuthProvider } from '@/contexts/AuthContext';
import { ThemeProvider } from '@/contexts/ThemeContext';
import Routes from '@/routes';

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <ThemeProvider>
          <AuthProvider>
            <Routes />
            <Toaster position="top-right" />
          </AuthProvider>
        </ThemeProvider>
      </BrowserRouter>
    </QueryClientProvider>
  );
}

export default App;
```

**Actions**:
- [ ] Atualizar `web/src/App.tsx`
- [ ] Verificar imports corretos
- [ ] Testar: `npm run dev`

---

### 6. Arquivo de Constantes

**Arquivo**: `web/src/config/constants.ts`

```typescript
/**
 * URLs da aplicação
 */
export const APP_URLS = {
  API: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  HOME: '/',
  LOGIN: '/auth/login',
  LOGOUT: '/auth/logout',
  REGISTER: '/auth/register',
};

/**
 * Timeouts (ms)
 */
export const TIMEOUTS = {
  API: parseInt(import.meta.env.VITE_API_TIMEOUT || '30000'),
  TOAST: 3000,
  DEBOUNCE: 500,
};

/**
 * Páginas por tabela
 */
export const PAGINATION = {
  DEFAULT_LIMIT: 20,
  MAX_LIMIT: 100,
};

/**
 * Cache times (segundos)
 */
export const CACHE_TIMES = {
  SISTEMAS: 24 * 60 * 60, // 24 horas (dados estáticos)
  CLIENTES: 5 * 60,       // 5 minutos
  ASSINATURAS: 5 * 60,    // 5 minutos
};

/**
 * Status codes HTTP
 */
export const HTTP_STATUS = {
  OK: 200,
  CREATED: 201,
  BAD_REQUEST: 400,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  CONFLICT: 409,
  SERVER_ERROR: 500,
};

/**
 * Mensagens padrão
 */
export const MESSAGES = {
  SUCCESS: 'Operação realizada com sucesso!',
  ERROR: 'Ocorreu um erro. Tente novamente.',
  LOADING: 'Carregando...',
  NO_DATA: 'Nenhum dado encontrado.',
  CONFIRM_DELETE: 'Tem certeza que deseja deletar?',
  INVALID_EMAIL: 'Email inválido.',
  INVALID_CPF: 'CPF/CNPJ inválido.',
};
```

**Actions**:
- [ ] Criar arquivo `web/src/config/constants.ts`
- [ ] Copiar constantes
- [ ] Usar em toda aplicação

---

### 7. Types HTTP Client

**Arquivo**: `web/src/config/http-client.ts`

```typescript
import { api } from '@/services/api';
import type { AxiosRequestConfig } from 'axios';
import type { ApiResponse } from '@/types/api';

/**
 * GET request com type-safety
 */
export async function httpGet<T = any>(
  url: string,
  config?: AxiosRequestConfig
): Promise<T> {
  const response = await api.get<ApiResponse<T>>(url, config);
  return response.data.result;
}

/**
 * POST request com type-safety
 */
export async function httpPost<T = any>(
  url: string,
  data?: any,
  config?: AxiosRequestConfig
): Promise<T> {
  const response = await api.post<ApiResponse<T>>(url, data, config);
  return response.data.result;
}

/**
 * PUT request com type-safety
 */
export async function httpPut<T = any>(
  url: string,
  data?: any,
  config?: AxiosRequestConfig
): Promise<T> {
  const response = await api.put<ApiResponse<T>>(url, data, config);
  return response.data.result;
}

/**
 * DELETE request com type-safety
 */
export async function httpDelete<T = any>(
  url: string,
  config?: AxiosRequestConfig
): Promise<T> {
  const response = await api.delete<ApiResponse<T>>(url, config);
  return response.data.result;
}
```

**Actions**:
- [ ] Criar arquivo `web/src/config/http-client.ts`
- [ ] Copiar utilitários
- [ ] Usar em React Query hooks

---

### 8. ESLint + Prettier

**Arquivo**: `.eslintrc.cjs` (já existe, verificar)

**Verify**:
```json
{
  "extends": [
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:react-hooks/recommended",
    "prettier"
  ],
  "rules": {
    "no-unused-vars": "warn",
    "@typescript-eslint/no-unused-vars": "warn",
    "@typescript-eslint/no-explicit-any": "warn"
  }
}
```

**Actions**:
- [ ] Verificar `.eslintrc.cjs` está correto
- [ ] Verificar `.prettierrc` está correto
- [ ] Executar: `npm run lint`
- [ ] Executar: `npm run format`

---

### 9. TypeScript Strict Mode

**Arquivo**: `web/tsconfig.json`

**Verify**:
```json
{
  "compilerOptions": {
    "strict": true,
    "noImplicitAny": true,
    "strictNullChecks": true,
    "strictFunctionTypes": true,
    "strictPropertyInitialization": true,
    "noImplicitThis": true,
    "alwaysStrict": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noImplicitReturns": true,
    "noFallthroughCasesInSwitch": true,
    "resolveJsonModule": true,
    "baseUrl": ".",
    "paths": {
      "@/*": ["src/*"]
    }
  }
}
```

**Actions**:
- [ ] Verificar `tsconfig.json`
- [ ] Habilitar `strict: true`
- [ ] Habilitar `noUnusedLocals: true`
- [ ] Habilitar `noImplicitAny: true`

---

### 10. Git Workflow

**Actions**:
- [ ] Verificar `.gitignore` contém `node_modules`, `.env`, `.env.local`
- [ ] Criar branch: `git checkout -b feat/module-1-setup`
- [ ] Commit: `git add . && git commit -m "feat: module 1 - base configuration"`
- [ ] Push: `git push origin feat/module-1-setup`
- [ ] Criar Pull Request

---

### 11. Testes Iniciais

**Actions**:
- [ ] Executar: `npm run dev`
- [ ] Testar se API está respondendo em `http://localhost:8000/api`
- [ ] Testar se frontend inicia sem erros em `http://localhost:5173`
- [ ] Testar se Toast funciona: `console.log` na página
- [ ] Testar se TypeScript está strict
- [ ] Testar se ESLint roda sem erros: `npm run lint`

---

### 12. Criar README de Setup

**Arquivo**: `web/SETUP_GUIDE.md`

```markdown
# Setup Guide - Frontend OrganizaAI

## Pré-requisitos
- Node.js >= 18.0
- npm >= 9.0

## Instalação

1. Instalar dependências:
\`\`\`bash
npm install
\`\`\`

2. Configurar `.env`:
\`\`\`bash
cp example.env .env
# Editar .env e configurar VITE_API_URL
\`\`\`

3. Iniciar servidor dev:
\`\`\`bash
npm run dev
\`\`\`

4. Abrir em browser:
\`\`\`
http://localhost:5173
\`\`\`

## Scripts Disponíveis

- `npm run dev` - Inicia servidor development
- `npm run build` - Build para produção
- `npm run preview` - Preview da build
- `npm run lint` - Roda ESLint
- `npm run format` - Formata código com Prettier
- `npm run type-check` - Valida tipos TypeScript

## Estrutura de Pastas

Veja `PLANO_ACAO_FRONTEND_ORGANIZAAI.md` para estrutura completa.

## API Endpoints

Todos os endpoints estão documentados em `../DOCS/openapi.yaml`
```

**Actions**:
- [ ] Criar arquivo `web/SETUP_GUIDE.md`

---

## 🎯 Resultado Esperado

Ao final do Módulo 1, você deve ter:

✅ `.env` configurado com `VITE_API_URL`  
✅ Axios cliente pronto em `services/api.ts`  
✅ React Query configurado e funcionando  
✅ TypeScript em strict mode  
✅ ESLint + Prettier funcionando  
✅ Toast notifications funcionando  
✅ API client com type-safety  
✅ Base pronta para Módulo 2 (Auth)

---

## 🚀 Próximo Passo

Após completar todas as tasks acima:
→ **MÓDULO 2: Authentication & Auth Context** (Dia 2)

---

**Status**: 🟡 Em Progresso  
**Última Atualização**: 05/11/2025
