<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.autores.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.autores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // CORREÇÃO: Adicionada a regra 'unique:autores' para verificar se o e-mail já existe
            'email' => 'nullable|email|max:255|unique:autores,email',
            'contact' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cor' => 'nullable|string|in:blue,green,pink,yellow,orange,white',
        ]);
        
        // Processa o upload da logo se fornecida
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('autores/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Define cor padrão se não fornecida
        if (!isset($validated['cor'])) {
            $validated['cor'] = 'white';
        }

        $validated['user_id'] = Auth::id();
        $validated['is_complete'] = true;

        Autor::create($validated);

        return redirect()->route('autores.index')->with('success', 'Autor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Autor $autor): View
    {
        return view('admin.autores.show', [
            'autor' => $autor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Autor $autor): View
    {
        return view('admin.autores.edit', ['autor' => $autor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Autor $autor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // CORREÇÃO: A regra 'unique' agora ignora o e-mail do autor que está a ser editado
            'email' => 'nullable|email|max:255|unique:autores,email,' . $autor->id,
            'contact' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cor' => 'nullable|string|in:blue,green,pink,yellow,orange,white',
        ]);
        
        // Processa o upload da nova logo se fornecida
        if ($request->hasFile('logo')) {
            // Remove a logo antiga se existir
            if ($autor->logo) {
                Storage::disk('public')->delete($autor->logo);
            }
            
            $logoPath = $request->file('logo')->store('autores/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $validated['is_complete'] = true;

        $autor->update($validated);

        return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor): RedirectResponse
    {
        $autor->delete();
        return redirect()->route('autores.index')->with('success', 'Autor deletado com sucesso!');
    }

    /**
     * Search for authors based on a query.
     */
    public function search(Request $request)
    {
        $autores = Autor::where('user_id', Auth::id()) // Garante que só busca nos seus autores
                         ->where('name', 'LIKE', '%' . $request->query('q') . '%')
                         ->select('id', 'name')
                         ->take(10)
                         ->get();

        return response()->json($autores);
    }
}
