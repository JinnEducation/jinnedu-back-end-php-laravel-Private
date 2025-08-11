<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Constants\CurrencyController;

class UserHourlyPrice extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function getPriceAttribute($price){

        $currency_id = Request::header('currency_id');
        $currency = new CurrencyController();
        $currencyResponse = $currency->latestExchange($currency_id, false);
        if($currencyResponse['success']) {
            $currency_exchange = $currencyResponse['result']->exchange;
            $price = round($price*$currency_exchange,2);
        }

        return $price;
    }
}
