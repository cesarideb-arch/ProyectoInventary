<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class LoansExport
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

        // Agregar encabezados
        $writer->addRow([
            'ID', 'Proyecto', 'Producto', 'Responsable', 'Cantidad', 'Ubicación', 'Observaciones', 'Fecha', 'Estado'
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
                $row['observations'] ?? 'N/A',
                \Carbon\Carbon::parse($row['status'] == 0 ? $row['updated_at'] : $row['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s'),
                $row['status'] == 0 ? 'Producto Regresado' : 'Producto Prestado',
            ]);
        }

        return $filePath;
    }
}
