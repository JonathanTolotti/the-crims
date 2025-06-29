<?php

namespace Database\Seeders;

use App\Enums\StoreProductTypeEnum;
use App\Models\StoreProduct;
use App\Models\VipTier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Product as StripeProduct;
use Stripe\Price as StripePrice;
use Exception;

class StoreProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando a sincronização de produtos da loja com o Stripe...');

        if (!config('services.stripe.secret')) {
            $this->command->error('A chave secreta do Stripe (STRIPE_SECRET) não está configurada.');
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // 1. Sincronizar produtos baseados nos Tiers de VIP
        $this->syncVipTiersAsProducts();

        // 2. Sincronizar produtos do tipo Pacote de Cash
        $this->syncCashPackageProducts();

        $this->command->info('Sincronização de produtos da loja concluída.');
    }

    private function syncVipTiersAsProducts(): void
    {
        $this->command->line('--- Sincronizando Pacotes VIP ---');
        $vipTiers = VipTier::all();

        foreach ($vipTiers as $tier) {
            $product = StoreProduct::query()->firstOrNew([
                'product_type' => StoreProductTypeEnum::VIP_SUBSCRIPTION,
                'metadata->vip_tier_id' => $tier->id,
            ]);

            $product->fill([
                'name' => "Assinatura {$tier->name}",
                'description' => $tier->description,
                'price_in_cents' => $tier->price_in_cents,
                'metadata' => [
                    'vip_tier_id' => $tier->id,
                    'duration_in_days' => $tier->duration_in_days,
                ]
            ]);

            $this->syncWithStripe($product);
        }
    }

    private function syncCashPackageProducts(): void
    {
        $this->command->line('--- Sincronizando Pacotes de Cash ---');

        $cashPackages = [
            ['name' => 'Pacote de 500 CrimsCoin', 'cash_amount' => 500, 'price_in_cents' => 499],
            ['name' => 'Pacote de 1200 CrimsCoin', 'cash_amount' => 1200, 'price_in_cents' => 999],
            ['name' => 'Pacote de 3000 CrimsCoin', 'cash_amount' => 3000, 'price_in_cents' => 2499],
        ];

        foreach ($cashPackages as $package) {
            $product = StoreProduct::query()->firstOrNew([
                'product_type' => StoreProductTypeEnum::CASH_PACKAGE,
                'metadata->cash_amount' => $package['cash_amount'],
            ]);

            $product->fill([
                'name' => $package['name'],
                'price_in_cents' => $package['price_in_cents'],
                'metadata' => ['cash_amount' => $package['cash_amount']]
            ]);

            $this->syncWithStripe($product);
        }
    }

    private function syncWithStripe(StoreProduct $product): void
    {
        try {
            $productPayload = ['name' => $product->name, 'description' => $product->description];

            if ($product->stripe_product_id) {
                StripeProduct::update($product->stripe_product_id, $productPayload);
            } else {
                $stripeProduct = StripeProduct::create($productPayload);
                $product->stripe_product_id = $stripeProduct->id;
            }

            $pricePayload = ['unit_amount' => $product->price_in_cents, 'currency' => 'brl', 'product' => $product->stripe_product_id];

            if ($product->stripe_price_id) {
                $stripePrice = StripePrice::retrieve($product->stripe_price_id);
                if ($stripePrice->unit_amount != $product->price_in_cents) {
                    StripePrice::update($product->stripe_price_id, ['active' => false]);
                    $newStripePrice = StripePrice::create($pricePayload);
                    $product->stripe_price_id = $newStripePrice->id;
                }
            } else {
                $stripePrice = StripePrice::create($pricePayload);
                $product->stripe_price_id = $stripePrice->id;
            }

            $product->save();
            $this->command->info("Sincronizado com sucesso: {$product->name}");

        } catch (Exception $e) {
            $this->command->error("Falha ao sincronizar '{$product->name}': " . $e->getMessage());
            Log::error("Erro na sincronização com o Stripe para StoreProduct: " . $e->getMessage());
        }
    }
}
