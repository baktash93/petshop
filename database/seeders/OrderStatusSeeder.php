<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('order_statuses')->insert([
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'title' => 'open'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'title' => 'pending payment'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'title' => 'paid'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'title' => 'shipped'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'title' => 'cancelled'
            ],
        ]);
    }
}
