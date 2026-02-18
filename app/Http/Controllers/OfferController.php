<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\Market\app\Services\ShoppingCartService;

class OfferController extends Controller
{
    public function potential(Request $request): View|Factory|Application
    {
        return view('market::components.data-tables.tables.offers-potential', [
            'cartItemsByUsers' => app(ShoppingCartService::class)->getCartItemsGroupedByUsers(),
        ]);
    }

}
