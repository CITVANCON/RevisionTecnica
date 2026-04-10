<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\Attributes\On;

class FormCliente extends Component
{
    public $nombreDelInvocador;

    // VARIABLES PARA CREAR/EDITAR EL CLIENTE
    public $tipo_cliente = 'NATURAL', $nombre_razon_social, $tipo_documento = 'DNI', 
           $numero_documento, $direccion, $telefono, $email;

    // VARIABLES PARA MOSTRAR (VISTA DESHABILITADA)
    public $m_nombre_razon_social, $m_tipo_documento, $m_numero_documento, $m_direccion, $m_telefono, $m_email;

    // VARIABLES DEL COMPONENTE
    public $estado = 'nuevo';
    public $busqueda = false;
    public $clientes;
    public $cliente;

    public function mount($nombreDelInvocador = null)
    {
        $this->nombreDelInvocador = $nombreDelInvocador;
    }

    // LISTENER ESTILO LIVEWIRE 3
    #[On('cargarDatosPersona')] 
    public function cargarDesdeReniec(array $datos)
    {
        $this->nombre_razon_social = $datos['nombre'] ?? $this->nombre_razon_social;
        $this->direccion = $datos['direccion'] ?? $this->direccion;
    }

    public function updatedTipoCliente($value)
    {
        if ($value === 'JURIDICA') {
            $this->tipo_documento = 'RUC';
        } else {
            $this->tipo_documento = 'DNI';
        }
        $this->numero_documento = '';
    }

    public function buscarCliente()
    {
        if (strlen($this->numero_documento) >= 8) {
            $this->clientes = Cliente::where('numero_documento', 'like', '%' . $this->numero_documento . '%')->get();
            if ($this->clientes->count() > 0) {
                $this->busqueda = true;
            } else {
                // En Livewire 3 se recomienda usar dispatch directamente
                $this->dispatch('consultarPadron', numero: $this->numero_documento);
            }
        }
    }

    public function guardaCliente()
    {
        $this->validate([
            "tipo_cliente" => "required",
            "tipo_documento" => "required",
            "numero_documento" => "required|unique:clientes,numero_documento",
            "nombre_razon_social" => "required|min:3",
            "direccion" => "nullable",
            "email" => "nullable|email",
        ]);

        $nuevoCliente = Cliente::create([
            "tipo_cliente" => $this->tipo_cliente,
            "nombre_razon_social" => strtoupper($this->nombre_razon_social),
            "tipo_documento" => $this->tipo_documento,
            "numero_documento" => $this->numero_documento,
            "direccion" => strtoupper($this->direccion),
            "telefono" => $this->telefono,
            "email" => strtolower($this->email),
        ]);

        $this->seleccionaCliente($nuevoCliente->id); // Pasamos solo el ID para mayor seguridad

        $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: "Cliente registrado.", icono: "success");
    }

    public function seleccionaCliente($id)
    {
        $cli = Cliente::find($id);
        if(!$cli) return;

        $this->cliente = $cli;
        $this->tipo_cliente = $cli->tipo_cliente; // Sincronizamos para el primer input
        
        // Variables de visualización
        $this->m_nombre_razon_social = $cli->nombre_razon_social;
        $this->m_tipo_documento = $cli->tipo_documento;
        $this->m_numero_documento = $cli->numero_documento;
        $this->m_direccion = $cli->direccion;
        $this->m_telefono = $cli->telefono;
        $this->m_email = $cli->email;
        
        $this->estado = 'cargado';
        $this->busqueda = false;

        // Emitimos al componente padre con la nueva sintaxis de Livewire 3
        $this->dispatch('cargaCliente', clienteId: $cli->id)->to($this->nombreDelInvocador ?? 'inspeccion-extra-component');
    }

    public function editarCliente()
    {
        // Al editar, pasamos los datos del modelo a las variables de los inputs
        $this->nombre_razon_social = $this->cliente->nombre_razon_social;
        $this->direccion = $this->cliente->direccion;
        $this->telefono = $this->cliente->telefono;
        $this->email = $this->cliente->email;
        $this->numero_documento = $this->cliente->numero_documento;

        $this->estado = 'editando';
    }
    public function actualizarCliente()
    {
        $this->validate([
            "nombre_razon_social" => "required|min:3",
            "direccion" => "nullable",
            "telefono" => "nullable",
            "email" => "nullable|email",
        ]);

        $this->cliente->update([
            "nombre_razon_social" => strtoupper($this->nombre_razon_social),
            "direccion" => strtoupper($this->direccion),
            "telefono" => $this->telefono,
            "email" => strtolower($this->email),
        ]);

        // Refrescamos los datos de la vista deshabilitada
        $this->seleccionaCliente($this->cliente->id);

        $this->dispatch('minAlert', titulo: "ACTUALIZADO", mensaje: "Información del cliente actualizada.", icono: "info");
    }

    public function resetForm()
    {
        $this->reset(['nombre_razon_social', 'numero_documento', 'direccion', 'telefono', 'email', 'tipo_cliente', 'tipo_documento']);
        $this->estado = 'nuevo';
    }
    
    public function render()
    {
        return view('livewire.form-cliente');
    }
}
