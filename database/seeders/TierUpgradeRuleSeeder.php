<?php

namespace Database\Seeders;

use App\Enums\RefineFailureOutcomeEnum;
use App\Models\Item;
use App\Models\TierUpgradeRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TierUpgradeRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TierUpgradeRule::query()->delete();

        $refiningMaterial = Item::query()->where('name', 'Fragmento de Aprimoramento')->first();

        if (!$refiningMaterial) {
            $this->command->error('O item "Fragmento de Aprimoramento" não foi encontrado. Rode o ItemSeeder primeiro.');
            return;
        }

        $rules = [
            // from_tier => [to_tier, req_item_qty, req_money, success_chance, failure_outcome]
            0 => [1, 1, 500,  95, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T0 -> T1
            1 => [2, 1, 1000, 90, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T1 -> T2
            2 => [3, 2, 2000, 80, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T2 -> T3
            3 => [4, 2, 4000, 70, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T3 -> T4
            4 => [5, 3, 7500, 60, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T4 -> T5
            5 => [6, 4, 12000, 50, RefineFailureOutcomeEnum::MAINTAIN_TIER], // T5 -> T6
            6 => [7, 5, 20000, 40, RefineFailureOutcomeEnum::DOWNGRADE_TIER], // T6 -> T7 (Falha começa a rebaixar o tier!)
            7 => [8, 6, 35000, 30, RefineFailureOutcomeEnum::DOWNGRADE_TIER], // T7 -> T8
        ];

        foreach ($rules as $fromTier => $rule) {
            TierUpgradeRule::query()->create([
                'from_tier' => $fromTier,
                'to_tier' => $rule[0],
                'required_item_id' => $refiningMaterial->id,
                'required_item_quantity' => $rule[1],
                'required_money' => $rule[2],
                'success_chance' => $rule[3],
                'failure_outcome' => $rule[4],
            ]);
        }

        $this->command->info('Regras de refinamento de tiers populadas com sucesso!');
    }
}
