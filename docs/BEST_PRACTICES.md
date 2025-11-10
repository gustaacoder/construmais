# Guia de Melhores Práticas - ConstruMais

## Performance e Otimização

### 1. Evitar N+1 Queries

#### ❌ Problema
```php
$sales = Sale::all();
foreach ($sales as $sale) {
    echo $sale->customer->name; // Nova query para cada sale!
}
```

#### ✅ Solução
```php
$sales = Sale::with('customer')->get(); // Uma query apenas!
foreach ($sales as $sale) {
    echo $sale->customer->name;
}
```

### 2. Usar Scopes para Queries Reutilizáveis

#### ✅ Definir no Model
```php
// Product.php
public function scopeActive($query) {
    return $query->where('is_active', true);
}

public function scopeLowStock($query) {
    return $query->whereColumn('stock_on_hand', '<=', 'min_stock');
}
```

#### ✅ Usar nos Controllers/Resources
```php
$activeProducts = Product::active()->get();
$lowStockProducts = Product::active()->lowStock()->get();
```

### 3. Eager Loading em Relacionamentos

#### ✅ No Model (sempre carregar)
```php
protected $with = ['items', 'customer'];
```

#### ✅ Na Query (carregar sob demanda)
```php
Sale::with(['items.product', 'customer', 'receivables'])->get();
```

### 4. Índices de Banco de Dados

#### ✅ Migration com índices
```php
$table->index(['sale_date', 'customer_id', 'status']);
$table->index(['name', 'category', 'brand']);
```

## SOLID Principles

### Single Responsibility Principle (SRP)
Cada classe deve ter uma única responsabilidade.

#### ✅ Correto
```php
class CreateReceivablesAction {
    // Apenas cria recebíveis
}

class CalculateSaleTotalsService {
    // Apenas calcula totais
}
```

#### ❌ Incorreto
```php
class SaleHelper {
    public function createReceivables() { }
    public function calculateTotals() { }
    public function sendEmail() { }
    // Muitas responsabilidades!
}
```

### Dependency Inversion Principle (DIP)
Depender de abstrações, não de implementações concretas.

#### ✅ Correto
```php
public function __construct(
    private FinancialMetricsServiceInterface $metrics
) {}
```

#### ❌ Incorreto
```php
public function __construct(
    private ManagerCalcService $metrics // Implementação concreta
) {}
```

## Clean Code

### 1. Nomes Descritivos

#### ✅ Correto
```php
$averageInventoryTurnoverPeriod = $this->pmre($from, $to);
$customerActiveOrders = $customer->sales()->active()->get();
```

#### ❌ Incorreto
```php
$avg = $this->calc($x, $y);
$data = $obj->get();
```

### 2. Métodos Pequenos e Focados

#### ✅ Correto
```php
private function calculateAmount(StockEntry $entry): float
{
    return round(
        ((float) $entry->purchase_price) * (int) $entry->quantity,
        2
    );
}
```

#### ❌ Incorreto
```php
public function process($entry) {
    // 100 linhas de código fazendo várias coisas
}
```

### 3. Evitar "Magic Numbers"

#### ✅ Correto
```php
private const DAYS_PER_YEAR = 360;
private const DEFAULT_PAYMENT_TERMS = ['pix' => 0, 'debit' => 0, 'credit' => 30];

$minCash = $annualExpenses / ($cashCycle / self::DAYS_PER_YEAR);
```

#### ❌ Incorreto
```php
$minCash = $annualExpenses / ($cashCycle / 360); // O que é 360?
```

## Testabilidade

### 1. Usar Dependency Injection

#### ✅ Correto
```php
class SaleObserver
{
    public function __construct(
        private CreateReceivablesAction $createReceivablesAction
    ) {}
}
```

#### ❌ Incorreto
```php
class SaleObserver
{
    public function saved(Sale $sale): void
    {
        $action = new CreateReceivablesAction(); // Hard dependency
    }
}
```

### 2. Testar Comportamento, Não Implementação

#### ✅ Correto
```php
public function test_calculates_totals_correctly(): void
{
    $dto = SaleTotalsDTO::fromSaleData($items);
    
    $this->assertEquals(100.0, $dto->grandTotal);
}
```

#### ❌ Incorreto
```php
public function test_uses_specific_formula(): void
{
    // Testa detalhes internos de implementação
}
```

## Segurança

### 1. Validação de Dados

#### ✅ Sempre validar antes de processar
```php
if (!$product->hasSufficientStock($quantity)) {
    throw InsufficientStockException::forProduct(
        $product->name,
        $quantity,
        $product->stock_on_hand
    );
}
```

### 2. Usar Transações para Operações Críticas

#### ✅ Correto
```php
DB::transaction(function () use ($sale) {
    $sale->receivables()->delete();
    // Criar novos recebíveis
});
```

### 3. Proteção contra Mass Assignment

#### ✅ Definir $fillable ou $guarded
```php
protected $fillable = [
    'name',
    'email',
    // Apenas campos permitidos
];
```

## Estrutura de Código

### Organização de Arquivos
```
app/
├── Actions/          # Operações de negócio
├── Contracts/        # Interfaces
├── DTOs/            # Data Transfer Objects
├── Exceptions/      # Exceções customizadas
├── Models/          # Eloquent Models
├── Observers/       # Model Observers
├── Services/        # Serviços de negócio
└── Filament/        # Admin Panel
    ├── Pages/
    ├── Resources/
    └── Widgets/
```

### Convenções de Nomenclatura

- **Models**: Singular, PascalCase (`Product`, `Sale`)
- **Controllers**: PascalCase + "Controller" (`ProductController`)
- **Actions**: PascalCase + "Action" (`CreateReceivablesAction`)
- **Services**: PascalCase + "Service" (`ManagerCalcService`)
- **DTOs**: PascalCase + "DTO" (`SaleTotalsDTO`)
- **Exceptions**: PascalCase + "Exception" (`InvalidSaleDataException`)
- **Interfaces**: PascalCase + "Interface" (`FinancialMetricsServiceInterface`)

## Próximos Passos Recomendados

1. **Implementar Cache** para cálculos financeiros frequentes
2. **Criar Policies** para autorização granular
3. **Adicionar Logging** estruturado para auditoria
4. **Implementar Queue** para operações pesadas
5. **Criar API REST** se necessário integrar com outros sistemas
6. **Implementar Eventos** para desacoplar ainda mais o código
7. **Adicionar Monitoring** com ferramentas como Laravel Telescope

## Referências

- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP The Right Way](https://phptherightway.com/)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Clean Code by Robert C. Martin](https://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882)
