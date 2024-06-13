<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ItemRequestApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemRequests = ItemRequest::with('user', 'approvals', 'transfers')->get();
        return response()->json($itemRequests);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'item_name' => 'required|string',
                'quantity' => 'required|integer',
            ]);

            $itemRequest = new ItemRequest();
            $itemRequest->user_id = $validatedData['user_id'];
            $itemRequest->item_name = $validatedData['item_name'];
            $itemRequest->quantity = $validatedData['quantity'];
            $itemRequest->save();

            return response()->json([
                'status' => true,
                'message' => 'Data successfully saved',
                'data' => $itemRequest
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $e->errors()
            ], 422);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(ItemRequest $itemRequest)
    {
        return response()->json($itemRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        Log::info('Update request ID: ' . $id);
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_name' => 'required|string',
            'quantity' => 'required|integer',
          
        ]);

        $record = ItemRequest::findOrFail($id);

        $record->user_id = $request->input('user_id');
        $record->item_name = $request->input('item_name');
        $record->quantity = $request->input('quantity');
    

        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully',
            'data' => $record
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = ItemRequest::findOrFail($id);
        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data deleted successfully',
            'data' => $record
        ], 200);
    }
}
