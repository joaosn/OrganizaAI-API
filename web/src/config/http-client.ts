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
