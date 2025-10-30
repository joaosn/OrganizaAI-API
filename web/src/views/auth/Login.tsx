import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuthContext } from "@/contexts/AuthContext";
import { LoginCredentials, loginSchema } from "@/models/UserModel";
import { ValidationError } from "yup";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";

export function Login() {
  const navigate = useNavigate();
  const { login } = useAuthContext();
  const [formData, setFormData] = useState<LoginCredentials>({
    nome: "",
    senha: "",
  });
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      await loginSchema.validate(formData, { abortEarly: false });
      await login(formData);
      toast.success("Login realizado com sucesso!");
      navigate("/", { replace: true });
    } catch (error) {
      if (error instanceof ValidationError) {
        toast.error("Preencha os campos corretamente.", {
          description: error.errors.join(", "),
        });
      } else {
        toast.error("Erro ao fazer login", {
          description: "Credenciais inválidas.",
        });
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="flex min-h-screen overflow-x-hidden">
      {/* Esquerda - lado azul com texto (esconde no mobile) */}
      <div className="hidden md:flex w-1/2 bg-[#1976d2] items-center justify-center relative overflow-hidden">
        <div className="text-white text-xl font-semibold px-6 text-center z-10">
          Faça o Login em nossa <br />
          <span className="font-bold text-2xl">Plataforma</span>
        </div>
        <div className="absolute w-[280px] h-[280px] bg-white rounded-[100px] rotate-[30deg] opacity-10"></div>
      </div>

      {/* Direita - formulário */}
      <div className="relative w-full md:w-1/2 flex items-center justify-center bg-white px-4 py-8 md:py-0 overflow-hidden">
        {/* Formas decorativas visíveis em todas as telas */}
        <div className="absolute -top-16 -left-16 w-40 h-40 bg-[#1976d2] rounded-full opacity-20 z-0"></div>
        <div className="absolute bottom-4 right-4 w-24 h-24 bg-[#1976d2] rounded-full opacity-10 z-0"></div>

        <div className="w-full max-w-md bg-white p-8 rounded-lg shadow-xl z-10">
          <div className="flex justify-center mb-4">
            <img
              src="https://api-clickjoias.jztech.com.br/logo.png"
              alt="Click Joias Logo"
              className="w-40 h-32 object-contain"
            />
          </div>

          <h1 className="text-2xl font-semibold text-center mb-1">
            Bem vindo!
          </h1>
          <p className="text-center text-sm text-gray-500 mb-6">
            Preencha os dados do login para acessar
          </p>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-1">
              <Label htmlFor="nome">Usuário</Label>
              <Input
                id="nome"
                name="nome"
                value={formData.nome}
                onChange={(e) =>
                  setFormData({ ...formData, nome: e.target.value })
                }
                placeholder="jhonestodr@joias"
                autoComplete="username"
              />
            </div>

            <div className="space-y-1">
              <Label htmlFor="senha">Senha</Label>
              <Input
                id="senha"
                type="password"
                name="senha"
                value={formData.senha}
                onChange={(e) =>
                  setFormData({ ...formData, senha: e.target.value })
                }
                placeholder="senha de acesso"
                autoComplete="current-password"
              />
            </div>

            <Button
              type="submit"
              className="w-full bg-[#1976d2] hover:bg-[#125fb3] transition"
              disabled={isLoading}
            >
              {isLoading ? "Entrando..." : "ENTRAR"}
            </Button>
          </form>
        </div>
      </div>
    </div>
  );
}
