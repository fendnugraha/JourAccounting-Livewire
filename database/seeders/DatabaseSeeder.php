<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Warehouse;
use Database\Factories\ContactFactory;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\ChartOfAccountSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'fend',
            'email' => 'fend@jour.com',
            'password' => 'user123'
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Warehouse::create([
            'code' => 'HQT',
            'name' => 'HEADQUARTER',
            'address' => 'Bandung, Jawa Barat, ID, 40375',
            'chart_of_account_id' => 1
        ]);

        Role::create([
            'user_id' => 1,
            'warehouse_id' => 1,
            'role' => 'Administrator'
        ]);

        Role::create([
            'user_id' => 2,
            'warehouse_id' => 1,
            'role' => 'Administrator'
        ]);

        Contact::create([
            'name' => 'General',
            'type' => 'Customer',
            'Description' => 'General Customer',
        ]);

        $this->call([
            AccountSeeder::class,
            ChartOfAccountSeeder::class,
            ProductSeeder::class
        ]);

        // ProductFactory::new()->count(10)->create();
        ContactFactory::new()->count(10)->create();
    }
}
