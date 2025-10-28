<?php

namespace App\Models;

use App\Enums\StockMovementEnum;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($movement) {
            $product = $movement->product;

            if (in_array($movement->type, [StockMovementEnum::IN, StockMovementEnum::ADJUSTMENT_IN])) {
                $product->increment('stock', $movement->quantity);
            }

            if (in_array($movement->type, [StockMovementEnum::OUT, StockMovementEnum::ADJUSTMENT_OUT])) {
                if ($product->stock < $movement->quantity) {
                    throw new \Exception('Cannot remove more stock than available.');
                }

                $product->decrement('stock', $movement->quantity);
            }
        });
    }
}
