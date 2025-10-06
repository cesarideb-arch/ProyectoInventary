<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class SuppliersExport
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function export(string $filePath)
    {
        $writer = SimpleExcelWriter::create($filePath, 'xlsx')
            ->noHeaderRow();

        // Agregar encabezados
        $writer->addRow([
         'Empresa', 'Teléfono', 'Email', 'Dirección'
        ]);

        // Agregar datos
        foreach ($this->data as $row) {
            $writer->addRow([
                $row['company'] ?? '',
                $row['phone'] ?? '',
                $row['email'] ?? 'N/A',
                $row['address'] ?? 'N/A'
            ]);
        }

        return $filePath;
    }
}
