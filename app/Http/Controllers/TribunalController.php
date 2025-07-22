<?php

namespace App\Http\Controllers;

use App\Models\Tribunal;
use Illuminate\Http\Request;

class TribunalController extends Controller
{
    public function index(Request $request)
    {
        $query = Tribunal::query();

        if ($request->filled('tribunal')) {
            $query->where('tribunal', 'like', '%'.$request->tribunal.'%');
        }
        if ($request->filled('circonscription_judiciaire')) {
            $query->where('circonscription_judiciaire', $request->circonscription_judiciaire);
        }
        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        $tribunaux = $query->paginate(10);

        return view('admin.tribunaux.index', compact('tribunaux'));
    }

    public function toggleActive(Request $request)
    {
        $tribunaux = Tribunal::whereIn('id', $request->ids)->get();

        foreach ($tribunaux as $tribunal) {
            $tribunal->active = !$tribunal->active;
            $tribunal->save();
        }

        return redirect()->back()->with('success', 'تحديث حالة المحكمة');
    }

}
