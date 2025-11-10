# ADR 002: Uso de DTOs (Data Transfer Objects)

## Status
Aceito

## Data
2025-11-10

## Contexto
O projeto tinha lógica de cálculo espalhada em múltiplos lugares, especialmente para:
- Cálculo de totais de vendas
- Cálculo de métricas financeiras (ciclos operacionais)

### Problemas Identificados:
- Duplicação de lógica de cálculo
- Falta de type safety em estruturas de dados
- Dificuldade em garantir consistência dos cálculos
- Código procedural misturado com modelos

## Decisão
Implementamos **DTOs (Data Transfer Objects)** para encapsular dados e cálculos relacionados:

1. **SaleTotalsDTO**: Encapsula cálculos de totais de vendas
   - Subtotal, desconto, acréscimos, total geral
   - Método estático `fromSaleData()` para criação
   - Imutabilidade via `readonly` properties

2. **FinancialCyclesDTO**: Encapsula métricas financeiras
   - PMRE, PMRV, PMPF
   - Ciclo operacional e de caixa
   - Cálculo de caixa mínimo necessário

### Características dos DTOs:
```php
public readonly float $subtotal;
public readonly float $discountTotal;
public readonly float $surchargeTotal;
public readonly float $grandTotal;
```

- **Imutáveis**: Uso de `readonly` properties (PHP 8.2+)
- **Type Safe**: Tipagem forte em todos os campos
- **Auto-documentados**: Estrutura clara e nomes descritivos
- **Métodos factory**: Criação através de métodos estáticos nomeados

## Consequências

### Positivas:
- ✅ Type safety completo - Erros detectados em tempo de desenvolvimento
- ✅ Imutabilidade - Dados não podem ser alterados após criação
- ✅ Reutilização - DTOs podem ser usados em qualquer camada
- ✅ Testabilidade - Fácil criar e testar DTOs isoladamente
- ✅ Consistência - Cálculos sempre executados da mesma forma
- ✅ Documentação viva - A estrutura documenta o que está sendo transferido

### Negativas:
- ⚠️ Requer PHP 8.2+ (já é requisito do projeto)
- ⚠️ Mais código boilerplate (minimizado com readonly)

## Uso Recomendado

### ✅ Usar DTOs quando:
- Transferir dados entre camadas
- Encapsular cálculos complexos
- Garantir imutabilidade de dados
- Estruturas de dados usadas em múltiplos lugares

### ❌ Não usar DTOs quando:
- Dados são muito simples (1-2 campos)
- Dados são mutáveis por natureza
- Performance é crítica (overhead mínimo, mas existe)

## Exemplos de Uso

```php
// Criar DTO a partir de dados
$totals = SaleTotalsDTO::fromSaleData(
    items: $items,
    discountTotal: 50.0,
    surchargeTotal: 20.0
);

// Acessar dados (readonly)
echo $totals->grandTotal; // OK
$totals->grandTotal = 100; // ERRO: Property is readonly

// Converter para array
$data = $totals->toArray();
```

## Referências
- [Data Transfer Object Pattern](https://martinfowler.com/eaaCatalog/dataTransferObject.html)
- [PHP 8.2 Readonly Properties](https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.readonly-properties)
