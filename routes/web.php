<?php

use App\Http\Controllers\BarCodeGeneratorController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ExpedicionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoteController;
use App\Http\Controllers\PalletController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductosCreadosController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UbicacionesController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group([ 'middleware' => ['auth']], function () {
    Route::get('migrar-db', function () {
        Artisan::call('migrate');
        dd("Done");
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/almacen/lotes', [LoteController::class, 'index'])->name('lotes');
    Route::get('/almacen/lotes-ajax', [LoteController::class, 'getLotes'])->name('get.lotes');

    Route::get('/almacen/pallets', [PalletController::class, 'index'])->name('pallets');
    Route::get('/almacen/pallet', [PalletController::class, 'getPallet'])->name('get.pallet');
    Route::get('/almacen/pallet/historial', [PalletController::class, 'getPalletHistorial'])->name('get.pallet.hist');
    Route::get('/almacen/pallets-ajax', [PalletController::class, 'getPallets'])->name('get.pallets');
    Route::post('/almacen/ubicar-pallet', [PalletController::class, 'ubicarPallet'])->name('ubicar.pallet');
    Route::get('/almacen/pallets-pendientes', [PalletController::class, 'getPalletPendientes'])->name('get.pallet.pendientes');
    Route::get('/almacen/pallet-detalles', [PalletController::class, 'getPalletDetalles'])->name('get.pallet.detalles');
    Route::post('/almacen/pallet-cambiar-estado', [PalletController::class, 'cambiarEstadoPallet'])->name('cambiar.estado.pallet');

    Route::get('/almacen/productos', [ProductController::class, 'index'])->name('products');

    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/admin/usuarios-ajax', [UsuarioController::class, 'getUsuarios'])->name('get.usuarios');
    Route::post('/admin/usuario', [UsuarioController::class, 'store'])->name('store.usuario');

    Route::get('/depositos', [UbicacionesController::class, 'getDepositos'])->name('get.depositos');
    Route::get('/deposito/calles', [UbicacionesController::class, 'getCalles'])->name('get.calles');
    Route::get('/deposito/ubicaciones', [UbicacionesController::class, 'getUbicaciones'])->name('get.ubicaciones');

    Route::get('/pedidos', [PedidosController::class, 'index'])->name('pedidos');
    Route::get('/pedidos-armados', [PedidosController::class, 'pedidosArmados'])->name('pedidos.armados');
    Route::get('/get-pedidos', [PedidosController::class, 'getPedidos'])->name('get.pedidos');
    Route::get('/get-pedidos-armados', [PedidosController::class, 'getPedidosArmados'])->name('get.pedidos.armados');
    Route::get('/get-pedido-item', [PedidosController::class, 'getDetallesPedido'])->name('get.pedido.items');
    Route::get('/get-pedido-armado-item', [PedidosController::class, 'getDetallesPedidoArmado'])->name('get.pedido-armado.items');
    Route::get('/preparar-pedido', [PedidosController::class, 'prepararPedido'])->name('preparar.pedido');
    Route::get('/pedidos-en-preparacion', [PedidosController::class, 'pedidosEnPreparacion'])->name('pedidos.en.prep');
    Route::get('/get-pedidos-en-preparacion', [PedidosController::class, 'getPedidosEnPreparacion'])->name('get.pedidos.en.preparacion');
    Route::post('/preparar-pedido-view', [PedidosController::class, 'prepararPedidoView'])->name('preparar.pedido.view');
    Route::post('/set-pesos', [PedidosController::class, 'setPesos'])->name('set.pesos');
    Route::get('/estados-de-pedidos', [PedidosController::class, 'pedidosEstado'])->name('pedidos.estados');
    Route::get('/detalles-pedido/{nro_comp}', [PedidosController::class, 'pedidoEstadoDetalle'])->name('ver.pedido.en.transito');

    Route::get('/ver-ubicacion/{idUbicacion}/{idSPallet}', [UbicacionesController::class, 'verUbicacion'])->name('ver.ubicacion');

    Route::get('/configuraciones', [ConfiguracionController::class, 'configuraciones'])->name('configuraciones');
    Route::get('/cambiar-color-estado', [ConfiguracionController::class, 'setNewColor'])->name('set.color.estado');
    Route::get('/cambiar-defecto-estado', [ConfiguracionController::class, 'setDefault'])->name('set.default.estado');
    Route::post('/crear-ubicaciones', [ConfiguracionController::class, 'crearUbicaciones'])->name('create.ubicaciones');

    Route::get('/cod-barras-a-pedido', [PedidosController::class, 'setCodbarrasToPEdido'])->name('set.cod-barras.to.pedido');
    Route::get('/quitar-articulo-session', [PedidosController::class, 'quitarArticuloSession'])->name('quitar.articulo.session');
    Route::get('/quitar-caja-session', [PedidosController::class, 'quitarCajaSession'])->name('quitar.caja.session');
    Route::get('/verificar', [PedidosController::class, 'verificarPaseAExpedicion'])->name('verificar.pase.a.aexpedicion');
    Route::post('/pasar-a-expedicion', [PedidosController::class, 'pasarAExpedicion'])->name('pasar.a.expedicion');
    Route::post('/cerrar-pedido', [PedidosController::class, 'cerrarPedido'])->name('cerrar.pedido');

    Route::get('/expedicion', [ExpedicionController::class, 'index'])->name('expedicion');
    Route::get('/get-expedicion', [ExpedicionController::class, 'getExpediciones'])->name('get.expediciones');
    Route::get('/preparar-palet/{token?}', [ExpedicionController::class, 'prepararPalet'])->name('preparar.palet');
    Route::get('/verificar-codigo-barras-en-expedicion', [ExpedicionController::class, 'verificarCodbarrasEnExpedicion'])->name('verificar.cod-barras.en-expedicion');
    Route::get('/palets-pendientes', [ExpedicionController::class, 'paletsPendientes'])->name('palet.pendientes');
    Route::post('/eliminar-palet-en-preparacion', [ExpedicionController::class, 'deletePalet'])->name('delete.palet.en.preparacion');
    Route::get('/get-items-palet', [ExpedicionController::class, 'getItemPalet'])->name('get.item.palet');
    Route::post('/cerrar-y-ubicar-palet', [ExpedicionController::class, 'cerrarPalet'])->name('cerrar.palet');
    Route::post('/eliminar-cajar-palet-en-preparacion', [ExpedicionController::class, 'deleteCaja'])->name('delete.caja.en.preparacion');
    Route::get('/palet-armados', [ExpedicionController::class, 'paletArmados'])->name('palet.armados');
    Route::get('/palet-armado/{pallet_armado_id}', [ExpedicionController::class, 'getPaletArmadoDetalles'])->name('ver.preparar.armado');

    Route::get('/palets-envasados', [PalletController::class, 'index'])->name('palets.envasado');
    Route::get('/agregar-palet-envasado', [PalletController::class, 'add'])->name('add.palet.envasado');
    Route::get('/get-items-palet-envasado-tabla', [PalletController::class, 'getEnvasadosIndex'])->name('get.envasados.index');
    Route::get('/get-items-palet-envasado', [PalletController::class, 'getItemsPaletEnvasado'])->name('get.items.palet.envasado');
    Route::post('/guardar-palet-envasado', [PalletController::class, 'store'])->name('store.palet.envasado');

    Route::get('/generar-codigo-barras/{code_bar}/{tipo}', [BarCodeGeneratorController::class, 'generar'])->name('generate.bar_code');
});
