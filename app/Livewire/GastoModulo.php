<?php

namespace App\Livewire;

use App\Models\Gasto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GastoModulo extends Component
{
    public $fecha, $descripcion, $monto, $tipo_egreso = 'DIARIO';
    public $categoria_gasto, $metodo_pago = 'EFECTIVO', $numero_comprobante;

    public $abrirFormulario = false; // Controla la visibilidad

    public function mount()
    {
        $this->fecha = date('Y-m-d');
    }

    public function toggleFormulario()
    {
        $this->abrirFormulario = !$this->abrirFormulario;
    }

    public function guardarGasto()
    {
        $this->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.10',
            'tipo_egreso' => 'required|in:DIARIO,MENSUAL',
            'metodo_pago' => 'required|string'
        ]);

        try {
            Gasto::create([
                'fecha' => $this->fecha,
                'descripcion' => $this->descripcion, // Guardar en mayúsculas para consistencia
                'monto' => $this->monto,
                'tipo_egreso' => $this->tipo_egreso,
                'categoria_gasto' => $this->categoria_gasto,
                'metodo_pago' => $this->metodo_pago,
                'numero_comprobante' => $this->numero_comprobante,
                'id_usuario' => Auth::id(),
            ]);

            // Reset completo a valores iniciales
            $this->reset(['descripcion', 'monto', 'numero_comprobante', 'categoria_gasto']);
            $this->tipo_egreso = 'DIARIO';
            $this->metodo_pago = 'EFECTIVO';

            $this->abrirFormulario = false;
            $this->dispatch('minAlert', titulo: 'ÉXITO', mensaje: 'Gasto registrado correctamente', icono: 'success');
        } catch (\Exception $e) {
            $this->dispatch('minAlert', titulo: 'ERROR', mensaje: 'No se pudo registrar: ' . $e->getMessage(), icono: 'error');
        }
    }

    public function eliminarGasto($id)
    {
        try {
            Gasto::find($id)->delete();
            $this->dispatch('minAlert', titulo: 'ELIMINADO', mensaje: 'El registro ha sido borrado', icono: 'success');
        } catch (\Exception $e) {
            $this->dispatch('minAlert', titulo: 'ERROR', mensaje: 'No se pudo eliminar el registro', icono: 'error');
        }
    }

    public function render()
    {
        $fechaCarbon = Carbon::parse($this->fecha);
        $mes = $fechaCarbon->month;
        $anio = $fechaCarbon->year;

        $gastosMes = Gasto::whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.gasto-modulo', [
            'gastosDiarios' => $gastosMes->where('tipo_egreso', 'DIARIO'),
            'gastosMensuales' => $gastosMes->where('tipo_egreso', 'MENSUAL'),
            'nombreMes' => $fechaCarbon->translatedFormat('F Y')
        ]);
    }
}
