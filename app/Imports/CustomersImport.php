<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomersImport implements ToModel
{
	public function __construct(public int $fileId){}

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		return new Customer([
			'mobile' => $row[0],
			'tracking_code' => $row[1],
			'file_id' => $this->fileId
		]);
	}
}
