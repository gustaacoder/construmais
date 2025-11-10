<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public static function forProduct(string $productName, int $requested, int $available): self
    {
        return new self(
            "Estoque insuficiente para o produto '{$productName}'. ".
            "Solicitado: {$requested}, Disponível: {$available}"
        );
    }
}
