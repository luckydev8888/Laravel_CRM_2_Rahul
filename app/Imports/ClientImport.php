<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $batchData = [];
        $existingClients = Client::pluck('id')->toArray(); // Get existing IDs

        foreach ($rows as $row) {
            if (!isset($row['id']) || empty($row['id'])) {
                continue; // Skip rows without an ID
            }

            // Convert Excel serial numbers to valid date format
            $date = $this->convertExcelDate($row['date']);
            $samples = $this->convertExcelDate($row['samples']);
            $display = ($row['display'] === 'Yes') ? 1 : 0;
            $prices = ($row['prices'] === 'Yes') ? 1 : 0;

            // Prepare data for insert or update
            $clientData = [
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
                'updated_at' => now() // Update timestamp
            ];

            if (in_array($row['id'], $existingClients)) {
                // ✅ If the ID exists, UPDATE the record
                Client::where('id', $row['id'])->update($clientData);
            } else {
                // ✅ If the ID does not exist, INSERT the record
                $clientData['created_at'] = now();
                $batchData[] = $clientData;
            }

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

    // Convert Excel date format (serial numbers) to MySQL date
    private function convertExcelDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::createFromDate(1899, 12, 30)->addDays($value)->format('Y-m-d');
        }
        return $value;
    }
}
