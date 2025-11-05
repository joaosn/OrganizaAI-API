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
