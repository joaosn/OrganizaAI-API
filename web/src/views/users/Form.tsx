import { useState } from 'react';
import { useUsers } from '../../hooks/useUsers';
import { Button } from '../../components/Button';
import { CreateUserDTO, userSchema } from '../../models/UserModel';
import { ValidationError } from 'yup';

interface FormProps {
  onSuccess: () => void;
}

export function Form({ onSuccess }: FormProps) {
  const { createUser, isCreating } = useUsers();
  const [formData, setFormData] = useState<CreateUserDTO>({
    name: '',
    email: '',
  });
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    try {
      await userSchema.validate(formData, { abortEarly: false });
      await createUser(formData);
      onSuccess();
    } catch (error) {
      if (error instanceof ValidationError) {
        const validationErrors: Record<string, string> = {};
        error.inner.forEach((err) => {
          if (err.path) {
            validationErrors[err.path] = err.message;
          }
        });
        setErrors(validationErrors);
      } else {
        console.error('Erro ao criar usuário:', error);
      }
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4 bg-white p-6 rounded-lg shadow-sm">
      <div>
        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
          Nome
        </label>
        <input
          type="text"
          id="name"
          value={formData.name}
          onChange={(e) => {
            setFormData(prev => ({ ...prev, name: e.target.value }));
            setErrors(prev => ({ ...prev, name: '' }));
          }}
          className={`mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm
            ${errors.name ? 'border-red-300' : 'border-gray-300'}`}
        />
        {errors.name && (
          <p className="mt-1 text-sm text-red-600">{errors.name}</p>
        )}
      </div>
      <div>
        <label htmlFor="email" className="block text-sm font-medium text-gray-700">
          Email
        </label>
        <input
          type="email"
          id="email"
          value={formData.email}
          onChange={(e) => {
            setFormData(prev => ({ ...prev, email: e.target.value }));
            setErrors(prev => ({ ...prev, email: '' }));
          }}
          className={`mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm
            ${errors.email ? 'border-red-300' : 'border-gray-300'}`}
        />
        {errors.email && (
          <p className="mt-1 text-sm text-red-600">{errors.email}</p>
        )}
      </div>
      <Button 
        type="submit" 
        disabled={isCreating}
      >
        {isCreating ? 'Adicionando...' : 'Adicionar Usuário'}
      </Button>
    </form>
  );
}