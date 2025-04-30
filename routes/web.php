<?php

use App\Livewire\EditarLineaInspeccion;
use App\Livewire\Linea;
use App\Livewire\Prueba;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::get('phpmyinfo', function () {
    phpinfo();
})->name('phpmyinfo');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    //Route::get('/altavehiculo', Prueba::class)->name('altavehiculo');
    Route::get('/altavehiculo', Prueba::class);

    });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/altavehiculo', Prueba::class)->name('altavehiculo');


    Route::get('/lineainspeccion', Linea::class)->name('lineainspeccion');
    Route::get('/lineainspeccion/{idPropuesta}', EditarLineaInspeccion::class)->name('editar-lineainspeccion');

});
