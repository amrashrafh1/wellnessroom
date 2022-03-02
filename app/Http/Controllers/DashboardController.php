<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Plans;
use Stripe\Plan;

class DashboardController extends Controller
{

    public function __invoke()
    {
        // check if the visitor is client
        if(auth()->user()->roles->first()->name == 'client') {
            $plans = Plans::get()->map(function($plan) {
                return $plan->only(['title', 'stripe_id', 'identifier','id']); 
            });
            
            $data = [
                'intent' => auth()->user()->createSetupIntent()
            ];
            $flash =  [
                    'success' => session()->get('success'),
                    'error'   => session()->get('error'),
                ];
            
            return Inertia::render('Client/Subscribe',['plans' => $plans, 'data' => $data, 'flash' => $flash]);
        }
        // if not client (admin | user) render the dashboard 
        else {
            return Inertia::render('Dashboard');

        }
    }

}
