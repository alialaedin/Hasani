<?php

namespace App\Jobs;

use App\Classes\Sms;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrackingCodeToOneCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Customer $customer)
	{
		//
	}

	public function handle(): void
	{
        $customer = $this->customer;
		if (!$customer->is_send) {
			$output = $this->sendSms($customer->tracking_code, $customer->mobile);
			$this->updateIsSend($output['status'], $customer);
		}
	}

	private function sendSms($trackingCode, $mobile): array
	{
		return Sms::send_tracking_code(
			env('SMS_PATTERN'),
			$trackingCode,
			$mobile
		);
	}

	private function updateIsSend(string|int $status, Customer $customer)
	{
		if ($status == 200) {
			$customer->is_send = 1;
			$customer->save();
		}
	}
}
