<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Buscamos apenas as categorias do usuário logado
        $categorias = Categoria::where('user_id', Auth::id())->orderBy('nome')->get();
        
        // CORREÇÃO: Passamos a variável $categorias para a view
        return view('admin.categorias.index', ['categorias' => $categorias]);
    }

    /**
     * Mostra o formulário para criar uma nova categoria.
     */
    public function create(): View
    {
        return view('admin.categorias.create');
    }

    /**
     * Salva uma nova categoria no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valida os dados e guarda o resultado
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:receita,despesa',
        ]);

        // 2. Adiciona o user_id do usuário logado aos dados validados
        $validated['user_id'] = Auth::id();

        // 3. Cria a nova categoria com todos os dados de uma só vez
        Categoria::create($validated);

        // 4. Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('categorias.index')
                         ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Mostra o formulário para editar uma categoria existente.
     */
    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.edit', ['categoria' => $categoria]);
    }

    /**
     * Atualiza uma categoria existente no banco de dados.
     */
    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:receita,despesa',
        ]);

        $categoria->update($validated);

        return redirect()->route('categorias.index')
                         ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove uma categoria específica do banco de dados.
     */
    public function destroy(Categoria $categoria): RedirectResponse
    {
        $categoria->delete();

        return redirect()->route('categorias.index')
                         ->with('success', 'Categoria deletada com sucesso!');
    }
}
