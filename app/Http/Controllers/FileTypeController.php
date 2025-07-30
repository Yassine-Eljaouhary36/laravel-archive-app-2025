<?php

namespace App\Http\Controllers;

use App\Models\FileType;
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
        return view('admin.file-types.create');
    }

    // Store new file type
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:file_types,name',
        ]);

        FileType::create([
            'name' => $request->name,
            'active' => $request->has('active'),
        ]);

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
        $request->validate([
            'name' => 'required|string|max:255|unique:file_types,name,'.$fileType->id,
        ]);

        $fileType->update([
            'name' => $request->name,
            'active' => $request->has('active'),
        ]);

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
