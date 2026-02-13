<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;

    // Propiedades para búsqueda y filtrado
    public $search = '';
    public $cant = '10';

    // Propiedades para edición
    public $open_edit = false;
    public $user_edit; // Aquí guardaremos el modelo a editar

    // Resetear paginación cuando cambia la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuarios = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        return view('livewire.usuarios', compact('usuarios'));
    }

    public function edit(User $user)
    {
        $this->user_edit = $user;
        $this->open_edit = true;
    }
}
