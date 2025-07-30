<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Transfert;
use App\Models\Tribunal;
use Illuminate\Http\Request;

class TransfertController extends Controller
{
    public function index()
    {
        $transferts = Transfert::with('tribunal')->latest()->paginate(10);
        return view('admin.transferts.index', compact('transferts'));
    }

    public function create()
    {
        $tribunaux = Tribunal::where('active', true)->get();
        return view('admin.transferts.create', compact('tribunaux'));
    }

    public function getBoxes(Request $request)
    {
        $request->validate(['tribunal_id' => 'required|exists:tribunaux,id']);
        
        $boxes = Box::where('tribunal_id', $request->tribunal_id)
            ->whereNull('transfert_id')
            ->get();
            
        return response()->json($boxes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tribunal_id' => 'required|exists:tribunaux,id',
            'box_ids' => 'required|array',
            'box_ids.*' => 'exists:boxes,id',
            'transfert_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Generate transfert number
        $tribunal = Tribunal::find($request->tribunal_id);
        $lastTransfert = Transfert::where('tribunal_id', $tribunal->id)
            ->orderBy('transfert_number', 'desc')
            ->first();
            
        $nextNumber = $lastTransfert ? $lastTransfert->transfert_number + 1 : 1;

        // Create transfert
        $transfert = Transfert::create([
            'transfert_number' => $nextNumber,
            'tribunal_id' => $tribunal->id,
            'transfert_date' => $request->transfert_date,
            'notes' => $request->notes
        ]);

        // Update boxes
        Box::whereIn('id', $request->box_ids)
            ->update(['transfert_id' => $transfert->id]);

        return redirect()->route('admin.transferts.index')
            ->with('success', 'تم إنشاء التحويل بنجاح');
    }

    public function show(Transfert $transfert)
    {
        $transfert->load(['boxes', 'tribunal']);
        return view('admin.transferts.show', compact('transfert'));
    }


    // Add these methods to your TransfertController

    public function edit(Transfert $transfert)
    {
        $tribunaux = Tribunal::where('active', true)->get();
        $currentBoxes = $transfert->boxes()->pluck('id')->toArray();
        
        // Get available boxes from the same tribunal that aren't already in another transfert
        $availableBoxes = Box::where('tribunal_id', $transfert->tribunal_id)
            ->where(function($query) use ($currentBoxes) {
                $query->whereNull('transfert_id')
                    ->orWhereIn('id', $currentBoxes);
            })
            ->get();

        return view('admin.transferts.edit', compact('transfert', 'tribunaux', 'availableBoxes', 'currentBoxes'));
    }

    public function update(Request $request, Transfert $transfert)
    {
        $request->validate([
            'box_ids' => 'required|array',
            'box_ids.*' => 'exists:boxes,id',
            'transfert_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // First remove all boxes from this transfert
        Box::where('transfert_id', $transfert->id)->update(['transfert_id' => null]);
        
        // Then assign the selected boxes
        Box::whereIn('id', $request->box_ids)
            ->update(['transfert_id' => $transfert->id]);

        // Update transfert details
        $transfert->update([
            'transfert_date' => $request->transfert_date,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.transferts.show', $transfert)
            ->with('success', 'تم تحديث التحويل بنجاح');
    }

    
}