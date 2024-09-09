<?php

namespace App\Models;

use App\Classes\Helpers;
use App\Events\UploadFile;
use App\Jobs\SendTrackingCodeToCustomersJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class File extends Model
{
  private const FAILED_MESSAGE = 'خطا در آپلود فایل';
  private const SUCCESS_MESSAGE = 'فایل با موفقیت بارگذاری شد';
  private const FILE_NOT_FOUND_MESSAGE = 'فایل فرستاده شده معتبر نمی باشد';
  public const FILE_NAME = 'excel_file';
  public const FILE_DIRECTORY = 'files/';
  public const FILE_DISK = 'local';
  public const MAX_FILE_SIZE = '10240';
  public const MIN_FILE_SIZE = '1';
  public const ACCEPT_MIME = 'xlsx';
  public const FILE_EXTENSION = '.xlsx';

  private const STATUS_SOME_HAVE_BEEN_SENT = 'some_have_been_sent';
  private const STATUS_ALL_SENT = 'all_sent';
  private const STATUS_NOT_ALL_SENT = 'not_all_sent';
  private const STATUS_DATA_NOT_FOUND = 'data_not_found';

  private const STATUSES = [
    self::STATUS_SOME_HAVE_BEEN_SENT => [
      'name' => 'برخی ارسال شده',
      'css_class' => 'badge badge-info-light',
    ],
    self::STATUS_ALL_SENT => [
      'name' => 'همه ارسال نشده',
      'css_class' => 'badge badge-success-light',
    ],
    self::STATUS_NOT_ALL_SENT => [
      'name' => 'هیچکدام ارسال نشده',
      'css_class' => 'badge badge-danger-light',
    ],
    self::STATUS_DATA_NOT_FOUND => [
      'name' => 'داده ای یافت نشد',
      'css_class' => 'badge badge-warning-light',
    ]
  ];


  protected $fillable = ['name', 'path', 'is_send'];

  public static function booted(): void
  {
    static::created(fn() => flash()->success(self::SUCCESS_MESSAGE));
  }

  public function getCountCustomersSentAttribute(): int
  {
    return $this->customers()->sent()->count();
  }

  public static function generateFileName(File $file)
  {
    $newFilePath = Str::remove(self::FILE_DIRECTORY, $file->path);
    $explodedFilePathArr = explode('.', $newFilePath);
    $newFileName = $file->name . '.' . $explodedFilePathArr[1];

    return $newFileName;
  }

  public static function uploadFile($request)
  {
    $isValid = static::validateFile($request);
    if ($isValid) {
      Event::dispatch(new UploadFile($request->file(self::FILE_NAME)));
    } else {
      self::throwValidationError();
    }
  }

  private static function validateFile($request): bool
  {
    if ($request->hasFile(self::FILE_NAME) && $request->file(self::FILE_NAME)->isValid()) {
      return true;
    } else {
      return false;
    }
  }

  private static function throwValidationError(): ValidationException
  {
    return ValidationException::withMessages([
      self::FILE_NAME => self::FAILED_MESSAGE
    ])->errorBag('default');
  }

  public function customers(): HasMany
  {
    return $this->hasMany(Customer::class);
  }

  public function scopeFilters($query)
  {
    return $query
      ->when(request('name'), fn ($q) => $q->where('name', 'LIKE', '%' . request('name') . '%'))
      ->when(request('start_date'), fn ($q) => $q->whereDate('created_at', '>=', request('start_date')))
      ->when(request('end_date'), fn ($q) => $q->whereDate('created_at', '<=', request('end_date')));
  }

  public function checkAllDataIsSend(): array
  {
    $isSend = false;
    $isNotSend = false;

    if ($this->customers->isEmpty()) {
      return self::STATUSES[self::STATUS_DATA_NOT_FOUND];
    }

    foreach ($this->customers as $customer) {
      if ($customer->is_send) {
        $isSend = true;
      } else {
        $isNotSend = true;
      }

      if ($isSend && $isNotSend) {
        return self::STATUSES[self::STATUS_SOME_HAVE_BEEN_SENT];
      }
    }

    if ($isSend) {
      return self::STATUSES[self::STATUS_ALL_SENT];
    } else {
      return self::STATUSES[self::STATUS_NOT_ALL_SENT];
    }
  }

  public static function sendSms(File $file)
  {
    if (!$file) {
      throw Helpers::makeWebValidationException(self::FILE_NOT_FOUND_MESSAGE);
		}
		try {
			SendTrackingCodeToCustomersJob::dispatch($file)->delay(now()->addSeconds(10));
			flash()->success('پیامک ها فرستاده شد');
		} catch (\Exception $e) {
			flash()->error($e->getMessage());
		}
  }
}
