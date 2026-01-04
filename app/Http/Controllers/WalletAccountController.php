<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletAccountController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->wallet
            ->accounts()
            ->with('config')
            ->get();
    }

    public function configure(Request $request, WalletAccount $account)
    {
        $data = $request->validate([
            'iban' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'wallet_address' => 'nullable|string',
            'blockchain_network' => 'nullable|string',
        ]);

        $account->config()->updateOrCreate(
            [],
            $data
        );

        $account->update(['is_configured' => true]);

        return response()->json([
            'message' => 'Compte configuré avec succès',
            'account' => $account->load('config')
        ]);
    }
}

