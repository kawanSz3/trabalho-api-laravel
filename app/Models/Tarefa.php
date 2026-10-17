<?php

namespace App\Models;

use Database\Factories\TarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    /** @use HasFactory<TarefaFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['titulo', 'descricao', 'concluida'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['concluida' => 'boolean'];
    }
}
