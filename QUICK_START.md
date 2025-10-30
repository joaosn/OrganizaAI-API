# ⚡ QUICK START - OrganizaAI API

## 🚀 5 Minutos para Começar

### 1️⃣ Clonar e Configurar
```bash
git clone https://github.com/seu-usuario/organizaai.git
cd organizaai
cp api/.env.example api/.env

# Editar .env com suas credenciais MySQL
```

### 2️⃣ Criar Banco de Dados
```bash
mysql -u root -p < api/SQL/DDL.SQL
```

### 3️⃣ Iniciar Servidor
```bash
cd api
php -S localhost:8000 -t public/
```

### 4️⃣ Fazer Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@organizaai.com","senha":"senha"}'
```

Copie o `jwt_token` retornado.

### 5️⃣ Testar Endpoint
```bash
curl -X GET http://localhost:8000/api/clientes \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

---

## 📮 Usando Postman

1. Abra **Postman**
2. Import → `DOCS/OrganizaAI_API_Collection.postman_collection.json`
3. Na aba **Variables**:
   - `base_url` = `http://localhost:8000/api`
   - `jwt_token` = TOKEN DO LOGIN
4. Clique em qualquer endpoint para testar

---

## 🔥 Operações Mais Comuns

### Criar Cliente
```bash
POST /clientes
{
  "tipo_pessoa": "PJ",
  "nome": "Empresa XYZ",
  "cpf_cnpj": "12345678000190",
  "email": "contato@xyz.com",
  "telefone": "+5511999999999",
  "ativo": 1
}
```

### Criar Assinatura
```bash
POST /assinaturas
{
  "idcliente": 1,
  "idplano": 1,
  "preco_negociado": 299.90,
  "aliquota_imposto": 15.5,
  "data_inicio": "2025-10-30",
  "data_vencimento": "2025-11-30",
  "status": "ativa"
}
```

### Renovar Assinatura
```bash
POST /assinaturas-avancado/renovar
{
  "idassinatura": 1,
  "novo_preco": 320.00,
  "nova_aliquota": 16.5
}
```

### Ver Dashboard
```bash
GET /relatorios/dashboard
```

---

## 📚 Documentação Completa

- **Guia Detalhado**: `DOCS/README_COMPLETO.md`
- **OpenAPI**: `DOCS/openapi.yaml`
- **Postman**: `DOCS/OrganizaAI_API_Collection.postman_collection.json`

---

## ✅ Pronto para Produção!

Todos os 67 endpoints estão funcionando e documentados.

**Divirta-se! 🎉**
