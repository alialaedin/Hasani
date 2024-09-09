<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileStoreRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
	public function upload(FileStoreRequest $request)
	{
		File::uploadFile($request);

		return to_route('dashboard');
	}

	public function download(File $file)
	{
		$fileName = File::generateFileName($file);

		return Excel::download(new CustomersExport($file), $fileName);
	}

	public function sms(Request $request)
	{
		$file = File::findOrFail($request->input('file_id'));
		File::sendSms($file);

		return redirect()->back();
	}
}
