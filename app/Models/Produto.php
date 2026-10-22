<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    /** @var list<string> */
    protected $fillable = ['nome', 'descricao', 'preco'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['preco' => 'decimal:2'];
    }
}
