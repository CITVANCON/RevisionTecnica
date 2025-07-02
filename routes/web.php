<?php

use App\Http\Controllers\MTCSoapController;
use App\Http\Controllers\PdfController;
use App\Livewire\AdministracionInspecciones;
use App\Livewire\EditarLineaInspeccion;
use App\Livewire\Expedientes;
use App\Livewire\Linea;
use App\Livewire\Prueba;
use App\Livewire\SubirFotografias;
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


    //Ruta para alta de vehiculo
    Route::get('/altavehiculo', Prueba::class)->name('altavehiculo');

    // Rutas para linea de inspeccion
    Route::get('/lineainspeccion', Linea::class)->name('lineainspeccion');
    Route::get('/lineainspeccion/{idPropuesta}', EditarLineaInspeccion::class)->name('editar-lineainspeccion');

    // Ruta para subir fotografias a una propuesta
    Route::get('/subir-fotografias', SubirFotografias::class)->name('subirFotografias');

    //Ruta para admin inspecciones
    Route::get('/Admin-inspecciones', AdministracionInspecciones::class)->name('AdminInspecciones');

    Route::get('/Expedientes', Expedientes::class)->name('expedientes');


    //RUTAS PARA STREAM Y DESCARGA DE PDFS
    Route::controller(PdfController::class)->group(function () {

        //Rutas para ver certificado anual GNV
        Route::get('/inspeccion/{id}', 'generaPdfInspeccion')->name("inspeccion");
        //Rutas para descargar certificado anual GNV
        Route::get('/inspeccion/{id}/descargar', 'descargarPdfInspeccion')->name("descargarInspeccion");
        
    });


    //Rutas para procesos de INTEROPERABILIDAD (soap)
    Route::get('/mtc/iniciar', [MTCSoapController::class, 'iniciarOperacion']);
    //Route::get('/mtc/consultar-vehiculo', [MTCSoapController::class, 'consultarVehiculo']);
    Route::get('/mtc/registrar-poliza', [MTCSoapController::class, 'registrarPoliza']);
    Route::get('/mtc/registrar-resultado', [MTCSoapController::class, 'registrarResultadoRevision']);
    Route::get('/mtc/actualizar-poliza', [MTCSoapController::class, 'actualizarPoliza']);
    Route::get('/mtc/anular-certificado', [MTCSoapController::class, 'anularCertificado']);
    Route::get('/mtc/cerrar-operaciones', [MTCSoapController::class, 'cerrarOperaciones']);

    // Llamar al método del controlador desde el componente usando Http::post(...)
    Route::post('/mtc/consultar-vehiculo', [MTCSoapController::class, 'consultarVehiculo'])->name('mtc.consultarVehiculo');






    
});
