<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTrackingCodeToOneCustomerJob;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function sms(Request $request)
	{
		$customer = Customer::findOrFail($request->input('customer_id'));
		SendTrackingCodeToOneCustomerJob::dispatch($customer)->delay(now()->addSeconds(10));

		return redirect()->back();
	}
}
