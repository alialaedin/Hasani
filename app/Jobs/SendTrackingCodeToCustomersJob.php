<?php

namespace App\Jobs;

use App\Classes\Sms;
use App\Models\Customer;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrackingCodeToCustomersJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 */
	public function __construct(public int $fileId)
	{
		//
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$file = File::query()->findOrFail($this->fileId);

		foreach ($file->customers as $customer) {
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
