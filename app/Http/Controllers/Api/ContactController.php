<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Armazena uma nova mensagem de contato/anúncio.
     */
    public function store(Request $request): JsonResponse
    {
        // Validação dos campos
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'type'    => ['required', Rule::in(['contact', 'advertise'])],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        // Armazena no banco
        Contact::create($data);

        // Retorna confirmação
        return response()->json([
            'message' => 'Contato recebido com sucesso!',
        ], 201);
    }
}
