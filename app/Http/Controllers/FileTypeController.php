<?php

namespace App\Http\Controllers;

use App\Models\FileType;
use App\Models\SavingBase;
use Illuminate\Http\Request;

class FileTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = FileType::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        $fileTypes = $query->paginate(10);

        return view('admin.file-types.index', compact('fileTypes'));
    }

    public function toggleActive(Request $request)
    {
        $fileTypes = FileType::whereIn('id', $request->ids)->get();

        foreach ($fileTypes as $fileType) {
            $fileType->active = !$fileType->active;
            $fileType->save();
        }

        return redirect()->route('admin.file-types.index')
            ->with('success', 'تم تحديث حالة أنواع الملفات بنجاح');
    }

    // Show create form
    public function create()
    {
        $savingBases = SavingBase::whereNull('file_type_id')->get(); // Only show unassigned ones
        return view('admin.file-types.create', compact('savingBases'));
    }

    // Store new file type
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:file_types,name',
            'saving_bases' => 'required|array|min:1',
            'saving_bases.*' => 'exists:saving_bases,id'
        ]);

        // Create the file type
        $fileType = FileType::create([
            'name' => $validated['name'],
            'active' => $request->has('active') ?? false,
        ]);

        // Update the file_type_id for selected saving bases
        SavingBase::whereIn('id', $validated['saving_bases'])
                ->update(['file_type_id' => $fileType->id]);

        return redirect()->route('admin.file-types.index')
            ->with('success', 'تم إنشاء نوع الملف بنجاح');
    }

    // Show edit form
    public function edit(FileType $fileType)
    {
        return view('admin.file-types.edit', compact('fileType'));
    }

    // Update file type
    public function update(Request $request, FileType $fileType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:file_types,name,'.$fileType->id,
            'saving_bases' => 'array',
            'saving_bases.*' => 'exists:saving_bases,id'
        ]);

        // Update file type
        $fileType->update([
            'name' => $validated['name'],
            'active' => $request->has('active') ?? false,
        ]);

        // Get current saving bases
        $currentSavingBases = $fileType->savingBases->pluck('id')->toArray();
        $newSavingBases = $validated['saving_bases'] ?? [];

        // Remove no longer selected
        $toRemove = array_diff($currentSavingBases, $newSavingBases);
        SavingBase::whereIn('id', $toRemove)->update(['file_type_id' => null]);

        // Add newly selected (only those not already assigned to another type)
        $toAdd = array_diff($newSavingBases, $currentSavingBases);
        SavingBase::whereIn('id', $toAdd)
                ->whereNull('file_type_id')
                ->update(['file_type_id' => $fileType->id]);

        return redirect()->route('admin.file-types.index')
            ->with('success', 'تم تحديث نوع الملف بنجاح');
    }


    // Delete file type
    public function destroy(FileType $fileType)
    {
        $fileType->delete();

        return redirect()->route('admin.file-types.index')
            ->with('success', 'تم حذف نوع الملف بنجاح');
    }

}
