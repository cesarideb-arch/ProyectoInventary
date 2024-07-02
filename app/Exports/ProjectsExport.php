<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class ProjectsExport
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
            'Nombre', 'Descripción', 'Nombre de la Empresa', 'RFC', 'Dirección', 'Teléfono', 'Email', 'Nombre del Cliente'
        ]);

        // Agregar datos
        foreach ($this->data as $row) {
            $writer->addRow([
                $row['name'] ?? '',
                $row['description'] ?? 'N/A',
                $row['company_name'] ?? '',
                $row['rfc'] ?? 'N/A',
                $row['address'] ?? '',
                $row['phone_number'] ?? '',
                $row['email'] ?? '',
                $row['client_name'] ?? ''
            ]);
        }

        return $filePath;
    }
}
