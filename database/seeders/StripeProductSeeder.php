<?php

namespace Database\Seeders;

use App\Models\VipTier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class StripeProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!config('services.stripe.secret')) {
            $this->command->error('A chave secreta do Stripe (STRIPE_SECRET) não está configurada no seu .env ou config/services.php.');
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $vipTiers = VipTier::all();

        foreach ($vipTiers as $tier) {
            try {
                $productPayload = [
                    'name' => $tier->name,
                    'description' => $tier->description,
                ];

                if ($tier->stripe_product_id) {
                    $this->command->info("Atualizando produto: {$tier->name}");
                    $stripeProduct = Product::update($tier->stripe_product_id, $productPayload);
                } else {
                    $this->command->info("Criando produto: {$tier->name}");
                    $stripeProduct = Product::create($productPayload);
                    $tier->stripe_product_id = $stripeProduct->id;
                }

                $pricePayload = [
                    'unit_amount' => $tier->price_in_cents,
                    'currency' => 'brl',
                    'product' => $stripeProduct->id,
                ];

                if ($tier->stripe_price_id) {
                    $stripePrice = Price::retrieve($tier->stripe_price_id);
                    if ($stripePrice->unit_amount != $tier->price_in_cents) {
                        $this->command->warn("-> Preço desatualizado. Criando novo preço para {$tier->name}...");

                        Price::update($tier->stripe_price_id, ['active' => false]);

                        $newStripePrice = Price::create($pricePayload);

                        $tier->stripe_price_id = $newStripePrice->id;
                    }
                } else {
                    $this->command->info("-> Criando preço para {$tier->name}");
                    $stripePrice = Price::create($pricePayload);
                    $tier->stripe_price_id = $stripePrice->id;
                }

                $tier->save();
            } catch (\Exception $e) {
                $this->command->error("Falha ao sincronizar '{$tier->name}': " . $e->getMessage());

                Log::channel('stripe_sync_products')->error("Erro na sincronização com o Stripe para VipTier ID {$tier->id}: " . $e->getMessage());
            }
        }

        $this->command->info('Sincronização de produtos com o Stripe concluída.');
    }
}
