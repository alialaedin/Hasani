<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;

class DashboardController extends Controller
{
	public function index()
	{
		$files = File::query()
			->with('customers')
			->filters()
			->latest('id')
			->paginate();

		return view('admin.index', compact('files'));
	}

	public function show(File $file)
	{
		$customers = $file
			->customers()
			->orderByDesc('id')
			->filters()
			->paginate();

		return view('admin.show', compact('file', 'customers'));
	}
}
