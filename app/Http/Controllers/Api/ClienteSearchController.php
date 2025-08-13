<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteSearchController extends Controller
{
    /**
     * Busca clientes para autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $clientes = Cliente::where('user_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('contact_person', 'like', '%' . $query . '%');
            })
            ->select('id', 'name', 'email', 'contact_person')
            ->limit(10)
            ->get();
            
        return response()->json($clientes->map(function ($cliente) {
            return [
                'id' => $cliente->id,
                'name' => $cliente->name,
                'email' => $cliente->email,
                'contact_person' => $cliente->contact_person,
                'display' => $cliente->name . ($cliente->email ? ' (' . $cliente->email . ')' : '')
            ];
        }));
    }
}