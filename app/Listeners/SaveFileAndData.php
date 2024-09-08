<?php

namespace App\Listeners;

use App\Events\UploadFile;
use App\Imports\CustomersImport;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SaveFileAndData
{
	private array $attributes;

	public function handle(UploadFile $event): void
	{
		$this->updateAttributs($event->file);
		$file = File::query()->create($this->attributes);
		Excel::import(new CustomersImport($file->id), $event->file);
	}

	private function updateAttributs($file): void
	{
		$this->attributes = [
			'name' =>  Str::remove(File::FILE_EXTENSION, $file->getClientOriginalName()),
			'path' => Storage::disk(File::FILE_DISK)->put(File::FILE_DIRECTORY, $file),
			'is_send' => 0
		];
	}
}
