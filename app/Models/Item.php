<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao', 'peso', 'unidade_id'];

    public function itemDetalhe(){
        return $this->hasOne('App\Models\ProdutoDetalhe', 'produto_id', 'id');
        //produto_id -> forein key
        // id -> primary key de produto
    }
}
