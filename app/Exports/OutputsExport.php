<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class OutputsExport
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function export(string $filePath)
    {
        $writer = SimpleExcelWriter::create($filePath, 'xlsx')
            ->noHeaderRow(); // Agrega esta línea para evitar encabezado automático

             // Rango de fechas
        $writer->addRow([
            'Rango de fechas: ' . request()->input('start_date') . ' - ' . request()->input('end_date')
        ]);

        // Agregar encabezados
        $writer->addRow([
            'ID', 'Proyecto', 'Producto', 'Responsable', 'Cantidad', 'Ubicación', 'Descripción', 'Fecha de Creación','Nombre Cuenta'
        ]);

        // Agregar datos
        foreach ($this->data as $row) {
            $writer->addRow([
            $row['id'] ?? '',
            $row['project']['name'] ?? '',
            $row['product']['name'] ?? '',
            $row['responsible'] ?? '',
            number_format($row['quantity'] ?? 0, 0, '.', ','),
            $row['product']['location'] ?? '',
            $row['description'] ?? 'N/A',
            \Carbon\Carbon::parse($row['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s'),
            $row['user']['name'] ?? 'N/A'
            ]);
        }
        

        return $filePath;
    }
}
