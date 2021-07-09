<?php

namespace App\Exports;

use App\Models\Meal;
use Maatwebsite\Excel\Concerns\FromCollection;

class MealsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Meal::all();
    }
}
