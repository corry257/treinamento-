<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estagiario;

class EstagiarioController extends Controller
{
   public function index()
{
  return view('estagiarios.index', [
    'estagiarios' => \App\Models\Estagiario::all()
  ]);
}


public function create()
{   
    \App\Models\Estagiario::truncate();

    $estagiario1 = new \App\Models\Estagiario;
    $estagiario1->nome = "João";
    $estagiario1->email = "joao@usp.br";
    $estagiario1->idade = 26;
    $estagiario1->save();

    $estagiario2 = new \App\Models\Estagiario;
    $estagiario2->nome = "Maria";
    $estagiario2->email = "maria@usp.br";
    $estagiario2->idade = 27;
    $estagiario2->save();

    return redirect("/estagiarios");

}
}