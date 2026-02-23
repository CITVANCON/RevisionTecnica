<?php

namespace App\Livewire\RRHH;

use Livewire\Component;
use App\Models\Planilla;
use App\Models\Contrato;
use Livewire\Attributes\On;

class CrearPlanilla extends Component
{
    public $abierto = false;
    public $periodo; // Inicia nulo

    public $lista_planilla = [];

    // Definimos el monto constante de asignación familiar
    const MONTO_ASIGNACION = 113.00;

    #[On('abrir-modal-planilla')]
    public function abrirModal()
    {
        // Limpiamos todo al abrir, no cargamos nada aún
        $this->reset(['periodo', 'lista_planilla']);
        $this->abierto = true;
    }

    // Solo cuando el usuario selecciona una fecha en el input date
    public function updatedPeriodo($value)
    {
        if ($value) {
            $this->cargarTrabajadores();
        } else {
            $this->lista_planilla = [];
        }
    }

    public function cargarTrabajadores()
    {
        // Solo buscamos en la BD si el periodo existe
        if (!$this->periodo) return;

        $contratosActivos = Contrato::with('user')
            ->where('status', 'Activo')
            ->get();

        $this->lista_planilla = [];

        foreach ($contratosActivos as $contrato) {

            // Verificamos si el usuario tiene asignación familiar (1 = Sí, 0 = No)
            $tieneAsignacion = ($contrato->user->asignacion_familiar == 1);
            $montoAsignacion = $tieneAsignacion ? self::MONTO_ASIGNACION : 0;

            $this->lista_planilla[] = [
                'contrato_id'         => $contrato->id,
                'nombre'              => $contrato->user->name,
                'sueldo_base'         => $contrato->sueldo_neto,
                'asignacion_familiar' => $montoAsignacion,
                'horas_extras'        => 0,
                'movilidad'           => 0,
                'otros_ingresos'      => 0,
                'otros_descuentos'    => 0,
                'planilla'            => $contrato->user->beneficios,
                'numero_cuenta'       => $contrato->user->numero_cuenta,
                'observacion'         => '',
                'total'               => $contrato->sueldo_neto + $montoAsignacion
            ];
        }
    }

    public function updatedListaPlanilla($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];

        if (!isset($this->lista_planilla[$index])) return;

        $this->recalcularFila($index);
    }
    private function recalcularFila($index)
    {
        $fila = &$this->lista_planilla[$index];

        $ingresos = floatval($fila['sueldo_base']) +
            floatval($fila['asignacion_familiar']) +
            floatval($fila['horas_extras']) +
            floatval($fila['movilidad']) +
            floatval($fila['otros_ingresos']);

        $fila['total'] = $ingresos - floatval($fila['otros_descuentos']);
    }

    public function guardarMasivo()
    {
        $this->validate([
            'periodo' => 'required|date',
            'lista_planilla' => 'required|array|min:1',
        ]);

        foreach ($this->lista_planilla as $item) {
            Planilla::create([
                'contrato_id'         => $item['contrato_id'],
                'periodo'             => $this->periodo,
                'sueldo_base'         => $item['sueldo_base'],
                'asignacion_familiar' => $item['asignacion_familiar'],
                'horas_extras'        => $item['horas_extras'],
                'movilidad'           => $item['movilidad'],
                'otros_ingresos'      => $item['otros_ingresos'],
                'otros_descuentos'    => $item['otros_descuentos'],
                'planilla'            => $item['planilla'],
                'numero_cuenta'       => $item['numero_cuenta'],
                'observacion'        => $item['observacion'],
                'estado_pago'         => 0
            ]);
        }

        $this->abierto = false;
        $this->dispatch('refresh-planilla');
        $this->dispatch('minAlert', titulo: 'ÉXITO', mensaje: 'Planilla generada', icono: 'success');
    }

    public function render()
    {
        return view('livewire.r-r-h-h.crear-planilla');
    }
}
