<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request){

        $erro = '';

        if($request->get('erro') == 1){
            $erro = 'Usuário e/ou senha não existe';
        }else if($request->get('erro') == 2){
            $erro = 'Necessário realizar login para ter acesso a página.'; 
        }
        
        return view('site.login', ['titulo' => 'Login', 'erro' => $erro]);
    }

    public function autenticar(Request $request){

        //regras de validação
        $regras = [
            'usuario' => 'email|required',
            'senha' => 'required'
        ];

        //as mensagens de feedback de validação
        $feedback = [
            'usuario.email' => 'Email inválido!',
            'required' => 'O campo :attribute é obrigatório!'
        ];

        //se não passar pelo validate, a requisição será redirecionada para a rota anterior
        $request->validate($regras, $feedback);

        //recuperando os parâmetros do formulário
        $email = $request->get('usuario');
        $senha = $request->get('senha');

        //iniciando o model user
        $user = new User();

        $usuario = $user->where('email', $email)->where('password', $senha)->get()->first();

        if(!empty($usuario)){
            session_start();

            $_SESSION['name'] = $usuario->name;
            $_SESSION['email'] = $usuario->email;

            return redirect()->route('app.home');
        }else{
            return redirect()->route('site.login', ['erro' => 1]);
        }
    }


    public function sair(){
        session_destroy();
        return redirect()->route('site.index');
    }
}
