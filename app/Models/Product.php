<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["name", "price"];

    public function getPriceEuroAttribute () {
        return (new CurrencyService())->convert($this->price, 'usd', 'eur');
    }
}
