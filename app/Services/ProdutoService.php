<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Facades\Validator;

class ProdutoService
{
    /** @param array<string, mixed> $dados */
    public function create(array $dados): Produto
    {
        $dadosValidados = Validator::make($dados, [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco' => ['required', 'numeric', 'gt:0'],
        ])->validate();

        return Produto::query()->create($dadosValidados);
    }

    /** @param array<string, mixed> $dados */
    public function update(Produto $produto, array $dados): Produto
    {
        $dadosValidados = Validator::make($dados, [
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'descricao' => ['sometimes', 'nullable', 'string'],
            'preco' => ['sometimes', 'required', 'numeric', 'gt:0'],
        ])->validate();

        $produto->update($dadosValidados);

        return $produto->refresh();
    }
}
