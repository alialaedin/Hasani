<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function sms(Request $request): RedirectResponse
	{
		$customer = Customer::findOrFail($request->input('customer_id'));
		$customer->sendCustomerSms();
		// SendTrackingCodeToOneCustomerJob::dispatch($customer)->delay(now()->addSeconds(10));

		return redirect()->back();
	}

	public function destroy(Customer $customer)
	{
		$customer->delete();

		return redirect()->back();
	}
}
