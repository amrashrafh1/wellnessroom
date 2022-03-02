<?php

namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
Use App\Models\Plans;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
class SubscriptionController extends Controller
{
    public function __invoke(Request $request)
    {

            /* $this->validate($request, [
                'stripeToken' => 'required',
                'sub_type'    => 'required',
                '_method'    => 'required|string'
            ]);
            $plan = Plans::where('identifier', $request->sub_type)
                ->orWhere('identifier', 'basic')
                ->first();
            
            $request->user()->newSubscription('default', $plan->stripe_id)->create($request->stripeToken);
     */
            
            return Redirect::route('dashboard')->with('success', 'Subscription is completed.');
            
    }

}
