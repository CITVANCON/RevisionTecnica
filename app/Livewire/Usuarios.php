<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Usuarios extends Component
{
    use WithPagination;

    // Propiedades para búsqueda y filtrado
    public $search = '';
    public $cant = '10';

    // Propiedades para edición
    public $open_edit = false;
    public $user_edit;
    public $name, $email, $dni, $celular, $direccion, $fecha_nacimiento;
    public $numero_cuenta, $sistema_pensionario, $asignacion_familiar, $beneficios;

    public $roles_selected = [];

    protected function rules() {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->user_edit->id ?? 0),
            'dni' => 'nullable|string|max:8|min:8',
            'celular' => 'nullable|string|max:9|min:9',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'numero_cuenta' => 'nullable|string',
            'sistema_pensionario' => 'nullable|string',
            'asignacion_familiar' => 'boolean',
            'beneficios' => 'nullable|string',
        ];
    }

    // Resetear paginación cuando cambia la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuarios = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        $roles = Role::all();

        return view('livewire.usuarios', compact('usuarios', 'roles'));
    }

    public function edit(User $user)
    {
        $this->user_edit = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->dni = $user->dni;
        $this->celular = $user->celular;
        $this->direccion = $user->direccion;
        $this->fecha_nacimiento = optional($user->fecha_nacimiento)->format('Y-m-d');
        $this->numero_cuenta = $user->numero_cuenta;
        $this->sistema_pensionario = $user->sistema_pensionario;
        $this->asignacion_familiar = (bool)$user->asignacion_familiar;
        $this->beneficios = $user->beneficios;
        $this->roles_selected = $user->roles->pluck('id')->toArray();
        $this->open_edit = true;
    }

    public function save()
    {
        $this->validate();
        $this->user_edit->update([
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'celular' => $this->celular,
            'direccion' => $this->direccion,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'numero_cuenta' => $this->numero_cuenta,
            'sistema_pensionario' => $this->sistema_pensionario,
            'asignacion_familiar' => $this->asignacion_familiar,
            'beneficios' => $this->beneficios,
        ]);

        // Sincronizar roles (añade los nuevos y quita los que no estén en el array)
        $this->user_edit->roles()->sync($this->roles_selected);
        $this->reset(['open_edit', 'roles_selected']);
        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Usuario actualizado correctamente.", icono: "success");
    }
}
