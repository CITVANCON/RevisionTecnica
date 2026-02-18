<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    use WithPagination;

    public $search = '';
    public $cant = '10';

    public $open_edit = false;
    public $name;
    public $role_id;

    // Propiedad para almacenar los IDs de los permisos seleccionados
    public $permissions_selected = [];

    protected $rules = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        $all_permissions = Permission::all();

        return view('livewire.roles', compact('roles', 'all_permissions'));
    }

    public function create()
    {
        $this->reset(['name', 'role_id', 'permissions_selected']);
        $this->resetValidation();
        $this->open_edit = true;
    }

    public function edit(Role $role)
    {
        $this->role_id = $role->id;
        $this->name = $role->name;

        // Cargamos los IDs de los permisos que ya tiene este rol
        $this->permissions_selected = $role->permissions->pluck('id')->toArray();

        $this->resetValidation();
        $this->open_edit = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role_id
        ]);

        // 1. Convertimos los IDs seleccionados en nombres o modelos
        // Esto evita el error "There is no permission named '1'"
        $permissions = Permission::whereIn('id', $this->permissions_selected)->get();

        if ($this->role_id) {
            $role = Role::where('id', $this->role_id)->where('guard_name', 'web')->first();

            if ($role) {
                $role->update(['name' => $this->name]);
                // 2. Sincronizamos usando la colección de modelos
                $role->syncPermissions($permissions);
                $msg = "Rol y permisos actualizados correctamente.";
            }
        } else {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => 'web'
            ]);
            // 3. Asignamos usando la colección de modelos
            $role->syncPermissions($permissions);
            $msg = "Rol creado correctamente.";
        }

        $this->reset(['open_edit', 'name', 'role_id', 'permissions_selected']);
        $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: $msg, icono: "success");
    }

    public function delete($id)
    {
        // Especificar guard para eliminar asegura que no haya conflictos
        $role = Role::where('id', $id)->where('guard_name', 'web')->first();
        if ($role) {
            $role->delete();
        }

        $this->dispatch('minAlert', titulo: "ELIMINADO", mensaje: "El rol ha sido borrado.", icono: "warning");
    }
}
