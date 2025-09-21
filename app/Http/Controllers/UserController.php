<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Arquivo inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Remove a foto anterior se existir
        if ($user->foto_perfil && Storage::exists('public/' . $user->foto_perfil)) {
            Storage::delete('public/' . $user->foto_perfil);
        }

        // Salva a nova foto
        $path = $request->file('photo')->store('profile_photos', 'public');
        
        // Atualiza o usuário
        $user->update([
            'foto_perfil' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto atualizada com sucesso',
            'user' => $user
        ]);
    }
}
