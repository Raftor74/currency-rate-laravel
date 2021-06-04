<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        return CurrencyResource::collection(Currency::all());
    }

    public function show($id)
    {
        $currency = Currency::query()->findOrFail($id);
        return new CurrencyResource($currency);
    }
}
