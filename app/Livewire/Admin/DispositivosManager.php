<?php

namespace App\Livewire\Admin;

use App\Models\DispositivoAutorizado;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class DispositivosManager extends Component
{
    use WithPagination;
    #[Validate('required|min:3', message: 'El nombre de la estación debe tener al menos 3 caracteres.')]
    public $nombre_estacion = '';

    #[Validate('required', message: 'La ubicación es obligatoria.')]
    public $descripcion_ubicacion = '';

    public $search = '';

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Función principal para autorizar la laptop donde estás sentado
    public function autorizarEstaEstacion()
    {
        $this->validate();

        $userAgent = request()->userAgent() ?? '';

        // Validar que no sea un dispositivo móvil
        $moviles = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];
        foreach ($moviles as $movil) {
            if (stripos($userAgent, $movil) !== false) {
                $this->dispatch('minAlert', titulo: "ERROR", mensaje: "No puedes autorizar un celular. Solo PCs o Laptops.", icono: "error");
                return;
            }
        }

        // Generar Token Único
        $token = 'CITVANCON_' . Str::random(60);

        // Guardar en BD
        DispositivoAutorizado::create([
            'device_token' => $token,
            'nombre_estacion' => $this->nombre_estacion,
            'descripcion_ubicacion' => $this->descripcion_ubicacion,
            'sistema_operativo' => $this->getSO($userAgent),
            'navegador' => $this->getBrowser($userAgent),
            'ultima_ip' => request()->ip(),
            'ultima_conexion' => now(),
            'esta_activo' => true
        ]);

        // Despachar evento para guardar el token en el cliente
        $this->dispatch('save-device-token', token: $token);
        
        $this->reset(['nombre_estacion', 'descripcion_ubicacion']);
    }

    public function desactivar($id)
    {
        $dispositivo = DispositivoAutorizado::find($id);
        if ($dispositivo) {
            $dispositivo->update(['esta_activo' => false]);
        }
    }

    private function getSO($ua)
    {
        if (stripos($ua, 'windows') !== false) return 'Windows';
        if (stripos($ua, 'macintosh') !== false) return 'macOS';
        if (stripos($ua, 'linux') !== false) return 'Linux';
        return 'Desconocido';
    }

    private function getBrowser($ua)
    {
        if (stripos($ua, 'edg') !== false) return 'Edge';
        if (stripos($ua, 'chrome') !== false) return 'Chrome';
        if (stripos($ua, 'firefox') !== false) return 'Firefox';
        return 'Otro';
    }

    public function render()
    {
        return view('livewire.admin.dispositivos-manager', [
            'dispositivos' => DispositivoAutorizado::where('nombre_estacion', 'like', '%' . $this->search . '%')
                ->latest()
                ->simplePaginate(10)
        ]);
    }
    
}
