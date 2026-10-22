<?php

namespace Tests\Unit;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_produto_com_preco_invalido_nao_e_criado(): void
    {
        try {
            app(ProdutoService::class)->create([
                'nome' => 'Teclado',
                'preco' => 0,
            ]);

            $this->fail('Era esperado que a validação rejeitasse o preço.');
        } catch (ValidationException) {
            $this->assertDatabaseCount('produtos', 0);
        }
    }

    public function test_produto_existente_e_atualizado_com_dados_validos(): void
    {
        $produto = Produto::query()->create([
            'nome' => 'Teclado',
            'descricao' => 'Modelo antigo',
            'preco' => 100,
        ]);

        $produtoAtualizado = app(ProdutoService::class)->update($produto, [
            'nome' => 'Teclado mecânico',
            'descricao' => 'Modelo novo',
            'preco' => 250,
        ]);

        $this->assertSame('Teclado mecânico', $produtoAtualizado->nome);
        $this->assertSame('250.00', $produtoAtualizado->preco);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Teclado mecânico',
            'preco' => 250,
        ]);
    }

    public function test_produto_nao_e_atualizado_com_preco_invalido(): void
    {
        $produto = Produto::query()->create([
            'nome' => 'Teclado',
            'descricao' => 'Modelo antigo',
            'preco' => 100,
        ]);

        try {
            app(ProdutoService::class)->update($produto, [
                'nome' => 'Teclado atualizado',
                'preco' => 0,
            ]);

            $this->fail('Era esperado que a validação rejeitasse o preço.');
        } catch (ValidationException) {
            $this->assertDatabaseHas('produtos', [
                'id' => $produto->id,
                'nome' => 'Teclado',
                'descricao' => 'Modelo antigo',
                'preco' => 100,
            ]);
        }
    }
}
