# ConstruMais - Sistema de Gestão para Materiais de Construção

Sistema completo de gestão empresarial desenvolvido em Laravel 12 com Filament 3, focado no setor de materiais de construção.

## 📋 Funcionalidades

- **Gestão de Vendas**: Controle completo de vendas com múltiplas formas de pagamento
- **Gestão de Estoque**: Controle de entrada e saída de produtos
- **Gestão Financeira**: Contas a receber e a pagar
- **Gestão de Clientes e Fornecedores**: Cadastro completo
- **Dashboard Gerencial**: Métricas financeiras e ciclos operacionais
- **Cálculos Financeiros**: PMRE, PMRV, PMPF, Ciclo Operacional e Ciclo de Caixa

## 🏗️ Arquitetura do Projeto

### Estrutura de Diretórios

```
app/
├── Actions/              # Ações de negócio isoladas
│   ├── CreateReceivablesAction.php
│   └── CreatePayableAction.php
├── Contracts/            # Interfaces de serviços
│   └── FinancialMetricsServiceInterface.php
├── DTOs/                 # Data Transfer Objects
│   ├── SaleTotalsDTO.php
│   └── FinancialCyclesDTO.php
├── Exceptions/           # Exceções customizadas
│   ├── InvalidSaleDataException.php
│   └── InsufficientStockException.php
├── Filament/            # Recursos do Filament Admin
│   ├── Pages/
│   ├── Resources/
│   └── Widgets/
├── Models/              # Modelos Eloquent
├── Observers/           # Observers de modelos
├── Providers/           # Service Providers
└── Services/            # Camada de serviços
    └── ManagerCalcService.php
```

### Padrões Arquiteturais Implementados

#### 1. **Service Layer Pattern**
- Interface `FinancialMetricsServiceInterface` define contratos
- `ManagerCalcService` implementa lógica de cálculos financeiros
- Separação clara entre lógica de negócio e apresentação

#### 2. **Action Pattern**
- `CreateReceivablesAction`: Gerencia criação de recebíveis
- `CreatePayableAction`: Gerencia criação de contas a pagar
- Ações isoladas, testáveis e reutilizáveis

#### 3. **Data Transfer Objects (DTOs)**
- `SaleTotalsDTO`: Encapsula cálculos de totais de venda
- `FinancialCyclesDTO`: Encapsula métricas de ciclos financeiros
- Imutabilidade e type safety

#### 4. **Observer Pattern**
- `SaleObserver`: Automatiza criação de recebíveis
- `StockEntryObserver`: Automatiza criação de contas a pagar
- Delegação para Actions mantém observers limpos

#### 5. **Exception Handling**
- Exceções customizadas para contextos específicos
- Mensagens de erro em português para melhor UX
- Facilita debugging e tratamento de erros

### Camadas da Aplicação

```
┌─────────────────────────────────────┐
│     Filament Admin Panel (UI)      │
├─────────────────────────────────────┤
│      Resources & Pages Layer       │
├─────────────────────────────────────┤
│        Business Logic Layer         │
│  (Actions, Services, Observers)     │
├─────────────────────────────────────┤
│         Data Access Layer           │
│      (Models, Eloquent ORM)         │
├─────────────────────────────────────┤
│           Database Layer            │
└─────────────────────────────────────┘
```

## 🔧 Tecnologias Utilizadas

- **Laravel 12**: Framework PHP moderno
- **Filament 3**: Admin panel elegante e poderoso
- **SQLite**: Banco de dados (configurável para MySQL/PostgreSQL)
- **PHP 8.2+**: Tipagem forte, readonly properties
- **Vite**: Build tool para assets

## 📊 Modelos de Dados

### Principais Entidades

- **Product**: Produtos do catálogo
- **Customer**: Clientes
- **Supplier**: Fornecedores
- **Sale**: Vendas realizadas
- **SaleItem**: Itens da venda
- **StockEntry**: Entradas de estoque
- **Receivable**: Contas a receber
- **Payable**: Contas a pagar
- **ManagementSetting**: Configurações gerenciais

### Relacionamentos

```
Sale 1──N SaleItem N──1 Product
  │                        │
  │                        │
  │                        │
  1                        1
  │                        │
  N                        N
Receivable            StockEntry
                           │
                           │
                           1
                           │
                           N
                        Payable
```

## 🚀 Instalação

### Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+
- SQLite, MySQL ou PostgreSQL

### Passos

1. Clone o repositório
```bash
git clone https://github.com/gustaacoder/construmais.git
cd construmais
```

2. Instale as dependências
```bash
composer install
npm install
```

3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

4. Execute as migrações
```bash
php artisan migrate
```

5. Inicie o servidor de desenvolvimento
```bash
composer dev
```

Ou individualmente:
```bash
php artisan serve
npm run dev
```

## 🧪 Testes

Execute os testes com:
```bash
composer test
# ou
php artisan test
```

## 📈 Métricas Financeiras

O sistema calcula automaticamente:

- **PMRE** (Prazo Médio de Renovação de Estoque): Tempo médio que os produtos permanecem em estoque
- **PMRV** (Prazo Médio de Recebimento de Vendas): Tempo médio para receber das vendas
- **PMPF** (Prazo Médio de Pagamento a Fornecedores): Tempo médio para pagar fornecedores
- **Ciclo Operacional**: PMRE + PMRV
- **Ciclo de Caixa**: Ciclo Operacional - PMPF
- **Caixa Mínimo Necessário**: Calculado com base no ciclo de caixa

## 🔒 Segurança

- Validação de dados em todas as entradas
- Proteção CSRF ativa
- Autenticação via Filament
- Transações de banco de dados para operações críticas

## 📝 Boas Práticas Implementadas

1. **SOLID Principles**
   - Single Responsibility: Cada classe tem uma responsabilidade única
   - Open/Closed: Extensível via interfaces
   - Liskov Substitution: Implementações podem ser substituídas
   - Interface Segregation: Interfaces específicas
   - Dependency Inversion: Dependência de abstrações

2. **DRY (Don't Repeat Yourself)**
   - Lógica reutilizável em Actions e Services
   - DTOs para evitar duplicação de cálculos

3. **Clean Code**
   - Nomes descritivos
   - Métodos pequenos e focados
   - Comentários apenas onde necessário
   - Tipagem forte

4. **Performance**
   - Eager loading para evitar N+1 queries
   - Índices de banco de dados otimizados
   - Uso de scopes para queries reutilizáveis

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT.

## 👥 Autores

- **gustaacoder** - *Desenvolvimento inicial*

## 🙏 Agradecimentos

- Laravel Framework
- Filament Admin Panel
- Comunidade PHP/Laravel
