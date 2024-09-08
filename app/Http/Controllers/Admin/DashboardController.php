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
}
