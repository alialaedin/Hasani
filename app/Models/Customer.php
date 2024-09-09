<?php

namespace App\Models;

use App\Classes\Sms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
	protected $fillable = [
		'tracking_code',
		'mobile',
		'file_id',
		'is_send'
	];

	public function file(): BelongsTo
	{
		return $this->belongsTo(File::class);
	}

	public function scopeSent($query)
	{
		return $query->where('is_send', 1);
	}

	public function sendCustomerSms()
	{
		try {
			$output = Sms::send_tracking_code(env('SMS_PATTERN'), $this->tracking_code, $this->mobile);

			if ($output['status'] == 200) {
				$this->update([
					'is_send' => 1
				]);
			}

			$this->file->updateIsSend();

			flash()->success('پیامک فرستاده شد');

		} catch (\Exception $e) {
			flash()->error($e->getMessage());
		}
	}

	public function scopeFilters($query)
  {
    return $query
      ->when(request('mobile'), fn ($q) => $q->where('mobile', request('mobile')))
      ->when(request('tracking_code'), fn ($q) => $q->where('tracking_code', request('tracking_code')))
			->when(!is_null(request('is_send')), function ($q) {
				if (request('is_send') != 'all') {
					$q->where('is_send', request('is_send'));
				}
			});
  }
}
