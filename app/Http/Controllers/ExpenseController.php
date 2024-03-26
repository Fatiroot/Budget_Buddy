<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseResource;

class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Get all expenses",
     *     description="Retrieve a list of all expenses",
     *     @OA\Response(response="200", description="List of expenses"),
     *     @OA\Response(response="404", description="No expense found"),
     * )
     */
    public function index()
    {
        $userId = Auth::id();
        $expenses =Expense::where('user_id', $userId)->get();
        $response = [
            'status' => 'ok',
            'data' => ExpenseResource::collection($expenses)
        ];
        return response()->json(array('expenses' => $response ));
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Create a new expense",
     *     description="Create a new expense with provided name and age",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description","price"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="integer")
     *         )
     *     ),
     *     @OA\Response(response="201", description="expense created"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $expense = Expense::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => $userId,
        ]);
        return response()->json(['expense' => $expense], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // This method is not implemented
    }

    /**
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Update a expense",
     *     description="Update the details of a expense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the expense to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description","price"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="integer")
     *         )
     *     ),
     *     @OA\Response(response="200", description="expense updated"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="404", description="expense not found")
     * )
     */
    public function update(Request $request,Expense $expense)
    {
        $this->authorize('update', $expense);
        $expense = Expense::find($expense->id);
        $expense->update($request->all());
        return response()->json(['message' => 'expense updated successfully', 'expense' => $expense]);
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Delete a expense",
     *     description="Delete a expense by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the expense to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="expense deleted"),
     *     @OA\Response(response="404", description="expense not found")
     * )
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense = Expense::findOrFail($expense->id);
        $expense->delete();
        return response()->json(['message' => 'expense deleted successfully', 'expense' => $expense]);
    }
}
