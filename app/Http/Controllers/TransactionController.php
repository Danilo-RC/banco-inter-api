<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:entrada,saida',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        DB::beginTransaction();
        
        try {
            // Cria a transação
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            // Atualiza o saldo do usuário
            if ($request->type === 'entrada') {
                $user->saldo += $request->amount;
            } else {
                $user->saldo -= $request->amount;
            }
            
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transação criada com sucesso',
                'transaction' => $transaction,
                'saldo_atual' => $user->saldo
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar transação'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transação não encontrada'
            ], 404);
        }

        DB::beginTransaction();
        
        try {
            // Reverte o saldo do usuário
            if ($transaction->type === 'entrada') {
                $user->saldo -= $transaction->amount;
            } else {
                $user->saldo += $transaction->amount;
            }
            
            $user->save();

            // Remove a transação
            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transação removida com sucesso',
                'saldo_atual' => $user->saldo
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover transação'
            ], 500);
        }
    }
}
