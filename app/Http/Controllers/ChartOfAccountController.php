<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class ChartOfAccountController extends Controller
{
    public function index()
    {

        return view(
            'setting.account.index',
            [
                'title' => 'Chart Of Account',
                'accounts' => ChartOfAccount::with('account')->get(),
            ]
        );
    }
}
