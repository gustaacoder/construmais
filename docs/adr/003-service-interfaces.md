# ADR 003: Interface para Serviços de Métricas Financeiras

## Status
Aceito

## Data
2025-11-10

## Contexto
O serviço `ManagerCalcService` estava implementado como uma classe concreta sem interface, dificultando:
- Substituição da implementação para testes
- Extensão do comportamento
- Aplicação do princípio de Inversão de Dependência (SOLID)

## Decisão
Criamos a interface `FinancialMetricsServiceInterface` que define o contrato para serviços de métricas financeiras.

### Interface Definida:
```php
interface FinancialMetricsServiceInterface
{
    public function compute(Carbon $from, Carbon $to): array;
    public function pmre(Carbon $from, Carbon $to): float;
    public function pmrv(Carbon $from, Carbon $to): float;
    public function pmpf(Carbon $from, Carbon $to): float;
    public function cycles(float $pmre, float $pmrv, float $pmpf): array;
    public function minCash(float $cashCycleDays, float $expenseForecastYear): ?float;
}
```

### Implementação:
```php
class ManagerCalcService implements FinancialMetricsServiceInterface
{
    // Implementation...
}
```

## Consequências

### Positivas:
- ✅ **Dependency Inversion**: Depender de abstrações, não de implementações
- ✅ **Testabilidade**: Fácil criar mocks da interface para testes
- ✅ **Flexibilidade**: Múltiplas implementações possíveis (ex: MockFinancialMetricsService)
- ✅ **Documentação**: Interface serve como contrato e documentação
- ✅ **IDE Support**: Melhor autocomplete e verificação de tipos

### Negativas:
- ⚠️ Mais um arquivo para manter (trade-off aceitável)
- ⚠️ Se interface mudar, todas implementações devem mudar

## Service Provider Binding

Para usar a interface, seria necessário registrar no Service Provider:

```php
// Em AppServiceProvider::register()
$this->app->bind(
    FinancialMetricsServiceInterface::class,
    ManagerCalcService::class
);
```

**Status**: Não implementado ainda pois há apenas uma implementação. Será feito quando houver necessidade de múltiplas implementações ou mocking extensivo.

## Casos de Uso Futuros

### 1. Implementação Mock para Testes
```php
class MockFinancialMetricsService implements FinancialMetricsServiceInterface
{
    public function pmre(Carbon $from, Carbon $to): float
    {
        return 30.0; // Valor fixo para testes
    }
    // ...
}
```

### 2. Implementação com Cache
```php
class CachedFinancialMetricsService implements FinancialMetricsServiceInterface
{
    public function __construct(
        private FinancialMetricsServiceInterface $innerService,
        private Cache $cache
    ) {}
    
    public function pmre(Carbon $from, Carbon $to): float
    {
        return $this->cache->remember(
            "pmre:{$from}:{$to}",
            3600,
            fn() => $this->innerService->pmre($from, $to)
        );
    }
}
```

### 3. Implementação com Logging
```php
class LoggedFinancialMetricsService implements FinancialMetricsServiceInterface
{
    public function __construct(
        private FinancialMetricsServiceInterface $innerService,
        private Logger $logger
    ) {}
    
    public function pmre(Carbon $from, Carbon $to): float
    {
        $this->logger->info("Calculating PMRE", compact('from', 'to'));
        return $this->innerService->pmre($from, $to);
    }
}
```

## Referências
- [Dependency Inversion Principle](https://en.wikipedia.org/wiki/Dependency_inversion_principle)
- [Interface Segregation Principle](https://en.wikipedia.org/wiki/Interface_segregation_principle)
- [Laravel Service Container](https://laravel.com/docs/container)
