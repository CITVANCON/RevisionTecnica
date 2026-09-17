<?php

namespace App\Livewire;

use App\Models\Asistencia;
use App\Models\DispositivoAutorizado;
use App\Models\HorarioDetalle;
use App\Models\MarcacionRaw;
use App\Models\User;
use App\Models\UsuarioHorario;
use Carbon\Carbon;
use Livewire\Component;

class MarcadoAsistencia extends Component
{
    public $dni;
    public $tipo = 'Entrada';

    // Propiedades de estado
    public $deviceToken = null; 
    public $isDeviceAuthorized = false;

    /**
     * Método invocado directamente desde JS pasando la cadena del token
     */
    public function verificarToken($token = null)
    {
        $this->deviceToken = trim((string) $token);

        if (empty($this->deviceToken)) {
            $this->isDeviceAuthorized = false;
            return;
        }

        $dispositivo = DispositivoAutorizado::where('device_token', $this->deviceToken)
            ->where('esta_activo', true)
            ->first();

        $this->isDeviceAuthorized = (bool) $dispositivo;
    }

    public function registrarMarcado()
    {
        // Re-validación de seguridad
        $dispositivo = DispositivoAutorizado::where('device_token', $this->deviceToken)
            ->where('esta_activo', true)
            ->first();

        if (!$this->isDeviceAuthorized || !$dispositivo) {
            $this->dispatch('minAlert', titulo: "ERROR DE SEGURIDAD", mensaje: "Intento de marcado desde dispositivo no autorizado", icono: "error");
            return;
        }

        $this->validate([
            'dni' => 'required|digits:8',
        ]);

        $usuario = User::where('dni', $this->dni)->first();

        if (!$usuario) {
            $this->dispatch('minAlert', titulo: "DNI NO ENCONTRADO", mensaje: "Consulte con administración", icono: "error");
            $this->reset('dni');
            return;
        }

        $horarioAsignado = UsuarioHorario::where('user_id', $usuario->id)
            ->where('activo', true)
            ->first();

        if (!$horarioAsignado) {
            $this->dispatch('minAlert', titulo: "ACCESO DENEGADO", mensaje: "Usuario no habilitado para control de asistencia", icono: "warning");
            $this->reset('dni');
            return;
        }

        $ahora = Carbon::now();
        $fechaHoy = $ahora->format('Y-m-d');

        $asistenciaHoy = Asistencia::where('user_id', $usuario->id)
            ->where('fecha', $fechaHoy)
            ->first();

        $this->tipo = ($asistenciaHoy && !is_null($asistenciaHoy->hora_entrada)) ? 'Salida' : 'Entrada';

        MarcacionRaw::create([
            'user_id' => $usuario->id,
            'dni_usado' => $this->dni,
            'momento_marcado' => $ahora,
            'tipo' => $this->tipo,
            'ip_origen' => request()->ip(),
            'metodo_verificacion' => 'PC: ' . ($dispositivo->nombre_estacion ?? 'DESCONOCIDA')
        ]);

        if ($this->tipo === 'Entrada') {
            $this->procesarEntrada($usuario, $ahora, $fechaHoy, $horarioAsignado);
        } else {
            $this->procesarSalida($usuario, $ahora, $fechaHoy, $asistenciaHoy);
        }

        $this->reset('dni');
    }

    private function procesarEntrada($usuario, $ahora, $fechaHoy, $horarioAsignado)
    {
        $minutosTardanza = 0;
        $estado = 'Puntual';

        $diaSemana = $ahora->dayOfWeekIso;
        $detalle = HorarioDetalle::where('horario_id', $horarioAsignado->horario_id)
            ->where('dia_semana', $diaSemana)
            ->first();

        if ($detalle && $detalle->es_laborable) {
            $entradaProg = Carbon::createFromFormat('H:i:s', $detalle->hora_entrada);
            $entradaReal = Carbon::createFromFormat('H:i:s', $ahora->format('H:i:s'));
            
            $diff = $entradaProg->diffInMinutes($entradaReal, false);
            
            if ($diff > $detalle->tolerancia_tardanza) {
                $minutosTardanza = $diff;
                $estado = 'Tardanza';
            }
        }

        Asistencia::updateOrCreate(
            ['user_id' => $usuario->id, 'fecha' => $fechaHoy],
            ['hora_entrada' => $ahora, 'minutos_tardanza' => $minutosTardanza, 'estado' => $estado]
        );

        $this->dispatch('minAlert', titulo: "¡HOLA, " . strtoupper($usuario->name) . "!", mensaje: "ENTRADA REGISTRADA", icono: "success");
    }

    private function procesarSalida($usuario, $ahora, $fechaHoy, $asistencia)
    {
        if ($asistencia) {
            $entrada = Carbon::parse($asistencia->hora_entrada);
            $asistencia->update([
                'hora_salida' => $ahora,
                'minutos_trabajados' => $entrada->diffInMinutes($ahora)
            ]);
        } else {
            Asistencia::create([
                'user_id' => $usuario->id,
                'fecha' => $fechaHoy,
                'hora_salida' => $ahora,
                'estado' => 'Incompleto'
            ]);
        }
        $this->dispatch('minAlert', titulo: "¡ADIÓS, " . strtoupper($usuario->name) . "!", mensaje: "SALIDA REGISTRADA", icono: "info");
    }

    public function render()
    {
        return view('livewire.marcado-asistencia')->layout('layouts.guest');
    }
}