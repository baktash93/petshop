<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\OrderStatusSeeder;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OrderStatusSeeder::class,
        ]);
        User::factory()
            ->count(10)
            ->has(Order::factory()->count(rand(5, 10)))
            ->create();
    }
}
