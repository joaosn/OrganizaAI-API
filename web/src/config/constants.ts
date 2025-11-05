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
