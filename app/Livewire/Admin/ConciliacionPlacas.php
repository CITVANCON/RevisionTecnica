<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConciliacionPlacas extends Component
{
    public $fecha;

    public function mount()
    {
        // Por defecto arranca con la fecha de hoy
        $this->fecha = date('Y-m-d');
    }

    /*public function render()
    {
        // 1. Obtener capturas de la cámara en esa fecha
        $capturas = DB::table('capturas_placas')
            ->whereDate('fecha_captura', $this->fecha)
            ->get();

        // 2. Obtener inspecciones maestras (sistema) en esa fecha usando placa_vehiculo
        $inspecciones = DB::table('inspecciones_maestras')
            ->whereDate('fecha_inspeccion', $this->fecha)
            ->get();

        // Normalizar placas para un cruce limpio (sin guiones y en mayúsculas)
        $placasCapturadas = $capturas->keyBy(function ($item) {
            return strtoupper(trim(str_replace('-', '', $item->numero_placa)));
        });

        $placasInspeccionadas = $inspecciones->keyBy(function ($item) {
            return strtoupper(trim(str_replace('-', '', $item->placa_vehiculo)));
        });

        $coincidentes = [];
        $soloEnLinea = [];     // En cámara pero NO en sistema (Alerta Roja)
        $soloEnSistema = [];   // En sistema pero NO en cámara (Alerta Amarilla)

        // Evaluar capturas de línea
        foreach ($placasCapturadas as $placaNorm => $captura) {
            if ($placasInspeccionadas->has($placaNorm)) {
                $coincidentes[] = [
                    'placa' => $captura->numero_placa,
                    'hora_camara' => $captura->fecha_captura,
                    'inspeccion_id' => $placasInspeccionadas[$placaNorm]->id,
                    'resultado' => $placasInspeccionadas[$placaNorm]->resultado_estado
                ];
            } else {
                $soloEnLinea[] = [
                    'placa' => $captura->numero_placa,
                    'hora_captura' => $captura->fecha_captura,
                    'confianza' => $captura->nivel_confianza
                ];
            }
        }

        // Evaluar inspecciones de sistema que no pasaron por la cámara
        foreach ($placasInspeccionadas as $placaNorm => $inspeccion) {
            if (!$placasCapturadas->has($placaNorm)) {
                $soloEnSistema[] = [
                    'placa' => $inspeccion->placa_vehiculo,
                    'hora_inicio' => $inspeccion->hora_inicio,
                    'propietario' => $inspeccion->propietario_nombre,
                    'resultado' => $inspeccion->resultado_estado
                ];
            }
        }

        return view('livewire.admin.conciliacion-placas', [
            'coincidentes' => $coincidentes,
            'soloEnLinea' => $soloEnLinea,
            'soloEnSistema' => $soloEnSistema
        ]);
    }*/
    
    public function render()
    {
        // 1. Obtener capturas de la cámara en esa fecha
        $capturas = DB::table('capturas_placas')
            ->whereDate('fecha_captura', $this->fecha)
            ->get();

        // 2. Obtener inspecciones maestras (sistema) en esa fecha
        $inspecciones = DB::table('inspecciones_maestras')
            ->whereDate('fecha_inspeccion', $this->fecha)
            ->get();

        // Obtener el tarifario oficial para la auditoría de cobros
        $tarifarios = DB::table('tarifarios')->get();

        // Normalizar placas para un cruce limpio
        $placasCapturadas = $capturas->keyBy(function ($item) {
            return strtoupper(trim(str_replace('-', '', $item->numero_placa)));
        });

        $placasInspeccionadas = $inspecciones->keyBy(function ($item) {
            return strtoupper(trim(str_replace('-', '', $item->placa_vehiculo)));
        });

        $coincidentes = [];
        $soloEnLinea = [];     
        $soloEnSistema = [];   
        $auditoriaTarifas = []; // Para almacenar las alertas de cobro

        // Evaluar capturas de línea y auditar tarifas de las inspecciones coincidentes
        foreach ($placasCapturadas as $placaNorm => $captura) {
            if ($placasInspeccionadas->has($placaNorm)) {
                $insp = $placasInspeccionadas[$placaNorm];
                
                // Evaluar tarifa
                $estadoTarifa = $this->evaluarTarifa($insp, $tarifarios);

                $coincidentes[] = [
                    'placa' => $captura->numero_placa,
                    'hora_camara' => $captura->fecha_captura,
                    'inspeccion_id' => $insp->id,
                    'resultado' => $insp->resultado_estado,
                    'monto' => $insp->monto_total,
                    'categoria' => $insp->categoria_vehiculo,
                    'tipo_atencion' => $insp->tipo_atencion,
                    'auditoria_tarifa' => $estadoTarifa
                ];

                if ($estadoTarifa['estado'] != 'correcto') {
                    $auditoriaTarifas[] = [
                        'placa' => $insp->placa_vehiculo,
                        'categoria' => $insp->categoria_vehiculo,
                        'monto' => $insp->monto_total,
                        'tipo' => $estadoTarifa['estado'], // subcobro o sobrecobro
                        'mensaje' => $estadoTarifa['mensaje']
                    ];
                }
            } else {
                $soloEnLinea[] = [
                    'placa' => $captura->numero_placa,
                    'hora_captura' => $captura->fecha_captura,
                    'confianza' => $captura->nivel_confianza
                ];
            }
        }

        // Evaluar inspecciones de sistema sin cámara
        foreach ($placasInspeccionadas as $placaNorm => $inspeccion) {
            if (!$placasCapturadas->has($placaNorm)) {
                $soloEnSistema[] = [
                    'placa' => $inspeccion->placa_vehiculo,
                    'hora_inicio' => $inspeccion->hora_inicio,
                    'propietario' => $inspeccion->propietario_nombre,
                    'resultado' => $inspeccion->resultado_estado
                ];
            }
        }

        return view('livewire.admin.conciliacion-placas', [
            'coincidentes' => $coincidentes,
            'soloEnLinea' => $soloEnLinea,
            'soloEnSistema' => $soloEnSistema,
            'auditoriaTarifas' => $auditoriaTarifas
        ]);
    }

    private function evaluarTarifa($insp, $tarifarios)
    {
        // Buscar coincidencia en el tarifario por categoría y tipo de atención
        $tarifaRef = $tarifarios->first(function ($t) use ($insp) {
            return strtoupper(trim($t->categoria_vehiculo)) === strtoupper(trim($insp->categoria_vehiculo))
                && strtoupper(trim($t->tipo_atencion)) === strtoupper(trim($insp->tipo_atencion));
        });

        // Si no encuentra por tipo de atención exacto, busca al menos por categoría general
        if (!$tarifaRef) {
            $tarifaRef = $tarifarios->first(function ($t) use ($insp) {
                return strtoupper(trim($t->categoria_vehiculo)) === strtoupper(trim($insp->categoria_vehiculo));
            });
        }

        if (!$tarifaRef) {
            return ['estado' => 'sin_configuracion', 'mensaje' => 'Sin tarifario definido'];
        }

        $monto = (float) $insp->monto_total;

        if ($monto < (float) $tarifaRef->precio_minimo) {
            return [
                'estado' => 'subcobro', 
                'mensaje' => "Cobro bajo el mínimo (Mín: S/ {$tarifaRef->precio_minimo})"
            ];
        }

        if ($monto > (float) $tarifaRef->precio_maximo) {
            return [
                'estado' => 'sobrecobro', 
                'mensaje' => "Supera el máximo permitido (Máx: S/ {$tarifaRef->precio_maximo})"
            ];
        }

        return ['estado' => 'correcto', 'mensaje' => 'Tarifa Correcta'];
    }
}
