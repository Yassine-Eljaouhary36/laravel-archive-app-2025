<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateBoxRequest;
use App\Models\Box;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller
{
    public function index(Request $request)
    {
        $boxes = Box::query()
            // Apply user-based filtering first
            ->when(!auth()->user()->hasRole(['admin', 'controller']), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            // Existing search filters
            ->when($request->box_number, function ($query, $box_number) {
                return $query->where('box_number', 'like', '%'.$box_number.'%');
            })
            ->when($request->year_of_judgment, function ($query, $year) {
                return $query->where('year_of_judgment', $year);
            })
            ->when($request->file_type, function ($query, $file_type) {
                return $query->where('file_type', $file_type);
            })
            // Additional features
            ->with(['user' => function($query) {  // Eager load user relationship
                $query->select('id', 'name');    // Only get necessary fields
            }])
            ->withCount('files')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('boxes.index', compact('boxes'));
    }

    public function create()
    {
        return view('boxes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'saving_base_number' => 'required|string|max:255',
            'file_type' => 'required|string|max:255',
            'year_of_judgment' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'files' => 'required|array',
            'files.*.file_number' => 'required|string|max:10',
            'files.*.symbol' => 'required|string|max:10',
            'files.*.year_of_opening' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'files.*.judgment_number' => 'nullable|string|max:10',
            'files.*.judgment_date' => 'required|date',
        ]);

        $max = Box::max('box_number');
        $next = is_numeric($max) ? $max + 1 : 1;

        $box = Box::create([
            'saving_base_number' => $validated['saving_base_number'],
            'box_number' => $next,
            'file_type' => $validated['file_type'],
            'year_of_judgment' => $validated['year_of_judgment'],
            'total_files' => count($validated['files']),
            'user_id' => auth()->id(), // Associate with current user
        ]);

        // Add order to each file
        $order = 1;
        foreach ($validated['files'] as $fileData) {
            $fileData['order'] = $order++;
            $box->files()->create($fileData);
        }

        return redirect()->route('boxes.index')
            ->with('success', "Box #{$next} and files created successfully.");

    }

    public function show(Box $box)
    {
        $this->authorize('show', $box);
        $files = $box->files()
                ->select('id', 'box_id', 'file_number', 'symbol', 'year_of_opening', 'judgment_number', 'judgment_date')
                ->paginate(10);

        return view('boxes.show', compact('box', 'files'));
    }

    
    public function edit(Box $box)
    {
        $this->authorize('update', $box);
        if ($box->isValidated()) {
            abort(403, 'Validated boxes cannot be modified');
        }
        $box->load('files');
        return view('boxes.edit', compact('box'));
    }


    public function update(Request $request, Box $box)
    {
        $this->authorize('update', $box);
        if ($box->isValidated()) {
            abort(403, 'Validated boxes cannot be modified');
        }
        $validated = $request->validate([
            'saving_base_number' => 'required|string|max:255',
            'file_type' => 'required|string|max:255',
            'year_of_judgment' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'files' => 'required|array',
            'files.*.id' => 'nullable|integer|exists:files,id',
            'files.*.file_number' => 'required|string|max:255',
            'files.*.symbol' => 'required|string|max:255',
            'files.*.year_of_opening' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'files.*.judgment_number' => 'nullable|string|max:255',
            'files.*.judgment_date' => 'required|date',
        ]);
        
        DB::transaction(function () use ($validated, $box) {
            // Update box information
            $box->update([
                'saving_base_number' => $validated['saving_base_number'],
                'file_type' => $validated['file_type'],
                'year_of_judgment' => $validated['year_of_judgment'],
            ]);

            // Handle files
            if (isset($validated['files'])) {
                $existingFileIds = [];

                // Get the current maximum order value for this box's files
                $maxOrder = $box->files()->max('order') ?? 0;
                $orderCounter = $maxOrder + 1; // Start counting from the next value
                
                foreach ($validated['files'] as $fileData) {
                    if (array_key_exists('id', $fileData)) {
                        // Update existing file
                        $file = File::find($fileData['id']);
                        $file->update([
                            'file_number' => $fileData['file_number'],
                            'symbol' => $fileData['symbol'],
                            'year_of_opening' => $fileData['year_of_opening'],
                            'judgment_number' => $fileData['judgment_number'],
                            'judgment_date' => $fileData['judgment_date'],
                        ]);
                        $existingFileIds[] = $file->id;
                    } else {
                        // Create new file
                        $newFile = $box->files()->create([
                            'file_number' => $fileData['file_number'],
                            'symbol' => $fileData['symbol'],
                            'year_of_opening' => $fileData['year_of_opening'],
                            'judgment_number' => $fileData['judgment_number'],
                            'judgment_date' => $fileData['judgment_date'],
                            'order' => $orderCounter++,
                        ]);
                        $existingFileIds[] = $newFile->id;
                    }
                }
                
                // Delete files that were removed
                $box->files()->whereNotIn('id', $existingFileIds)->delete();
            } else {
                // If no files were submitted, delete all existing files
                $box->files()->delete();
            }
        });

        return redirect()->route('boxes.index')->with('success', 'Box updated successfully!');
    }

    // In BoxController.php
    public function validateBox(ValidateBoxRequest $request, Box $box)
    {
        if ($box->isValidated()) {
            return back()->with('error', 'This box is already validated');
        }

        DB::transaction(function () use ($request, $box) {
            $box->update([
                'validated_by' => $request->validated ? auth()->id() : null,
                'validated_at' => $request->validated ? now() : null,
            ]);
        });

        $message = $request->validated 
            ? 'Box validated successfully' 
            : 'Box validation removed';
        
        return back()->with('success', $message);
    }
}
