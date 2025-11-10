# ADR 001: Implementação de Actions Pattern para Lógica de Negócio

## Status
Aceito

## Data
2025-11-10

## Contexto
O projeto ConstruMais inicialmente tinha toda a lógica de negócio dentro dos Observers (`SaleObserver` e `StockEntryObserver`), tornando o código difícil de testar, reutilizar e manter.

### Problemas Identificados:
- Observers com múltiplas responsabilidades
- Dificuldade em testar lógica de negócio isoladamente
- Código duplicado entre diferentes partes do sistema
- Baixa coesão e alto acoplamento

## Decisão
Implementamos o **Action Pattern** para encapsular operações de negócio específicas em classes dedicadas:

1. **CreateReceivablesAction**: Responsável por criar e gerenciar contas a receber
2. **CreatePayableAction**: Responsável por criar e gerenciar contas a pagar

### Características das Actions:
- Uma responsabilidade única e bem definida
- Métodos públicos mínimos e claros
- Fácil de testar isoladamente
- Reutilizável em diferentes contextos
- Injeção de dependências via construtor

## Consequências

### Positivas:
- ✅ Melhor testabilidade - Actions podem ser testadas isoladamente
- ✅ Maior reusabilidade - Actions podem ser usadas em qualquer lugar
- ✅ Observers mais limpos - Apenas delegam para Actions
- ✅ Separação de responsabilidades clara
- ✅ Facilita manutenção e evolução do código

### Negativas:
- ⚠️ Mais arquivos no projeto (trade-off aceitável)
- ⚠️ Curva de aprendizado inicial para novos desenvolvedores

## Alternativas Consideradas

### 1. Manter lógica nos Observers
**Rejeitada** porque dificulta testes e viola Single Responsibility Principle.

### 2. Service Layer apenas
**Parcialmente implementada** - Services foram mantidos para operações mais complexas, Actions para operações atômicas.

### 3. Repository Pattern
**Não implementada agora** - Eloquent ORM já fornece uma camada de abstração suficiente para o momento.

## Referências
- [Action Pattern in Laravel](https://laravel-news.com/laravel-actions)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
