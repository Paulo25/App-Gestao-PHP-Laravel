<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProdutoDetalheController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
verbo http:
get
post
put
patch
delete
options 
*/

// Route::get('/', function () {
//     return view('welcome');
//     return "Olá seja bem vindo ao curso!";
// });
// Route::get('/sobre-nos', function(){
//     return 'Sobre-nós';
// });
// Route::get('/contato', function(){
//     return 'Contato';
// });

/* rotas com pametros opcionais e tipagagem nas variaveis  */

// Route::get('/contato/{nome}/{sexo?}/{idade?}', function(string $nome, string $sexo = 'indefinido', int $idade = 0){
//           echo "Estamos aqui: {$nome} - {$sexo} - {$idade}";
// });

/* Tratativas de parametros com expressão regular*/

// Route::get('
//           /contato/{nome}/{categoria_id}', 
//           function(
//                     string $nome = 'Desconhecido', 
//                     int $categoria_id = 1 // 1 = informação
//                     ){
//           echo "Estamos aqui: {$nome} - {$categoria_id}";
//           }
// )->where('nome', '[A-Za-z]+')
// ->where('categoria_id', '[0-9]+');

/* Redirecionamente de rotas */

// Route::get('/rota1', function(){
//           echo 'Rota 1';
// })->name('site.rota1');
// Route::get('/rota2', function(){
//           //echo 'Rota 2';
//           return redirect()->route('site.rota1');
// })->name('site.rota2');
//Route::redirect('/rota2', '/rota1'); //redirecionamento de routas -> ao acessar a rota2 automaticamente será redirecionado para rota1


Route::get('/teste/{p1}/{p2}', [TesteController::class, 'teste'])->name('teste');

Route::get('/', 'App\Http\Controllers\PrincipalController@principal')->name('site.index')->middleware('log.acesso');//->middleware(LogAcessoMiddleware::class);
Route::get('/sobre-nos', 'App\Http\Controllers\SobreNosController@sobreNos')->name('site.sobrenos');
Route::get('/contato', '\App\Http\Controllers\ContatoController@contato')->name('site.contato');
Route::post('/contato', '\App\Http\Controllers\ContatoController@salvar')->name('site.contato');

Route::get('/login/{erro?}', [LoginController::class, 'index'])->name('site.login');
Route::post('/login', [LoginController::class, 'autenticar'])->name('site.login');

Route::middleware('log.acesso','autenticacao:padrao,visitante,p3,p4')
->prefix('/app')->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('app.home');
    Route::get('/sair', [LoginController::class, 'sair'])->name('app.sair');
    Route::get('/produto', [ProdutoController::class, 'index'])->name('app.produto');

    Route::prefix('/fonecedor')->group(function(){
        Route::get('/', [FornecedorController::class, 'index'])->name('app.fornecedor');
        Route::post('listar', [FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
        Route::get('listar', [FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
        Route::get('adicionar', [FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
        Route::post('adicionar', [FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
        Route::get('editar/{id}/{msg?}', [FornecedorController::class, 'editar'])->name('app.fornecedor.editar');
        Route::get('excluir/{id}', [FornecedorController::class, 'excluir'])->name('app.fornecedor.excluir');
    });

    #produtos
    Route::resource('produto', ProdutoController::class);
    #produtos detalhes
    Route::resource('produto-detalhe', ProdutoDetalheController::class);

    Route::resource('cliente', ClienteController::class);
    Route::resource('pedido', PedidoController::class);
    // Route::resource('pedido-produto', PedidoProdutoController::class);
    Route::get('pedido-produto/{pedido}', [PedidoProdutoController::class, 'create'])->name('pedido-produto.create');
    Route::post('pedido-produto/{pedido}', [PedidoProdutoController::class, 'store'])->name('pedido-produto.store');
    // Route::delete('pedido-produto/{pedido}/{produto}', [PedidoProdutoController::class, 'destroy'])->name('pedido-produto.destroy');
    Route::delete('pedido-produto/{pedido_produto}/{pedido_id}', [PedidoProdutoController::class, 'destroy'])->name('pedido-produto.destroy');
});

/* Rota de contigência: caso usuário acessa uma determinada rota inexistente, ele cairá nessa rota de fallback*/
Route::fallback(function(){ 
    echo 'A rota acessada não existe. <a href="'.route('site.index').'">clique aqui</a> para ir para página inicial';
});



