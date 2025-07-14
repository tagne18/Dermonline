<?php

namespace App\Exports;

use App\Models\Docteur;
use Maatwebsite\Excel\Concerns\FromCollection;

class DocteursExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Docteur::all();
    }
}
