<?php

namespace App\Livewire;

use App\Models\InspeccionExtra;
use App\Models\InspeccionMaestra;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteComisiones extends Component
{
    public $fecha_inicio;
    public $fecha_fin;
    public $search = '';

    public function mount()
    {
        // Por defecto, mostrar el mes actual
        $this->fecha_inicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $rango = [$this->fecha_inicio, $this->fecha_fin];

        // 1. MAESTRAS
        $maestras = InspeccionMaestra::whereBetween('fecha_inspeccion', $rango)
            ->where('comision_monto', '>', 0)
            ->get()
            ->map(fn($i) => [
                'id'            => $i->id,
                'fecha'         => $i->fecha_inspeccion,
                'placa'         => $i->placa_vehiculo,
                'servicio'      => $i->tipo_atencion,
                'categoria'     => $i->categoria_vehiculo,
                'monto'         => (float)$i->monto_total,
                'comision'      => (float)$i->comision_monto,
                'formato'       => "{$i->serie_certificado}-{$i->correlativo_certificado}",
                'metodo_pago'   => $i->metodo_pago ?? 'SN',
                'comprobante'   => $i->nro_comprobante ?? 'NN',
                'activo'        => is_null($i->fecha_anulacion) && $i->estado_inspeccion !== 'Anulada',
                'clase_fila'    => $this->getClaseMaestra($i),
                'badge'         => $i->es_reinspeccion === 'S' ? 'REINSP.' : ($i->estado_inspeccion === 'Anulada' ? 'ANULADA' : null)
            ]);

        // 2. EXTRAS
        $extras = InspeccionExtra::with(['vehiculo', 'tipoServicio'])
            ->whereBetween('fecha_inspeccion', $rango)
            ->where('comision_monto', '>', 0)
            ->get()
            ->map(fn($i) => [
                'id'            => $i->id,
                'fecha'         => $i->fecha_inspeccion,
                'placa'         => $i->vehiculo->placa ?? 'S/P',
                'servicio'      => $i->tipoServicio->nombre_servicio ?? 'SERVICIO EXTRA',
                'categoria'     => $i->vehiculo->categoria ?? 'NE',
                'monto'         => (float)$i->monto_total,
                'comision'      => (float)$i->comision_monto,
                'formato'       => "CERT-{$i->numero_certificado}",
                'metodo_pago'   => $i->metodo_pago ?? 'SN',
                'comprobante'   => $i->nro_comprobante ?? 'NN',
                'activo'        => $i->estado !== 'Anulada',
                'clase_fila'    => $i->estado === 'Anulada' ? 'bg-red-50 opacity-60 italic' : '',
                'badge'         => $i->estado === 'Anulada' ? 'ANULADA' : null
            ]);

        // 3. Unificar y Filtrar por búsqueda
        $inspComision = $maestras->concat($extras)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($item) {
                    return str_contains(strtolower($item['placa']), strtolower($this->search)) ||
                           str_contains(strtolower($item['formato']), strtolower($this->search));
                });
            })
            ->sortByDesc('fecha');

        // 4. Estadísticas Pro
        $validas = $inspComision->where('activo', true);
        $stats = [
            'total_comisiones' => $validas->sum('comision'),
            'total_monto'      => $validas->sum('monto'),
            'cantidad'         => $validas->count(),
            'promedio'         => $validas->count() > 0 ? $validas->sum('comision') / $validas->count() : 0,
            'anuladas'         => $inspComision->where('activo', false)->count(),
        ];

        return view('livewire.reporte-comisiones', [
            'inspComision' => $inspComision,
            'stats' => $stats
        ]);
    }

    private function getClaseMaestra($i) {
        if (!is_null($i->fecha_anulacion) || $i->estado_inspeccion === 'Anulada') return 'bg-red-50 italic text-gray-400';
        if ($i->resultado_estado === 'D') return 'bg-blue-50 text-blue-700';
        return '';
    }   
}
