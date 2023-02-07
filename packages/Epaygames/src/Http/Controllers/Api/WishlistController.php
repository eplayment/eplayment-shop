<?php
namespace Epaygames\Http\Controllers\Api;


use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\RestApi\Http\Resources\V1\Shop\Checkout\CartResource;
use Epaygames\Http\Controllers\Controller;


class WishlistController extends Controller
{
    /**
     * Wishlist repository instance.
     *
     * @var \Webkul\Customer\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * Product repository instance.
     *
     * @var \Webkul\Customer\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller istance.
     *
     * @param  \Webkul\Customer\Repositories\WishlistRepository  $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository
    ) {
        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * Move product from wishlist to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCart(Request $request, $id)
    {
        $customer = $request->user();

        $wishlistItem = $this->wishlistRepository->findOneWhere([
            'channel_id'  => core()->getCurrentChannel()->id,
            'product_id'  => $id,
            'customer_id' => $customer->id,
        ]);

        if ($wishlistItem->customer_id != $customer->id) {
            return response([
                'message' => __('rest-api::app.common-response.error.security-warning'),
            ], 400);
        }

        $result = Cart::moveToCart($wishlistItem);

        if ($result) {
            Cart::collectTotals();

            $cart = Cart::getCart();

            return response([
                'data'    => $cart ? new CartResource($cart) : null,
                'message' => __('rest-api::app.wishlist.moved'),
            ]);
        }

        return response([
            'message' => __('rest-api::app.wishlist.option-missing'),
        ], 400);
    }
}
