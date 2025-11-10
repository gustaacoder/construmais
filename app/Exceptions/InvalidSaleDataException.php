<?php

namespace App\Exceptions;

use Exception;

class InvalidSaleDataException extends Exception
{
    public static function emptyItems(): self
    {
        return new self('A venda deve conter pelo menos um item.');
    }

    public static function invalidQuantity(int $productId): self
    {
        return new self("Quantidade inválida para o produto ID: {$productId}");
    }

    public static function negativeTotal(): self
    {
        return new self('O total da venda não pode ser negativo.');
    }

    public static function cannotModifyPaidReceivables(): self
    {
        return new self('Não é possível modificar uma venda com recebíveis já pagos.');
    }
}
