<?php

namespace App\Livewire\RRHH;

use Livewire\Component;
use App\Models\User;
use App\Models\Planilla;
use App\Models\Contrato;
use Livewire\Attributes\On;

class CrearPlanilla extends Component
{
    public $abierto = false;

    // Campos del formulario
    public $userId, $periodo, $sueldo_base = 0;
    public $asignacion_familiar = 0, $horas_extras = 0, $movilidad = 0, $otros_ingresos = 0;
    public $otros_descuentos = 0, $observacion;
    public $tipo_planilla = 'Mensual';

    public $total_visual = 0;

    #[On('abrir-modal-planilla')]
    public function abrirModal()
    {
        $this->reset(['userId', 'sueldo_base', 'asignacion_familiar', 'horas_extras', 'movilidad', 'otros_ingresos', 'otros_descuentos', 'observacion', 'total_visual']);
        $this->periodo = now()->format('Y-m-d');
        $this->abierto = true;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'userId' && $this->userId) {
            $contrato = Contrato::where('user_id', $this->userId)->where('status', 'Activo')->first();
            if ($contrato) {
                $this->sueldo_base = $contrato->sueldo_neto;
            } else {
                $this->sueldo_base = 0;
            }
        }

        if (in_array($propertyName, ['sueldo_base', 'asignacion_familiar', 'horas_extras', 'movilidad', 'otros_ingresos', 'otros_descuentos'])) {
            $this->calcularTotal();
        }
    }

    public function calcularTotal()
    {
        $ingresos = (floatval($this->sueldo_base) + floatval($this->asignacion_familiar) +
                     floatval($this->horas_extras) + floatval($this->movilidad) +
                     floatval($this->otros_ingresos));

        $this->total_visual = $ingresos - floatval($this->otros_descuentos);
    }

    public function guardar()
    {
        $this->validate([
            'userId' => 'required',
            'periodo' => 'required|date',
            'sueldo_base' => 'required|numeric',
        ]);

        $contrato = Contrato::where('user_id', $this->userId)->where('status', 'Activo')->first();

        Planilla::create([
            'contrato_id' => $contrato->id,
            'periodo' => $this->periodo,
            'sueldo_base' => $this->sueldo_base,
            'asignacion_familiar' => $this->asignacion_familiar,
            'horas_extras' => $this->horas_extras,
            'movilidad' => $this->movilidad,
            'otros_ingresos' => $this->otros_ingresos,
            'otros_descuentos' => $this->otros_descuentos,
            'observacion' => $this->observacion,
            'planilla' => $this->tipo_planilla,
            'estado_pago' => 0
        ]);

        $this->abierto = false;
        $this->dispatch('refresh-planilla');
        $this->dispatch('minAlert', titulo: 'ÉXITO', mensaje: 'Planilla generada', icono: 'success');
    }

    public function render()
    {
        return view('livewire.r-r-h-h.crear-planilla', [
            'usuarios' => User::has('contrato')->get()
        ]);
    }
}
