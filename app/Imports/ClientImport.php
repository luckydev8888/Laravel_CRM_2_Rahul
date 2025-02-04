<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $batchData = [];
        $existingIds = Client::pluck('id')->toArray(); // Get existing IDs

        foreach ($rows as $row) {
            if (!isset($row['id']) || empty($row['id'])) {
                continue; // Skip rows without an ID
            }

            if (in_array($row['id'], $existingIds)) {
                continue; // Skip if ID already exists
            }

            $date = $this->convertExcelDate($row['date']);
            $samples = $this->convertExcelDate($row['samples']);
            $display = ($row['display'] === 'Yes') ? 1 : 0;
            $prices = ($row['prices'] === 'Yes') ? 1 : 0;

            // // Validate phone numbers before inserting
            // $validator = Validator::make($row->toArray(), [
            //     'tel1' => 'nullable|unique:clients,tel1',
            //     'tel2' => 'nullable|unique:clients,tel2',
            // ]);

            // if ($validator->fails()) {
            //     continue; // Skip invalid data
            // }

            $batchData[] = [
                'id' => $row['id'],
                'assigned' => $row['assigned'],
                'date' => $date,
                'status' => $row['status'],
                'score' => $row['score'],
                'company' => $row['company'],
                'contact' => $row['contact'],
                'tel1' => $row['tel1'],
                'tel2' => $row['tel2'],
                'town' => $row['town'],
                'area' => $row['area'],
                'samples' => $samples,
                'display' => $display,
                'prices' => $prices,
                'brand' => $row['brand'],
                'comments' => $row['comments'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Insert data in chunks of 500 rows
            if (count($batchData) >= 500) {
                DB::table('clients')->insert($batchData);
                $batchData = []; // Reset batch
            }
        }

        // Insert remaining data
        if (!empty($batchData)) {
            DB::table('clients')->insert($batchData);
        }
    }

    private function convertExcelDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::createFromDate(1899, 12, 30)->addDays($value)->format('Y-m-d');
        }
        return $value;
    }
}
