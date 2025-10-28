<?php

namespace App\Rules;

use App\Enums\StockMovementEnum;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnoughStock implements ValidationRule
{
    protected ?int $productId;

    protected ?string $type;

    public function __construct(?int $productId = null, ?string $type = null)
    {
        $this->productId = $productId;
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! in_array($this->type, [StockMovementEnum::OUT, StockMovementEnum::ADJUSTMENT_OUT])) {
            return;
        }

        if (! $this->productId) {
            return;
        }

        $product = Product::find($this->productId);

        if (! $product) {
            $fail('The selected product is invalid.');

            return;
        }

        $availableStock = $product->stock;
        $requestedQuantity = (int) $value;

        if ($requestedQuantity > $availableStock) {
            $fail('The requested quantity of :input exceeds the available stock of '.$availableStock.'.');
        }
    }
}
