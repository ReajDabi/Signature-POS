<?php

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Local/testing demo data only. Branch names are placeholders until the client confirms them. */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        User::create(['name' => 'HQ Admin', 'email' => 'hq@signature.test', 'password' => 'password'])
            ->assignRole(UserRole::HqAdmin->value);

        $services = collect([
            ['Haircut (Women)', 'Hair', '350.00', 45], ['Haircut (Men)', 'Hair', '200.00', 30],
            ['Hair Color (Full)', 'Hair', '1800.00', 120], ['Rebond', 'Hair', '2500.00', 180],
            ['Manicure', 'Nail', '250.00', 45], ['Pedicure', 'Nail', '300.00', 60],
            ['Gel Polish', 'Nail', '450.00', 60], ['Foot Spa', 'Nail', '500.00', 60],
        ])->mapWithKeys(fn ($s) => [$s[0] => Service::create([
            'branch_id' => null, 'name' => $s[0], 'category' => $s[1], 'price' => $s[2], 'duration_min' => $s[3],
        ])]);

        $products = collect([
            ['Hair Serum 100ml', 'bottle', 24, 5], ['Hair Color Tube', 'tube', 40, 10], ['Cuticle Oil', 'bottle', 18, 4],
            ['Nail Polish Remover', 'bottle', 12, 3], ['Gel Polish', 'bottle', 30, 8], ['Rebond Cream', 'jar', 6, 2],
        ])->mapWithKeys(fn ($p) => [$p[0] => Product::create(['name' => $p[0], 'unit' => $p[1]])]);

        $services['Hair Color (Full)']->products()->attach($products['Hair Color Tube']->id, ['qty_consumed' => '1.00']);
        $services['Rebond']->products()->attach($products['Rebond Cream']->id, ['qty_consumed' => '1.00']);
        $services['Manicure']->products()->attach($products['Cuticle Oil']->id, ['qty_consumed' => '0.05']);
        $services['Pedicure']->products()->attach($products['Cuticle Oil']->id, ['qty_consumed' => '0.05']);
        $services['Gel Polish']->products()->attach($products['Gel Polish']->id, ['qty_consumed' => '0.10']);

        foreach ([['Tagum City Center', 'TCC', 'center'], ['Apokon', 'APK', 'apokon'], ['Visayan Village', 'VSV', 'visayan']] as [$name, $code, $slug]) {
            $branch = Branch::create(['name' => $name, 'code' => $code, 'address' => $name.', Tagum City']);

            User::create(['name' => "$name Manager", 'email' => "manager.$slug@signature.test", 'password' => 'password', 'branch_id' => $branch->id])
                ->assignRole(UserRole::BranchManager->value);
            User::create(['name' => "$name Cashier", 'email' => "cashier.$slug@signature.test", 'password' => 'password', 'branch_id' => $branch->id])
                ->assignRole(UserRole::BranchCashier->value);

            foreach ([['Maria Santos', '15.00'], ['Jun Reyes', '12.00'], ['Ana Dela Cruz', '10.00'], ['Liza Mendoza', '10.00']] as [$tech, $rate]) {
                Technician::create(['branch_id' => $branch->id, 'name' => $tech, 'commission_rate' => $rate]);
            }

            foreach ([['Senior citizen 20%', DiscountType::Percent, '20.00'], ['PWD 20%', DiscountType::Percent, '20.00'], ['Promo 100 off', DiscountType::Flat, '100.00']] as [$label, $type, $value]) {
                Discount::create(['branch_id' => $branch->id, 'label' => $label, 'type' => $type, 'value' => $value]);
            }

            foreach ([['Hair Serum 100ml', 24, 5], ['Hair Color Tube', 40, 10], ['Cuticle Oil', 18, 4], ['Nail Polish Remover', 12, 3], ['Gel Polish', 30, 8], ['Rebond Cream', 6, 2]] as [$product, $qty, $min]) {
                BranchInventory::create([
                    'branch_id' => $branch->id, 'product_id' => $products[$product]->id,
                    'qty_on_hand' => $qty, 'low_stock_threshold' => $min,
                ]);
            }
        }
    }
}
