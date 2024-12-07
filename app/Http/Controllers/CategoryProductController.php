<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\Product;

class CategoryProductController extends Controller
{
    /**
     * @param  Request  $request
     * @param           $categoryId
     *
     * @return View|Factory|Application
     */
    public function show(Request $request, $categoryId = null): View|Factory|Application
    {
        if ($categoryId !== null) {
            if ($category = Category::with([])->loadByFrontend($categoryId, 'web_uri')->first()) {
                $productsBuilder = $category->frontendProducts();
            } else {
                app()->abort(404);
            }
        } else {
            $productsBuilder = Product::with([])->frontendItems()->inRandomOrder();//->limit($itemsPerPage);
        }

        $productsCount = $productsBuilder->count();
        $currentPage = $request->get('page', 1);
        $itemsPerPage = 21; // @todo: from config
        $maxPages = (int) ceil($productsCount / $itemsPerPage);
        if ($currentPage > $maxPages) {
            $currentPage = $maxPages;
        }

        return view('website-base::page', [
            'title'           => ($categoryId !== null) ? $category->name : __('Random Products'),
            'category'        => ($categoryId !== null) ? $category : null,
            'productsBuilder' => $productsBuilder,
            'productsCount'   => $productsCount,
            'contentView'     => ($categoryId !== null) ? 'market::category-products' : 'market::products',
            'paginationData'  => [
                'maxPages'     => $maxPages,
                'currentPage'  => $currentPage,
                'itemsPerPage' => $itemsPerPage,
                'pageLink'     => ($categoryId !== null) ? ('/category-products/'.$categoryId.'/?page=%d') : '/category-products/?page=%d',
            ],
        ]);
    }
}
