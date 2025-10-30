import { useState } from 'react';
import { useUsers } from '../../hooks/useUsers';
import { Button } from '../../components/Button';
import { Form } from './Form';
import { UserPlus, Trash2, Edit2 } from 'lucide-react';

export function UserList() {
  const [showForm, setShowForm] = useState(false);
  const { users, isLoading, error, deleteUser, isDeleting } = useUsers();

  if (isLoading) {
    return <div className="text-center py-6">Carregando...</div>;
  }

  if (error) {
    return <div className="text-center py-6 text-red-600">Erro ao carregar usuários</div>;
  }

  return (
    <div className="max-w-4xl mx-auto p-6 space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-2xl font-bold text-gray-900">Usuários</h1>
        <Button onClick={() => setShowForm(!showForm)}>
          <UserPlus className="w-4 h-4 mr-2" />
          Adicionar Usuário
        </Button>
      </div>

      {showForm && (
        <div className="mb-6">
          <Form onSuccess={() => setShowForm(false)} />
        </div>
      )}

      <div className="bg-white shadow-sm rounded-lg overflow-hidden">
        {users.length === 0 ? (
          <p className="text-center py-6 text-gray-500">Nenhum usuário encontrado</p>
        ) : (
          <ul className="divide-y divide-gray-200">
            {users.map((user) => (
              <li key={user.id} className="p-4 flex items-center justify-between hover:bg-gray-50">
                <div>
                  <h3 className="text-sm font-medium text-gray-900">{user.name}</h3>
                  <p className="text-sm text-gray-500">{user.email}</p>
                  <p className="text-xs text-gray-400">
                    Adicionado em {new Date(user.createdAt).toLocaleDateString()}
                  </p>
                </div>
                <div className="flex gap-2">
                  <Button
                    variant="secondary"
                    onClick={() => deleteUser(user.id)}
                    disabled={isDeleting}
                    className="text-red-600 hover:text-red-700"
                  >
                    <Trash2 className="w-4 h-4" />
                  </Button>
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
}