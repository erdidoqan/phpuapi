<?php

use App\Subscription;
use App\SubscriptionType;
use App\User;
use Illuminate\Database\Seeder;
use App\SubscriptionPayment;
use App\CreditCard;

class SubscriptionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionType::create([
            'name' => 'Aylık',
            'slug' => \Illuminate\Support\Str::slug('Aylık'),
            'description' => 'Request your lesson today. Teachers will answer your request during the day.',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M0,0H32V32H0Z" fill="none"></path> <path d="M9,3.333A5.667,5.667,0,0,1,14.667,9v5.667H9A5.667,5.667,0,1,1,9,3.333Zm0,14h5.667V23A5.667,5.667,0,1,1,9,17.333Zm14-14a5.667,5.667,0,1,1,0,11.333H17.333V9A5.667,5.667,0,0,1,23,3.333Zm-5.667,14H23A5.667,5.667,0,1,1,17.333,23Z" fill="#fff"></path></svg>',
            'price' => (float) 49.00,
            'months' => (int) 1
        ]);

        SubscriptionType::create([
            'name' => '3 Aylık',
            'slug' => \Illuminate\Support\Str::slug('3 Aylık'),
            'description' => 'Request your lesson today. Teachers will answer your request during the day.',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M0,0H32V32H0Z" fill="none"></path> <path d="M16,24.347,6.6,29.611,8.7,19.04.783,11.723l10.7-1.269L16,.667l4.515,9.787,10.7,1.269L23.3,19.04l2.1,10.571Z" fill="#fff"></path></svg>',
            'price' => (float) 99.00,
            'months' => (int) 3
        ]);

        SubscriptionType::create([
            'name' => 'Yıllık',
            'slug' => \Illuminate\Support\Str::slug('Yıllık'),
            'description' => 'Request your lesson today. Teachers will answer your request during the day.',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M0,0H32V32H0Z" fill="none"></path> <path d="M28.239,17.059l.017.019L16,29.333,3.744,17.077l.017-.019A8.667,8.667,0,0,1,16,4.853,8.667,8.667,0,0,1,28.239,17.059Z" fill="#fff"></path></svg>',
            'price' => (float) 249.00,
            'months' => (int) 12
        ]);

        SubscriptionType::create([
            'name' => 'Ömür Boyu',
            'slug' => \Illuminate\Support\Str::slug('Ömür Boyu'),
            'description' => 'Request your lesson today. Teachers will answer your request during the day.',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M0,0H32V32H0Z" fill="none"></path> <path d="M2.667,12h4V28h-4a1.333,1.333,0,0,1-1.333-1.333V13.333A1.333,1.333,0,0,1,2.667,12Zm7.057-1.724,8.533-8.533a.667.667,0,0,1,.872-.063l1.137.853A2,2,0,0,1,21,4.627l-1.537,6.04H28a2.667,2.667,0,0,1,2.667,2.667v2.805a2.667,2.667,0,0,1-.2,1.016L26.34,27.175A1.333,1.333,0,0,1,25.107,28H10.667a1.333,1.333,0,0,1-1.333-1.333V11.219a1.333,1.333,0,0,1,.39-.942Z" fill="#fff"></path></svg>',
            'price' => (float) 399.00,
            'months' => 240
        ]);
    }
}
