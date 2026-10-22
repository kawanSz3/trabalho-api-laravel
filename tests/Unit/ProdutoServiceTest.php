<?php

namespace Tests\Unit;

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
}
