<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\AccSuppliesGoodsType;

class AccSupplierGoodsTypeComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    /**protected $users;*/

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $t = AccSuppliesGoodsType::active()->get();
        $this->type = $t;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('supplier_goods_type',$this->type);
    }
}
