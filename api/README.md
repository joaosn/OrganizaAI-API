# 📦 clickjoias - Ambiente Docker

Este projeto utiliza Docker para rodar a aplicação PHP com Apache e Composer de forma isolada e reaproveitável. A ideia é usar **uma única imagem Docker compartilhada entre múltiplos projetos** como `papelzero`, `clickjoias`, `cobrafacil`, etc.

---

## 🚀 Como funciona

- A imagem base `api_mvc:latest` é criada **uma única vez**.
- Cada projeto apenas usa essa imagem via `docker-compose.yml`.
- O container `composer` é utilizado para rodar `composer install` quando necessário.

---

## 🐳 Como rodar o projeto

### 1. Construir a imagem base (somente uma vez)
acessar onde esta seu Dockerfile e rode:
```bash
docker build -t api_mvc:latest .
```

> Faça isso em um projeto base (pode ser qualquer um).

### 2. Subir o projeto atual (ex: PapelZero)
```bash
docker-compose up -d
```

### 3. Rodar o Composer manualmente (opcional)
```bash
docker-compose run --rm composer composer install
```

---

## 📁 Estrutura recomendada

Cada projeto pode ter sua própria pasta com esse `docker-compose.yml`: 
lenbre-se de mudar porta caso queria rodar varios

```yaml
version: "3.8"

services:
  papelzero:
    image: api_mvc:latest
    container_name: papelzero
    volumes:
      - ./:/var/www/html
    ports:
      - "8085:80"
  composer:
    image: composer/composer
    command: install
    volumes:
      - ./:/app

```

```yaml
version: "3.8"

services:
  clickjoias:
    image: api_mvc:latest
    container_name: clickjoias
    volumes:
      - ./:/var/www/html
    ports:
      - "8086:80"
  composer:
    image: composer/composer
    command: install
    volumes:
      - ./:/app

```

---

## 🧠 Dicas úteis

### pra acessar banco rodando fora do container coloque seu IPv4 da maquina 
EX:  DB_HOST = '192.168.1.12'

### Ver containers ativos
```bash
docker ps
```

### Ver imagens locais
```bash
docker images
```

### Deletar um container (mantendo a imagem)
```bash
docker rm -f nome_do_container
```

### Deletar imagem (só se quiser realmente limpar tudo)
```bash
docker rmi api_mvc:latest
```

---

## ✅ Vantagens

- Zero repetição de build entre projetos
- Containers organizados por projeto
- Composer desacoplado para rodar só quando quiser

---

Feito com 💙 para acelerar seus projetos PHP com Docker.

> Nome do sistema: **clickjoias**
> Missão: **Mini ERP mais facil do brasil**

## Endpoint de Impressão de Etiquetas

- **POST /printEtiqueta**
  - Parâmetros no corpo: `idproduto`, `idetiqueta`.
  - Retorna: `{ zpl: "<comandos ZPL>" }`.

Exemplo de modelo de etiqueta (campo `template_zpl`):
```
^XA
^FO20,20^ADN,36,20^FD{{nome_produto}}^FS
^BY2,2,50^FO20,70^BC^FD{{codigo_barras}}^FS
^FO20,140^ADN,28,14^FDPreço: {{preco}}^FS
^XZ
```
