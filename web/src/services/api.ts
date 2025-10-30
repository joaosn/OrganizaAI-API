import axios from 'axios';
import type { User } from '../models/UserModel';

const apiUrl = import.meta.env.VITE_API_URL;

// Exporta URL base para chamadas fora do axios (sendBeacon/fetch)
export const API_BASE_URL = apiUrl;

if (!apiUrl) {
  throw new Error('API URL not configured. Please check your environment variables.');
}

export interface ApiResponse<T = any> {
  result: T;
  error: boolean | string;
}


export interface ErrorResponse {
  message: string;
  status?: number;
  code?: string;
}

export function trataErro(error: any): ErrorResponse {
  const axiosError = error as any;
  
  // Se for AxiosError com response
  if (axiosError.response?.data) {
    const data = axiosError.response.data;
    
    // Pega a mensagem de erro do backend
    const message = data.error || data.result || 'Erro inesperado';
    
    return {
      message: typeof message === 'string' ? message : 'Erro inesperado',
      status: axiosError.response.status,
      code: axiosError.code
    };
  }
  
  // Erro de rede
  if (axiosError.code === 'ERR_NETWORK') {
    return {
      message: 'Erro de conexão com o servidor',
      code: axiosError.code
    };
  }
  
  // Timeout
  if (axiosError.code === 'ECONNABORTED') {
    return {
      message: 'Tempo limite excedido',
      code: axiosError.code
    };
  }
  
  // Fallback genérico
  return {
    message: axiosError.message || 'Erro inesperado',
    code: axiosError.code
  };
}


export const api = axios.create({
  baseURL: apiUrl,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true
});

export async function validateToken(): Promise<User | null> {
  try {
    const response = await api.get<ApiResponse<User>>('/validaToken');
    const user = response.data.result;
    if (user?.token) {
      localStorage.setItem('auth_token', user.token);
      localStorage.setItem('auth_user', JSON.stringify(user));
      return user;
    }
    return null;
  } catch (error) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    throw error;
  }
}

const sensitivePaths = [
  '/caixa-abrirDia',
  '/caixa-fecharDia',
  '/caixa-suprimento',
  '/caixa-sangria',
  '/caixa-transferencia',
  '/caixa-statusDia',
  '/caixa-relatorioFechaDia'
];

// Request interceptor to add token and validate sensitive routes
api.interceptors.request.use(
  async (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    const url = config.url || '';
    if (sensitivePaths.some(path => url.includes(path))) {
      await validateToken();
      const newToken = localStorage.getItem('auth_token');
      if (newToken) {
        config.headers.Authorization = `Bearer ${newToken}`;
      }
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor for handling auth errors
api.interceptors.response.use(
  response => response,
  async error => {
    if (error.response?.status === 401) {
      try {
        await validateToken();
        const retryConfig = error.config;
        const retryToken = localStorage.getItem('auth_token');
        if (retryToken) {
          retryConfig.headers.Authorization = `Bearer ${retryToken}`;
          return api(retryConfig);
        }
      } catch (_) {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

export default api;