<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Collection;

class ProdutoRepository
{
    /**
     * @param  array{nome?: string|null, preco?: numeric-string|int|float|null}  $filtros
     * @return Collection<int, Produto>
     */
    public function search(array $filtros = []): Collection
    {
        return Produto::query()
            ->when(filled($filtros['nome'] ?? null), function ($query) use ($filtros): void {
                $query->where('nome', 'like', '%'.$filtros['nome'].'%');
            })
            ->when(($filtros['preco'] ?? null) !== null, function ($query) use ($filtros): void {
                $query->where('preco', $filtros['preco']);
            })
            ->orderBy('id')
            ->get();
    }

    /** @param array<string, mixed> $dados */
    public function update(Produto $produto, array $dados): Produto
    {
        $produto->update($dados);

        return $produto->refresh();
    }

    public function delete(Produto $produto): bool
    {
        return $produto->delete();
    }
}
