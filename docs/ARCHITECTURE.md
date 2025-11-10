# Arquitetura do Sistema ConstruMais

## Visão Geral

O ConstruMais é um sistema de gestão empresarial desenvolvido com Laravel 12 e Filament 3, seguindo princípios de Clean Architecture e SOLID.

## Diagrama de Camadas

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│                   (Filament Admin Panel)                     │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  Resources  │  │    Pages     │  │   Widgets    │      │
│  └─────────────┘  └──────────────┘  └──────────────┘      │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                  BUSINESS LOGIC LAYER                        │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Actions   │  │   Services   │  │  Observers   │      │
│  └─────────────┘  └──────────────┘  └──────────────┘      │
│  ┌─────────────┐  ┌──────────────┐                         │
│  │    DTOs     │  │  Exceptions  │                         │
│  └─────────────┘  └──────────────┘                         │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                    DATA ACCESS LAYER                         │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Models    │  │  Eloquent    │  │   Scopes     │      │
│  └─────────────┘  └──────────────┘  └──────────────┘      │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                     DATABASE LAYER                           │
│                     (SQLite/MySQL)                           │
└─────────────────────────────────────────────────────────────┘
```

## Componentes Principais

### 1. Presentation Layer (Camada de Apresentação)

#### Filament Resources
Gerenciam CRUD e interface do usuário para entidades:
- `ProductResource`
- `SaleResource`
- `CustomerResource`
- `SupplierResource`
- `StockEntryResource`
- `ReceivableResource`
- `PayableResource`

#### Filament Pages
Páginas customizadas:
- `ManagementDashboard` - Dashboard gerencial com métricas

#### Filament Widgets
Componentes reutilizáveis:
- `RealtimeMetrics` - Métricas em tempo real

### 2. Business Logic Layer (Camada de Lógica de Negócio)

#### Actions (Ações)
Operações de negócio isoladas e testáveis:
- `CreateReceivablesAction` - Criação de contas a receber
- `CreatePayableAction` - Criação de contas a pagar

**Responsabilidades:**
- Executar uma operação de negócio específica
- Validar regras de negócio
- Garantir consistência de dados

#### Services (Serviços)
Lógica de negócio complexa:
- `ManagerCalcService` - Cálculos de métricas financeiras
  - PMRE (Prazo Médio de Renovação de Estoque)
  - PMRV (Prazo Médio de Recebimento de Vendas)
  - PMPF (Prazo Médio de Pagamento a Fornecedores)

**Responsabilidades:**
- Orquestrar múltiplas operações
- Implementar algoritmos complexos
- Agregar dados de múltiplas fontes

#### DTOs (Data Transfer Objects)
Objetos de transferência de dados imutáveis:
- `SaleTotalsDTO` - Totais de venda
- `FinancialCyclesDTO` - Ciclos financeiros

**Responsabilidades:**
- Encapsular dados estruturados
- Garantir type safety
- Fornecer imutabilidade

#### Observers (Observadores)
Reagem a eventos de modelos:
- `SaleObserver` - Automatiza criação de recebíveis
- `StockEntryObserver` - Automatiza criação de contas a pagar

**Responsabilidades:**
- Delegar para Actions
- Manter-se simples e focados
- Garantir side effects previsíveis

#### Exceptions (Exceções)
Erros de domínio específicos:
- `InvalidSaleDataException`
- `InsufficientStockException`

### 3. Data Access Layer (Camada de Acesso a Dados)

#### Models (Modelos Eloquent)
Entidades do domínio:
- `Product` - Produtos
- `Sale` - Vendas
- `SaleItem` - Itens de venda
- `Customer` - Clientes
- `Supplier` - Fornecedores
- `StockEntry` - Entradas de estoque
- `Receivable` - Contas a receber
- `Payable` - Contas a pagar

**Responsabilidades:**
- Definir estrutura de dados
- Definir relacionamentos
- Fornecer accessors e mutators
- Definir scopes para queries

### 4. Database Layer (Camada de Banco de Dados)

#### Migrations
Versionamento de schema:
- Estrutura de tabelas
- Índices para performance
- Foreign keys para integridade

#### Seeders
Dados iniciais e de teste

## Fluxo de Dados - Exemplo: Criação de Venda

```
┌──────────────┐
│   User       │
│   (UI)       │
└──────┬───────┘
       │
       ▼
┌──────────────────────┐
│  SaleResource        │  (Presentation)
│  Filament Form       │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  Sale Model          │  (Data Access)
│  save()              │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  SaleObserver        │  (Business Logic)
│  saving()            │
│  - recalcTotals()    │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  SaleTotalsDTO       │  (Business Logic)
│  fromSaleData()      │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  Sale Model          │  (Data Access)
│  Atualiza totais     │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  SaleObserver        │  (Business Logic)
│  saved()             │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────────┐
│  CreateReceivablesAction │  (Business Logic)
│  execute()               │
└──────┬───────────────────┘
       │
       ▼
┌──────────────────────┐
│  Receivable Model    │  (Data Access)
│  create()            │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│  Database            │
│  INSERT              │
└──────────────────────┘
```

## Princípios Aplicados

### SOLID

1. **Single Responsibility** - Cada classe tem uma responsabilidade
2. **Open/Closed** - Extensível via interfaces
3. **Liskov Substitution** - Implementações substituíveis
4. **Interface Segregation** - Interfaces específicas
5. **Dependency Inversion** - Dependência de abstrações

### DRY (Don't Repeat Yourself)
- Lógica reutilizável em Actions e Services
- DTOs evitam duplicação de cálculos

### KISS (Keep It Simple, Stupid)
- Métodos pequenos e focados
- Nomes descritivos
- Código auto-explicativo

## Performance

### Otimizações Implementadas

1. **Eager Loading** - Evita N+1 queries
   ```php
   Sale::with(['items.product', 'customer'])->get();
   ```

2. **Índices de Banco** - Queries rápidas
   ```php
   $table->index(['sale_date', 'customer_id', 'status']);
   ```

3. **Scopes Reutilizáveis** - Queries consistentes
   ```php
   Product::active()->lowStock()->get();
   ```

4. **DTOs Imutáveis** - Cache seguro de dados calculados

## Segurança

1. **Validação de Dados** - Em todas as entradas
2. **Proteção CSRF** - Laravel automático
3. **Mass Assignment Protection** - `$fillable` definido
4. **Transações de BD** - Para operações críticas
5. **Exceptions Customizadas** - Erros controlados

## Extensibilidade Futura

### Recursos Preparados para Adição:

1. **Cache Layer** - Interface permite decorators
2. **Logging** - Pode ser adicionado em Actions
3. **Events** - Podem substituir Observers
4. **API REST** - Resources podem ser reutilizados
5. **Queue Jobs** - Actions podem virar Jobs
6. **Multiple Databases** - Repository pattern facilitaria

## Referências

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)
