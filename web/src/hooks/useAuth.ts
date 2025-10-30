import { useState, useCallback, useEffect } from 'react';
import { api, validateToken } from '../services/api';
import { User, LoginCredentials } from '../models/UserModel';
import { useThemeController } from '../contexts/ThemeContext';
import { toast } from "sonner";

interface ApiResponse {
  result: User;
  error: boolean;
}

export function useAuth() {
  const [user, setUser] = useState<User | null>(() => {
    const storedUser = localStorage.getItem('auth_user');
    return storedUser ? JSON.parse(storedUser) : null;
  });

  const [token, setToken] = useState<string | null>(() => {
    return localStorage.getItem('auth_token');
  });
  const { setThemeIndex } = useThemeController();

  // Definir logout primeiro
  const logout = useCallback(() => {
    setToken(null);
    setUser(null);
    toast.success('Logout realizado com sucesso!');
  }, []);

  useEffect(() => {
    if (user) {
      localStorage.setItem('auth_user', JSON.stringify(user));
    } else {
      localStorage.removeItem('auth_user');
    }
  }, [user]);

  useEffect(() => {
    if (token) {
      localStorage.setItem('auth_token', token);
    } else {
      localStorage.removeItem('auth_token');
    }
  }, [token]);

  useEffect(() => {
    if (!token) return;
    const interval = setInterval(async () => {
      try {
        const refreshed = await validateToken();
        if (refreshed) {
          setToken(refreshed.token);
          setUser(refreshed);
        }
      } catch (e) {
        logout();
      }
    }, 5 * 60 * 1000);
    return () => clearInterval(interval);
  }, [token, logout]);

  const login = useCallback(async (credentials: LoginCredentials): Promise<void> => {
    try {
      const response = await api.post<ApiResponse>('/login', credentials);
      const { result, error } = response.data;
      
      if (error || !result) {
        throw new Error('Login falhou');
      }

      if (result.token) {
        setToken(result.token);
        setUser(result);
        localStorage.setItem('clickjoias_theme', result.tema.toString()|| '1'); // ex: "1" ou "2"
        setThemeIndex(result.tema);
        //pegar e setar tema aqui ne 
        // showSuccessToast('Login realizado com sucesso!');
      } else {
        throw new Error('Token não encontrado na resposta');
      }
    } catch (error) {
      // showErrorToast(error);
      if (error instanceof Error) {
        throw new Error(error.message || 'Credenciais inválidas');
      } else {
        throw new Error('Erro desconhecido durante o login');
      }
    }
  }, []);

  return {
    user,
    isAuthenticated: !!token,
    login,
    logout
  };
}