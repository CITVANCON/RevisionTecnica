<?php

namespace App\Exports;

use App\Models\InspeccionMaestra;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InspeccionesVencidasExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filtro;
    protected $search;
    protected $fecha_inicio;
    protected $fecha_fin;

    public function __construct($filtro, $search, $fecha_inicio, $fecha_fin)
    {
        $this->filtro = $filtro;
        $this->search = $search;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }

    public function collection()
    {
        $query = InspeccionMaestra::query()
            ->whereNull('fecha_anulacion')
            ->where('resultado_estado', 'A');

        // SEARCH
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('placa_vehiculo', 'like', "%{$this->search}%")
                    ->orWhere('propietario_nombre', 'like', "%{$this->search}%")
                    ->orWhere('propietario_documento', 'like', "%{$this->search}%");
            });
        }

        // FECHAS
        if ($this->fecha_inicio && $this->fecha_fin) {
            $query->whereBetween('fecha_vencimiento', [
                $this->fecha_inicio,
                $this->fecha_fin
            ]);
        }

        // FILTROS
        if (!$this->fecha_inicio && !$this->fecha_fin) {

            if ($this->filtro === 'vencidos') {
                $query->whereDate('fecha_vencimiento', '<', now());
            }

            if ($this->filtro === '7dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(7)
                ]);
            }

            if ($this->filtro === '15dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(15)
                ]);
            }

            if ($this->filtro === '30dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(30)
                ]);
            }
        }

        return $query->orderBy('fecha_vencimiento', 'asc')->get()->map(function ($item) {

            $dias = (int) Carbon::parse($item->fecha_vencimiento)->diffInDays(now(), false);

            return [
                'Placa' => $item->placa_vehiculo,
                'Atención' => $item->tipo_atencion,
                'Categoría' => $item->categoria_vehiculo,
                'Cliente' => $item->propietario_nombre,
                'Celular' => $item->propietario_celular,
                'Vencimiento' => Carbon::parse($item->fecha_vencimiento)->format('d/m/Y'),
                'Días' => $dias,
                'Estado' => $dias > 0 ? 'VENCIDO' : 'VIGENTE',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Placa',
            'Atención',
            'Categoría',
            'Cliente',
            'Celular',
            'Vencimiento',
            'Días',
            'Estado',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // 📌 Encabezado en negrita
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // 📌 Bordes a toda la tabla
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ]);
            },
        ];
    }
}
