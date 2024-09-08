<?php

namespace App\Exports;

use App\Models\File;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomersExport implements FromCollection
{
	public function __construct(protected File $file){}

	public function collection(): Collection
	{
		$finalModels = [];
		
        $finalModels[] = [
            'شناسه',
            'موبایل',
            'کد رهگیری',
        ];

		foreach($this->file->customers as $customer) {
			$item = [];
            $item[] = $customer->id;
            $item[] = $customer->mobile;
            $item[] = $customer->tracking_code;
            $finalModels[] = $item;
		}

		$finalModels = collect([...$finalModels]);

		return $finalModels;
	}
}
