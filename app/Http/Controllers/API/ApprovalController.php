<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = Approval::query();

        if ($status) {
            $query->where('status', $status);
        }

        $approvals = $query->get();

        if ($approvals->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No approvals found for the given criteria',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of approvals fetched successfully',
            'data' => $approvals
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function approve(Request $request, $id)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'status' => 'required|in:approvedByManager,approvedByFinance,rejected,transferred,pending',
                'reason' => 'required_if:status,rejected|string|nullable',
                'transfer_proof' => 'required_if:status,transferred|file|mimes:pdf,png,jpg,jpeg|nullable',
            ]);
    
            // Temukan item request berdasarkan ID
            $itemRequest = ItemRequest::findOrFail($id);
    
            // Update status item request
            $itemRequest->status = $validatedData['status'];
            $itemRequest->save();
    
            // Simpan file bukti transfer jika ada
            $transferProofPath = null;
            if ($request->hasFile('transfer_proof')) {
                $transferProofPath = $request->file('transfer_proof')->store('transfer_proofs', 'public');
            }
    
            // Simpan data approval
            $approval = Approval::create([
                'item_request_id' => $itemRequest->id,
                'user_id' => auth()->id(), // Pastikan pengguna terautentikasi
                'status' => $validatedData['status'],
                'reason' => $validatedData['reason'] ?? null,
                'transfer_proof' => $transferProofPath,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $approval
            ], 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $approval = Approval::findOrFail($id);
        return response()->json($approval);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approval $approval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approval $approval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approval $approval)
    {
        //
    }
}
