<?php

namespace App\Jobs;

use App\Classes\Sms\Sms;
use App\Models\Customer;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTrackingCodeToCustomersJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct(public File $file)
	{
		//
	}

	public function handle(): void
	{
		foreach ($this->file->customers->chunk(10) as $chunk) {
			foreach ($chunk as $customer) {
				if (!$customer->is_send) {
					try {
						$output = $this->sendSms($customer->tracking_code, $customer->mobile);
						if ($output && $output['status'] == 200) {
							$this->updateIsSend($customer);
						}
					} catch (\Exception $e) {
						flash()->error($e->getMessage());
						Log::error($e->getMessage());
					}
				}
			}
		}
		$this->file->updateIsSend();
	}

	private function sendSms($trackingCode, $mobile)
	{
		return Sms::pattern(env('SMS_PATTERN'))
			->data(['tracking_code' => $trackingCode])
			->to([$mobile])
			->send();
	}

	private function updateIsSend(Customer $customer)
	{
		$customer->is_send = 1;
		$customer->sended_at = now();
		$customer->save();
	}
}
