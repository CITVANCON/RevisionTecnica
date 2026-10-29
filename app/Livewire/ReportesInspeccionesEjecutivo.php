<?php

namespace App\Livewire;

use App\Models\CierreDiario;
use App\Models\Gasto;
use App\Models\InspeccionExtra;
use App\Models\InspeccionMaestra;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportesInspeccionesEjecutivo extends Component
{
    // Filtro único
    public $fecha;
    // Totales de Inspecciones (Ingresos)
    public $total_monto = 0;
    public $total_inspecciones = 0;
    public $total_comisiones = 0;
    // Totales de Gastos (Egresos)
    public $total_gastos = 0;

    // Propiedades para el Modal de Auditoría
    public $mostrarModalAuditoria = false;
    public $formAuditoria = [
        'fecha' => '',
        'efectivo_esperado' => 0,
        'efectivo_real' => 0,
        
        'pos_esperado' => 0,
        'pos_real' => 0,
        'comision_pos' => 0,
        'monto_neto_pos' => 0,

        'observacion' => '',
        'estado' => 'pendiente'
    ];

    public function mount()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
    }


    public function updatedFormAuditoriaPosReal($value)
    {
        // Cálculo sugerido (Ejm: 3.5% promedio de comisión Izipay/Niubiz)
        // El usuario podrá sobrescribir la comisión_pos si el banco cobró distinto
        //$this->formAuditoria['comision_pos'] = round($value * 0.035, 2);
        $this->calcularNeto();
    }
    public function updatedFormAuditoriaComisionPos()
    {
        $this->calcularNeto();
    }
    private function calcularNeto()
    {
        // El truco es forzar (float) y tratar valores vacíos como 0
        $posReal = is_numeric($this->formAuditoria['pos_real']) ? (float)$this->formAuditoria['pos_real'] : 0;

        $comision = is_numeric($this->formAuditoria['comision_pos']) ? (float)$this->formAuditoria['comision_pos'] : 0;

        $this->formAuditoria['monto_neto_pos'] = round($posReal - $comision, 2);
    }

    public function abrirAuditoria($efectivo_sistema, $pos_sistema)
    {
        // Al ser 'fecha' PK, find() es correcto y rápido.
        $cierre = CierreDiario::find($this->fecha);

        if ($cierre) {
            $this->formAuditoria = $cierre->toArray();
            $this->formAuditoria['efectivo_esperado'] = (float)$cierre->efectivo_esperado;
            $this->formAuditoria['pos_esperado'] = (float)$cierre->pos_esperado;
        } else {
            // Inicializamos el formulario con los valores calculados por el sistema
            $this->formAuditoria = [
                'fecha'             => $this->fecha,
                'efectivo_esperado' => (float)$efectivo_sistema,
                'efectivo_real'     => (float)$efectivo_sistema,
                'pos_esperado'      => (float)$pos_sistema,
                'pos_real'          => (float)$pos_sistema,
                //'comision_pos'      => round($pos_sistema * 0.035, 2), // Sugerido inicial
                //'monto_neto_pos'    => $pos_sistema - round($pos_sistema * 0.035, 2),
                'comision_pos'      => 0,
                'monto_neto_pos'    => (float)$pos_sistema,
                'observacion'       => '',
                'estado'            => 'pendiente'
            ];
        }
        
        $this->mostrarModalAuditoria = true;
    }
    public function guardarAuditoria()
    {
        // Forzamos el recalculo por seguridad
        $this->calcularNeto();

        $this->validate([
            'formAuditoria.efectivo_real' => 'required|numeric|min:0',
            'formAuditoria.pos_real'      => 'required|numeric|min:0',
            'formAuditoria.comision_pos'  => 'required|numeric|min:0',
            'formAuditoria.estado'        => 'required|in:pendiente,cuadrado,observado',
            'formAuditoria.observacion'   => 'nullable|string|max:1000',
        ]);

        CierreDiario::updateOrCreate(
            ['fecha' => $this->formAuditoria['fecha']],
            [
                'efectivo_esperado' => $this->formAuditoria['efectivo_esperado'],
                'efectivo_real'     => $this->formAuditoria['efectivo_real'],
                'pos_esperado'      => $this->formAuditoria['pos_esperado'],
                'pos_real'          => $this->formAuditoria['pos_real'],
                'comision_pos'      => $this->formAuditoria['comision_pos'],
                'monto_neto_pos'    => $this->formAuditoria['monto_neto_pos'],
                'observacion'       => $this->formAuditoria['observacion'],
                'estado'            => $this->formAuditoria['estado'],
                'auditado_por'      => Auth::id(),
            ]
        );

        $this->mostrarModalAuditoria = false;
        $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: "La auditoría del día se ha registrado correctamente.", icono: "success");
    }    
    
    public function render()
    {
        // 1. Definimos una colección vacía para consolidar todo
        $ingresosConsolidados = collect();

        // 2. Procesamos cada fuente de ingresos de forma independiente
        
        // --- FUENTE: INSPECCIONES MAESTRAS ---
        $maestras = InspeccionMaestra::with(['pagos'])
            ->whereDate('fecha_inspeccion', $this->fecha)->get();
        $ingresosConsolidados = $ingresosConsolidados->concat($this->transformarMaestras($maestras));

        // --- FUENTE: INSPECCIONES EXTRAS ---
        $extras = InspeccionExtra::with(['vehiculo', 'tipoServicio', 'pagos'])->whereDate('fecha_inspeccion', $this->fecha)->get();
        $ingresosConsolidados = $ingresosConsolidados->concat($this->transformarExtras($extras));

        // --- ¿NUEVA FUENTE FUTURA? (Ejemplo: Ventas) ---
        // $ventas = Venta::whereDate('fecha', $this->fecha)->get();
        // $ingresosConsolidados = $ingresosConsolidados->concat($this->transformarVentas($ventas));

        // 3. Cálculos Normalizados
        $inspeccionesActivas = $ingresosConsolidados->where('activo', true);
        
        $this->total_monto = $inspeccionesActivas->sum('monto');
        $this->total_comisiones = $inspeccionesActivas->sum('comision');
        $this->total_inspecciones = $inspeccionesActivas->count();

        // Franja de contadores por estado para el resumen de la tabla
        $statsResumen = [
            'aprobados'      => $inspeccionesActivas->where('resultado', 'A')->count(),
            'desaprobados'   => $inspeccionesActivas->where('resultado', 'D')->count(),
            'anulados'       => $ingresosConsolidados->where('activo', false)->count(),
            'reinspecciones' => $inspeccionesActivas->where('reinspeccion', true)->count(),
            'sin_resultado'  => $inspeccionesActivas->filter(fn ($i) => !in_array($i['resultado'], ['A', 'D'], true))->count(),
        ];

        // --- NUEVA LÓGICA DE RESUMEN DE PAGOS ---
        // Extraemos todos los pagos individuales de todas las inspecciones activas
        $todosLosPagos = $inspeccionesActivas->pluck('detalles_pago')->flatten(1);

        // Resumen de Pagos, Gastos y Auditoría (Se mantiene igual sobre la colección única)
        /*$resumenPagos = $todosLosPagos->groupBy('metodo_pago')
            ->map(fn($group) => [
                'cantidad' => $group->count(),
                'total' => $group->sum('monto')
            ]);*/
        // Agrupamos usando 'metodo' que es la llave que definiste en transformarMaestras/Extras
        $resumenPagos = $todosLosPagos->groupBy('metodo')
            ->map(fn($group) => [
                'cantidad' => $group->count(),
                'total' => $group->sum('monto')
            ]);

        $gastos = Gasto::diarios($this->fecha)->get();
        $this->total_gastos = $gastos->sum('monto');

        // Efectivo esperado = Suma de pagos en efectivo - gastos
        $ingresosEfectivo = $resumenPagos['EFECTIVO']['total'] ?? 0;
        $totalTarjetasYOtros = $todosLosPagos->whereIn('metodo', ['YAPE', 'VISA', 'TRANSFERENCIA', 'MASTERCARD', 'PLIN'])->sum('monto');

        return view('livewire.reportes-inspecciones-ejecutivo', [
            'inspecciones' => $ingresosConsolidados->sortBy('id'),
            'validasCount' => $inspeccionesActivas->count(),
            'resumenPagos' => $resumenPagos,
            'gastos' => $gastos,
            'efectivo_neto' => $ingresosEfectivo - $this->total_gastos,
            'total_tarjetas' => $totalTarjetasYOtros,
            'cierreActual' => CierreDiario::find($this->fecha),
            'statsResumen' => $statsResumen
        ]);
    }

    /**
     * Adaptadores específicos por cada modelo
     * Esto permite que si un modelo cambia de nombre de columna, solo tocas su función.
     */

    private function transformarMaestras($coleccion)
    {
        return $coleccion->map(fn($i) => [
            'id'            => $i->id,
            'fecha'         => $i->fecha_inspeccion,
            'placa'         => $i->placa_vehiculo,
            'servicio'      => $i->tipo_atencion,
            'categoria'     => $i->categoria_vehiculo,
            'monto'         => (float)$i->monto_total,
            'comision'      => (float)$i->comision_monto,
            'formato'       => "{$i->serie_certificado}-{$i->correlativo_certificado}",
            // Concatenamos métodos para la vista de tabla
            //'metodo_pago'   => $i->metodo_pago ?? 'SN',
            'metodo_pago'    => $i->pagos->pluck('metodo_pago')->implode(' + ') ?: 'SN',
            'detalles_pago'  => $i->pagos->map(fn($p) => ['metodo' => $p->metodo_pago, 'monto' => $p->monto]),
            'comprobante'   => $i->nro_comprobante ?? 'NN',
            'activo'        => is_null($i->fecha_anulacion) && $i->estado_inspeccion !== 'Anulada',
            'clase_fila'    => $this->getClaseMaestra($i),
            'badge'         => $i->es_reinspeccion === 'S' ? 'REINSP.' : ($i->resultado_estado === 'D' ? 'DESAPROB.' : null),
            'resultado'     => $i->resultado_estado,   // 'A', 'D' o null
            'reinspeccion'  => $i->es_reinspeccion === 'S'
        ]);
    }

    private function transformarExtras($coleccion)
    {
        return $coleccion->map(fn($i) => [
            'id'            => $i->id,
            'fecha'         => $i->fecha_inspeccion,
            'placa'         => $i->vehiculo->placa ?? 'S/P',
            'servicio'      => $i->tipoServicio->nombre_servicio ?? 'SERVICIO EXTRA',
            'categoria'     => $i->vehiculo->categoria ?? '-',
            'monto'         => (float)$i->monto_total,
            'comision'      => (float)$i->comision_monto,
            'formato'       => "CERT-{$i->numero_certificado}",
            //'metodo_pago'   => $i->metodo_pago ?? 'SN',
            'metodo_pago'    => $i->pagos->pluck('metodo_pago')->implode(' + ') ?: 'SN',
            'detalles_pago'  => $i->pagos->map(fn($p) => ['metodo' => $p->metodo_pago, 'monto' => $p->monto]),

            'comprobante'   => $i->nro_comprobante ?? 'NN',
            'activo'        => $i->estado !== 'Anulada',
            'clase_fila'    => $i->estado === 'Anulada' ? 'bg-red-50 italic text-gray-400' : '',
            'badge'         => null,
            'resultado'     => null,
            'reinspeccion'  => false
        ]);
    }

    private function getClaseMaestra($i) {
        if (!is_null($i->fecha_anulacion) || $i->estado_inspeccion === 'Anulada') return 'bg-red-100 italic text-gray-400';
        if ($i->resultado_estado === 'D') return 'bg-blue-100 text-blue-700';
        return '';
    }
}