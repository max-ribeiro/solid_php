<?php
namespace services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderProcessorService {
    public function execute($product_id, Request $request){
        $product = DB::table('products')->find($product_id);

        $stock = DB::table('stocks')->find($product_id);

        if ($stock->quantity < 1) {
            throw new NotFoundHttpException('Estamos sem estoque');
        }

        $total = $this->applySpecialDiscount($product);

        $paymentSuccessMessage = '';

        if ($request->has('payment_method') && $request->input('payment_method') === 'stripe') {
            $paymentSuccessMessage = $this->processPaymentViaStripe('stripe', $total);
        }

        if (!empty($paymentSuccessMessage)) {
            DB::table('stocks')
                ->where('product_id', $product_id)
                ->update([
                    'quantity' => $stock->quantity - 1
                ]);

            return [
                'payment_message' => $paymentSuccessMessage,
                'discounted_price' => $total,
                'original_price' => $product->price,
                'message' => 'Obrigado, a sua encomenda esta sendo processada'
            ];
        }
    }
    private function processPaymentViaStripe($provider, $total) {
        $price = "\${$total}";
        return "Processando paragamento de {$price} via {$provider}";
    }
    private function applySpecialDiscount($product) {
        $discount = 0.20 * $product->price;
        return number_format(($product->price = $discount), 2);
    }

}
