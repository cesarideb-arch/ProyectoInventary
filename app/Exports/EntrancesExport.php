<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class EntrancesExport
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
            'ID', 'Proyecto', 'Producto', 'Responsable', 'Cantidad', 'Precio', 'Gastado', 'Ubicación', 'Descripción', 'Folio', 'Fecha de Creación', 'Nombre Cuenta'
        ]);

        // Agregar datos
        foreach ($this->data as $row) {
            $writer->addRow([
                $row['id'] ?? '',
                $row['project']['name'] ?? '',
                $row['product']['name'] ?? '',
                $row['responsible'] ?? '',
                number_format($row['quantity'] ?? 'N/A', 0, '.', ','),
                number_format($row['price'] ?? 'N/A', 2, '.', ','),
                number_format(($row['price'] ?? 0) * ($row['quantity'] ?? 0), 2, '.', ','),
                $row['product']['location'] ?? '',
                $row['description'] ?? 'N/A',
                $row['folio'] ?? '',
                \Carbon\Carbon::parse($row['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s'),
                $row['user']['name'] ?? 'N/A'
            ]);
        }

        return $filePath;
    }
}
