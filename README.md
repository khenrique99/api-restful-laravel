# 🇺🇸 Laravel RESTful API - Vehicle Marketplace

A RESTful API developed in Laravel 12 for vehicle marketplace simulation with session-based authentication. Features a clean architecture with Repository pattern, Service layer, and comprehensive testing.

## 🏗️ Architecture

This project follows a layered architecture pattern suitable for enterprise applications:

```
┌─────────────────┐
│   Controllers   │ ← HTTP Layer (Routes, Validation, Responses)
├─────────────────┤
│    Services     │ ← Business Logic Layer (Use Cases, Domain Rules)
├─────────────────┤
│  Repositories   │ ← Data Access Layer (Database Abstraction)
├─────────────────┤
│     Models      │ ← Eloquent Models (Data Representation)
└─────────────────┘
```

### Key Components

- **Contracts/Repositories**: Interfaces for data access abstraction
- **Services**: Business logic encapsulation
- **Events**: Decoupled event-driven architecture
- **Policies**: Authorization and access control
- **Resources**: API response transformation
- **Requests**: Input validation and sanitization

## 🚀 Installation

### Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: PHP dependency manager
- **Node.js**: 18+ with NPM for frontend assets
- **Database**: MySQL 8.0+, PostgreSQL 13+, or SQLite 3.35+
- **Git**: Version control system

### Step-by-Step Installation

1. **Clone the Repository**

    ```bash
    git clone <repository-url>
    cd api-restful-laravel
    ```

2. **Install PHP Dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js Dependencies**

    ```bash
    npm install
    ```

4. **Environment Configuration**

    ```bash
    # Copy environment file
    cp .env.example .env

    # Generate application key
    php artisan key:generate
    ```

5. **Database Setup**

    ```bash
    # Configure your database in .env file
    # Example for MySQL:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_vehicle_api
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    # Run migrations
    php artisan migrate
    ```

6. **Database Seeding (Optional)**

    ```bash
    # Seed with sample data
    php artisan db:seed
    ```

7. **Build Frontend Assets**

    ```bash
    # For development
    npm run dev

    # For production
    npm run build
    ```

8. **Start the Development Server**

    ```bash
    php artisan serve
    ```

    The API will be available at: `http://127.0.0.1:8000`

## 📖 Usage

### API Documentation

Access the interactive Swagger documentation at:

```
http://127.0.0.1:8000/api/documentation
```

### Authentication

The API uses session-based authentication. Include session cookies in your requests.

#### Authentication Endpoints

| Method | Endpoint        | Description                 |
| ------ | --------------- | --------------------------- |
| POST   | `/api/register` | Register new user           |
| POST   | `/api/login`    | User login                  |
| POST   | `/api/logout`   | User logout                 |
| GET    | `/api/user`     | Get authenticated user data |

### Users Management

| Method | Endpoint          | Description         | Authorization    |
| ------ | ----------------- | ------------------- | ---------------- |
| GET    | `/api/users/{id}` | View user profile   | Own profile only |
| PUT    | `/api/users/{id}` | Update user profile | Own profile only |
| DELETE | `/api/users/{id}` | Delete user account | Own profile only |

### Vehicles Marketplace

| Method | Endpoint                  | Description          | Authorization |
| ------ | ------------------------- | -------------------- | ------------- |
| GET    | `/api/vehicles`           | List all vehicles    | Public        |
| POST   | `/api/vehicles`           | Create new vehicle   | Authenticated |
| GET    | `/api/vehicles/{id}`      | View vehicle details | Public        |
| PUT    | `/api/vehicles/{id}`      | Update vehicle       | Owner only    |
| DELETE | `/api/vehicles/{id}`      | Delete vehicle       | Owner only    |
| POST   | `/api/vehicles/{id}/buy`  | Purchase vehicle     | Authenticated |
| POST   | `/api/vehicles/{id}/sell` | Sell vehicle         | Owner only    |

### Business Rules

- **Vehicles**: Public read access, authenticated users can create/manage
- **Buy/Sell**: Only unowned vehicles can be purchased. Only owners can sell
- **Users**: Users can only access and modify their own data
- **Validation**: All inputs are validated using Form Requests
- **Responses**: Consistent JSON responses with success/error states

## 🧪 Testing

The project includes comprehensive test coverage with both unit and feature tests.

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage report
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/VehicleTest.php

# Run tests in a specific group
php artisan test --testsuite=Unit
```

### Test Structure

```
tests/
├── Feature/          # Integration tests (API endpoints)
│   ├── AuthTest.php
│   ├── UserCrudTest.php
│   ├── VehicleTest.php
│   └── VehiclePurchaseTest.php
└── Unit/            # Unit tests (isolated components)
    ├── Services/
    │   ├── Vehicles/
    │   └── Users/
    ├── Repositories/
    ├── Events/
    └── Policies/
```

### Test Coverage

- ✅ **Controllers**: HTTP request/response handling
- ✅ **Services**: Business logic and event dispatching
- ✅ **Repositories**: Data access and database operations
- ✅ **Events**: Event structure and dispatching
- ✅ **Policies**: Authorization rules
- ✅ **Validation**: Input sanitization and error handling
- ✅ **Error Handling**: 404, 422, 403, 401 responses

## 🛠️ Development

### Code Quality

```bash
# Run code formatting
vendor/bin/pint

# Run static analysis (if configured)
# composer run phpstan

# Run security checks
# composer run security-check
```

### Available Commands

```bash
# Generate API documentation
php artisan l5-swagger:generate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run queue worker (if using queues)
php artisan queue:work
```

### Project Structure

```
app/
├── Contracts/           # Interfaces
│   └── Repositories/
├── Events/             # Event classes
├── Http/
│   ├── Controllers/    # HTTP controllers
│   ├── Requests/       # Form requests
│   └── Resources/      # API resources
├── Models/             # Eloquent models
├── Policies/           # Authorization policies
├── Providers/          # Service providers
├── Repositories/       # Data repositories
└── Services/           # Business logic services

tests/                  # Test files
├── Feature/           # Integration tests
└── Unit/              # Unit tests
```

## 🔧 Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME="Laravel Vehicle API"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vehicle_api
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue (optional)
QUEUE_CONNECTION=database
```

### Security Considerations

- Use HTTPS in production
- Configure proper CORS settings
- Set `APP_DEBUG=false` in production
- Use strong database passwords
- Regularly update dependencies

## 📋 API Response Format

### Success Response

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "errors": null
}
```

### Error Response

```json
{
    "success": false,
    "message": "Validation failed",
    "data": null,
    "errors": {
        "field": ["Error message"]
    }
}
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support, please open an issue in the repository or contact the development team.

---

**Built with Laravel 12** | **PHP 8.2+** | **RESTful Design**

```bash
php artisan test
```

## 📝 Development

### Useful Commands

```bash
# Format code
vendor/bin/pint

# Generate Swagger documentation
php artisan l5-swagger:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Project Structure

- `app/Http/Controllers/` - API Controllers
- `app/Http/Requests/` - Input validations
- `app/Http/Resources/` - Response formatting
- `app/Models/` - Eloquent Models
- `database/migrations/` - Database migrations
- `routes/api.php` - API routes
- `tests/` - Automated tests

## 📄 License

This project is licensed under the MIT license.

---

# 🇧🇷 API RESTful Laravel - Marketplace de Veículos

Uma API RESTful desenvolvida em Laravel 12 para simulação de marketplace de veículos com autenticação via sessão.

## 🚀 Instalação

### Pré-requisitos

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite

### Passos de Instalação

1. **Clone o repositório**

    ```bash
    git clone <repository-url>
    cd api-restful-laravel
    ```

2. **Instale as dependências PHP**

    ```bash
    composer install
    ```

3. **Instale as dependências JavaScript**

    ```bash
    npm install
    ```

4. **Configure o ambiente**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure o banco de dados**
    - Edite `.env` com suas credenciais de banco
    - Execute as migrações:

    ```bash
    php artisan migrate
    ```

6. **Seed do banco (opcional)**

    ```bash
    php artisan db:seed
    ```

7. **Inicie o servidor**
    ```bash
    php artisan serve
    ```

## 📖 Uso

### Documentação da API

Acesse a documentação Swagger em: `http://127.0.0.1:8000/api/documentation`

### Endpoints Principais

#### Autenticação

- `POST /api/register` - Registrar novo usuário
- `POST /api/login` - Fazer login
- `POST /api/logout` - Fazer logout
- `GET /api/user` - Dados do usuário logado

#### Usuários

- `GET /api/users/{id}` - Ver perfil (apenas próprio)
- `PUT /api/users/{id}` - Atualizar perfil (apenas próprio)
- `DELETE /api/users/{id}` - Deletar conta (apenas própria)

#### Veículos (Marketplace)

- `GET /api/vehicles` - Listar todos os veículos
- `POST /api/vehicles` - Criar veículo
- `GET /api/vehicles/{id}` - Ver veículo específico
- `PUT /api/vehicles/{id}` - Atualizar veículo
- `POST /api/vehicles/{id}/buy` - Comprar veículo disponível
- `POST /api/vehicles/{id}/sell` - Vender veículo próprio

### Regras de Negócio

- **Veículos**: Qualquer um pode visualizar, apenas usuários autenticados podem criar
- **Compra/Venda**: Apenas veículos sem proprietário podem ser comprados. Apenas o dono pode vender
- **Usuários**: Cada usuário só pode modificar seus próprios dados

## 🧪 Testes

Execute os testes com:

```bash
php artisan test
```

## 📝 Desenvolvimento

### Comandos Úteis

```bash
# Formatar código
vendor/bin/pint

# Gerar documentação Swagger
php artisan l5-swagger:generate

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Estrutura do Projeto

- `app/Http/Controllers/` - Controladores da API
- `app/Http/Requests/` - Validações de entrada
- `app/Http/Resources/` - Formatação de respostas
- `app/Models/` - Modelos Eloquent
- `database/migrations/` - Migrações do banco
- `routes/api.php` - Rotas da API
- `tests/` - Testes automatizados

## 📄 Licença

Este projeto está sob a licença MIT.
