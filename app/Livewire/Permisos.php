<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permisos extends Component
{
    use WithPagination;

    public $search = '';
    public $cant = '10';

    public $open_edit = false;
    public $name, $description;
    public $permission_id;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $permisos = Permission::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        return view('livewire.permisos', compact('permisos'));
    }

    public function create()
    {
        $this->reset(['name', 'description', 'permission_id']);
        $this->resetValidation();
        $this->open_edit = true;
    }

    public function edit(Permission $permission)
    {
        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->description = $permission->description;
        $this->resetValidation();
        $this->open_edit = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permission_id,
            'description' => 'required|string|max:255', // Regla para descripción
        ]);

        if ($this->permission_id) {
            $permiso = Permission::where('id', $this->permission_id)->where('guard_name', 'web')->first();
            if ($permiso) {
                $permiso->update([
                    'name' => $this->name,
                    'description' => $this->description // Actualizar descripción
                ]);
                $msg = "Permiso actualizado correctamente.";
            }
        } else {
            Permission::create([
                'name' => $this->name,
                'description' => $this->description, // Guardar descripción
                'guard_name' => 'web'
            ]);
            $msg = "Permiso creado correctamente.";
        }

        $this->reset(['open_edit', 'name', 'description', 'permission_id']);
        $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: $msg, icono: "success");
    }

    public function delete($id)
    {
        $permiso = Permission::where('id', $id)->where('guard_name', 'web')->first();
        if ($permiso) {
            $permiso->delete();
        }
        $this->dispatch('minAlert', titulo: "ELIMINADO", mensaje: "El permiso ha sido borrado.", icono: "warning");
    }
}
