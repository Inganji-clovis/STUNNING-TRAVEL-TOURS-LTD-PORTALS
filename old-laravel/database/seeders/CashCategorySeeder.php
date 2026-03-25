<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashCategory;

class CashCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income
            ['name' => 'Ticket Sales', 'type' => 'income', 'classification' => 'none'],
            ['name' => 'Visa Processing Fees', 'type' => 'income', 'classification' => 'none'],
            ['name' => 'Consultancy Fees', 'type' => 'income', 'classification' => 'none'],
            ['name' => 'Other Income', 'type' => 'income', 'classification' => 'none'],
            
            // OPEX
            ['name' => 'Office Rent', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Electricity & Water', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Internet & Communication', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Staff Salaries', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Office Supplies', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Fuel & Transport', 'type' => 'expense', 'classification' => 'opex'],
            ['name' => 'Marketing & Ads', 'type' => 'expense', 'classification' => 'opex'],
            
            // CAPEX
            ['name' => 'Computer/Laptop Purchase', 'type' => 'expense', 'classification' => 'capex'],
            ['name' => 'Office Furniture', 'type' => 'expense', 'classification' => 'capex'],
            ['name' => 'Building Renovation', 'type' => 'expense', 'classification' => 'capex'],
            ['name' => 'Vehicle Purchase', 'type' => 'expense', 'classification' => 'capex'],
        ];

        foreach ($categories as $cat) {
            CashCategory::create($cat);
        }
    }
}
