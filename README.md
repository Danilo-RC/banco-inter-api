# Banco Inter API - Laravel Backend

Este é o backend do aplicativo Banco Inter, desenvolvido em Laravel com autenticação por tokens usando Laravel Sanctum.

## 🚀 Funcionalidades

- **Autenticação completa** (registro, login, logout)
- **Gestão de usuários** com upload de foto de perfil
- **Sistema de transações** (criar, listar, remover)
- **Atualização automática de saldo** baseada nas transações
- **API RESTful** com validação de dados
- **Segurança** com tokens de autenticação

## 📋 Pré-requisitos

- PHP 8.1 ou superior
- Composer
- MySQL/MariaDB (XAMPP recomendado)
- Extensões PHP: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON

## 🔧 Instalação

### 1. Clone o repositório
```bash
git clone https://github.com/Danilo-RC/banco-inter-api.git
cd banco-inter-api
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Configure o ambiente
```bash
# Copie o arquivo de configuração para XAMPP
cp .env.xampp .env

# Gere a chave da aplicação
php artisan key:generate
```

### 4. Configure o banco de dados
1. Inicie o XAMPP (Apache e MySQL)
2. Acesse http://localhost/phpmyadmin
3. Crie um banco de dados chamado `banco_inter`
4. Execute as migrations:
```bash
php artisan migrate
```

### 5. Configure o storage
```bash
php artisan storage:link
```

### 6. Inicie o servidor
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

A API estará disponível em: `http://localhost:8000`

## 📚 Endpoints da API

### Autenticação
- `POST /api/register` - Registrar novo usuário
- `POST /api/login` - Fazer login
- `POST /api/logout` - Fazer logout (requer token)

### Usuário
- `GET /api/user` - Obter dados do usuário (requer token)
- `POST /api/profile/photo` - Upload de foto de perfil (requer token)

### Transações
- `GET /api/transactions` - Listar transações do usuário (requer token)
- `POST /api/transactions` - Criar nova transação (requer token)
- `DELETE /api/transactions/{id}` - Remover transação (requer token)

## 🗄️ Estrutura do Banco de Dados

### Tabela `users`
- `id` - Chave primária
- `name` - Nome do usuário
- `email` - Email (único)
- `password` - Senha (hash)
- `saldo` - Saldo atual (decimal)
- `foto_perfil` - URL da foto de perfil
- `created_at` - Data de criação
- `updated_at` - Data de atualização

### Tabela `transactions`
- `id` - Chave primária da transação
- `user_id` - Chave estrangeira para o usuário
- `type` - Tipo da transação ('entrada' ou 'saida')
- `amount` - Valor da transação (decimal)
- `description` - Descrição da transação
- `created_at` - Data de criação
- `updated_at` - Data de atualização

**Nota sobre as chaves**: A `id` é a chave primária única de cada transação, enquanto `user_id` é a chave estrangeira que liga a transação ao usuário que a realizou. Isso é padrão em bancos de dados relacionais.

## 🔒 Autenticação

O sistema utiliza **Laravel Sanctum** para autenticação baseada em tokens:

1. O usuário faz login e recebe um token
2. O token deve ser enviado no header `Authorization: Bearer {token}` em todas as requisições protegidas
3. O token é válido até o logout ou expiração

## 📝 Exemplos de Uso

### Registro de usuário
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@email.com",
    "password": "123456"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@email.com",
    "password": "123456"
  }'
```

### Criar transação
```bash
curl -X POST http://localhost:8000/api/transactions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token}" \
  -d '{
    "type": "entrada",
    "amount": 1000.00,
    "description": "Salário"
  }'
```

## 🛠️ Desenvolvimento

### Comandos úteis
```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Recriar banco de dados
php artisan migrate:fresh

# Ver rotas
php artisan route:list

# Ver logs
tail -f storage/logs/laravel.log
```

### Estrutura de arquivos importantes
```
app/
├── Http/Controllers/
│   ├── AuthController.php      # Autenticação
│   ├── UserController.php      # Gestão de usuários
│   └── TransactionController.php # Gestão de transações
├── Models/
│   ├── User.php               # Model do usuário
│   └── Transaction.php        # Model da transação
database/
├── migrations/                # Migrations do banco
routes/
└── api.php                   # Rotas da API
```

## 🐛 Troubleshooting

### Erro de conexão com banco
- Verifique se o MySQL está rodando no XAMPP
- Confirme as configurações no arquivo `.env`
- Teste a conexão: `php artisan tinker` → `DB::connection()->getPdo()`

### Erro de token inválido
- Verifique se o token está sendo enviado corretamente
- Confirme se o usuário não foi removido do banco
- Teste com um novo login

### Erro de upload de foto
- Verifique se o link do storage foi criado: `php artisan storage:link`
- Confirme as permissões da pasta `storage/`

## 📄 Licença

Este projeto foi desenvolvido para fins educacionais e demonstrativos.
