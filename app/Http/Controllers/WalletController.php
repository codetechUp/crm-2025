<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()
            ->wallet()
            ->with('accounts.config')
            ->first();
    }
}
