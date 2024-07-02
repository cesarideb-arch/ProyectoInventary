<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;

class ProductsExport
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
            'Nombre', 'Descripción', 'Precio', 'Categoría', 'Proveedor', 'Ubicación', 'Imagen'
        ]);

        // Agregar datos
        foreach ($this->data as $row) {
            $writer->addRow([
                $row['name'] ?? '',
                $row['description'] ?? 'N/A',
                $row['price'] != 0 ? '$' . number_format($row['price'], 2, '.', ',') : 'N/A',
                $row['category']['name'] ?? '',
                $row['supplier']['company'] ?? 'N/A',
                $row['location'] ?? 'N/A',
                config('app.backend_api') . '/' . ($row['profile_image'] ?? 'ruta_por_defecto_de_la_imagen.jpg')
            ]);
        }

        return $filePath;
    }
}
