<?php

namespace App\Http\Requests\Admin;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File as RulesFile;

class FileStoreRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			File::FILE_NAME => [
				RulesFile::types(File::ACCEPT_MIME),
				'between:' . File::MIN_FILE_SIZE .','. File::MAX_FILE_SIZE,
				'file'
			]
		];
	}
}
