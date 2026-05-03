# 📋 MicroSaas To-Do

Aplicação de gerenciamento de tarefas no estilo Kanban, construída com **Laravel 12** e **PHP 8.3**, utilizando uma arquitetura orientada a repositórios. O projeto é containerizado com Docker e inclui suporte a filas via RabbitMQ.

---

## 🚀 Tecnologias

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.3 + Laravel 12 |
| Banco de dados | MySQL 8.0 (produção) / SQLite (testes) |
| Frontend | Tailwind CSS v4 + Vite |
| Containerização | Docker + Docker Compose |
| Servidor web | Nginx (Alpine) |
| Filas | RabbitMQ 3 |
| Testes | PHPUnit 11 |
| Linting | Laravel Pint |

---

## 📦 Estrutura do projeto

```
MicroSaas_To_do/
├── php-service/                  # Aplicação Laravel
│   ├── app/
│   │   ├── Http/Controllers/     # Controllers HTTP
│   │   ├── Models/               # Modelos Eloquent
│   │   │   ├── Board.php         # Quadro Kanban
│   │   │   └── Card.php          # Cartão/Tarefa
│   │   └── Repositories/
│   │       ├── Contracts/        # Interfaces dos repositórios
│   │       └── Eloquent/         # Implementações Eloquent
│   ├── database/
│   │   ├── migrations/           # Migrações do banco de dados
│   │   └── seeders/              # Seeders
│   ├── docker-config/
│   │   └── nginx/nginx.conf      # Configuração do Nginx
│   ├── routes/
│   │   └── web.php               # Rotas da aplicação
│   ├── tests/                    # Testes Feature e Unit
│   ├── Dockerfile
│   └── docker-compose.yaml
└── README.md
```

---

## 🗄️ Modelo de dados

### Board (Quadro)
| Campo | Tipo | Descrição |
|---|---|---|
| `id` | bigint | Chave primária |
| `title` | string | Nome do quadro |
| `created_at` | timestamp | Data de criação |
| `updated_at` | timestamp | Data de atualização |

### Card (Cartão)
| Campo | Tipo | Descrição |
|---|---|---|
| `id` | bigint | Chave primária |
| `board_id` | bigint (FK) | Referência ao quadro |
| `title` | string | Título do cartão |
| `description` | text (nullable) | Descrição opcional |
| `position` | integer | Posição no quadro (default: 0) |
| `created_at` | timestamp | Data de criação |
| `updated_at` | timestamp | Data de atualização |

---

## ⚙️ Pré-requisitos

- **Docker** e **Docker Compose**
- **PHP 8.2+** (para execução local sem Docker)
- **Composer**
- **Node.js 18+** e **npm**

---

## 🔧 Instalação e execução

### Com Docker (recomendado)

**1. Clone o repositório:**
```bash
git clone <url-do-repositorio>
cd MicroSaas_To_do/php-service
```

**2. Configure as variáveis de ambiente:**
```bash
cp .env.example .env
```

Edite o `.env` para apontar para o banco MySQL do Docker:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=microsaas
DB_USERNAME=root
DB_PASSWORD=root
```

**3. Suba os containers:**
```bash
docker-compose up -d --build
```

**4. Instale as dependências e configure a aplicação:**
```bash
docker exec -it micro-app composer install
docker exec -it micro-app php artisan key:generate
docker exec -it micro-app php artisan migrate
```

**5. Acesse a aplicação:**
- Aplicação: [http://localhost:8080](http://localhost:8080)
- RabbitMQ: [http://localhost:15672](http://localhost:15672) (usuário: `guest` / senha: `guest`)

---

### Sem Docker (ambiente local)

**1. Instale as dependências:**
```bash
cd php-service
composer install
npm install
```

**2. Configure o ambiente:**
```bash
cp .env.example .env
php artisan key:generate
```

**3. Execute as migrações e rode o servidor:**
```bash
php artisan migrate
composer run dev
```

O comando `dev` inicia simultaneamente o servidor PHP, o worker de filas, o log watcher (Pail) e o Vite.

---

## 🐳 Serviços Docker

| Serviço | Container | Porta |
|---|---|---|
| PHP-FPM (app) | `micro-app` | 9000 (interno) |
| Nginx | `micro-webserver` | `8080:80` |
| MySQL | `micro-db` | `3306:3306` |
| RabbitMQ | `micro-rabbitmq` | `5672`, `15672` |

---

## 🧪 Testes

```bash
# Com Docker
docker exec -it micro-app php artisan test

# Local
php artisan test

# Ou via composer
composer run test
```

Os testes rodam com SQLite em memória, conforme configurado no `phpunit.xml`, sem afetar o banco de dados de desenvolvimento.

---

## 🏗️ Arquitetura

O projeto segue o padrão **Repository Pattern**, desacoplando a lógica de negócio do acesso ao banco de dados:

```
Controller → Service → Repository Interface → Eloquent Repository → Model
```

- **`CardRepositoryInterface`** — define o contrato com os métodos `getAll`, `findById`, `create`, `update` e `delete`.
- **`EloquentCardRepository`** — implementa a interface utilizando os Models do Eloquent.

Essa abordagem facilita a troca de implementação (ex: de Eloquent para uma API externa) sem alterar os controllers ou a lógica de negócio.

---

## 🎨 Frontend

O projeto usa **Tailwind CSS v4** integrado via **Vite**. Para compilar os assets:

```bash
# Build para produção
npm run build

# Modo de desenvolvimento com hot reload
npm run dev
```

---

## 📝 Variáveis de ambiente relevantes

| Variável | Descrição | Padrão |
|---|---|---|
| `APP_KEY` | Chave de criptografia da aplicação | gerada via `artisan key:generate` |
| `APP_ENV` | Ambiente (`local`, `production`) | `local` |
| `DB_CONNECTION` | Driver de banco (`sqlite`, `mysql`) | `sqlite` |
| `DB_HOST` | Host do banco de dados | `127.0.0.1` |
| `DB_DATABASE` | Nome do banco de dados | `laravel` |
| `QUEUE_CONNECTION` | Driver de filas | `database` |

---

## 🤝 Contribuindo

1. Crie uma branch a partir de `main`:
   ```bash
   git checkout -b feature/minha-feature
   ```
2. Faça suas alterações e formate o código:
   ```bash
   ./vendor/bin/pint
   ```
3. Rode os testes para garantir que tudo está funcionando:
   ```bash
   php artisan test
   ```
4. Abra um Pull Request descrevendo as mudanças.

---

