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
        $perPage = $request->get('perPage', 20);
        $currencies = Currency::query()->paginate($perPage);
        return CurrencyResource::collection($currencies);
    }

    public function show($id)
    {
        $currency = Currency::query()->findOrFail($id);
        return new CurrencyResource($currency);
    }
}
