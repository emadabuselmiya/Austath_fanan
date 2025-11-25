<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranches(Request $request)
    {


        return response()->json(Branch::all());

    }

    public function destroy(Request $request)
    {

        $branch = Branch::findOrFail($request->id);
        $branch->delete();
        return response()->json(["message" => "branch deleted successfully"], 200);

    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255', // Validate that class_id is an integer and exists in the student_classes table
            "address" => 'required|string|max:255',
            'img' => 'nullable|string', // Ensure the img is a base64 string
        ]);

        $branch = Branch::findOrFail($request->id);

        $imageName = null;
        $base64Image = $request->input('img');

        if ($base64Image) {
            if (!str_starts_with($base64Image, 'data:image/')) {
                $base64Image = 'data:image/jpeg;base64,' . $base64Image; // Assuming it's a JPEG
            }

            // Match the base64 string to extract the image extension
            if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                // Strip the base64 metadata and decode the image
                $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                $image = str_replace(' ', '+', $image);
                $newImageName = 'uploads/branches/' . uniqid() . '.' . $imageExtension[1];

                // Store the new image
                \Storage::disk('public')->put($newImageName, base64_decode($image));

                // Delete the old image if it exists
                if ($imageName && \Storage::disk('public')->exists($imageName)) {
                    \Storage::disk('public')->delete($imageName);
                }

                $imageName = $newImageName;
            } else {
                // Return an error if the base64 string format is incorrect
                return response()->json(['error' => 'Invalid base64 image format'], 400);
            }
        }

        // Update the course record in the database
        $branch->update([
            'name' => $request->name ?? $branch->name, // Use the old name if a new one is not provided
            'img' => $imageName, // Update the image path if a new image is provided
            'address' => $request->address ?? $branch->address, // Use the old name if a new one is not provided
            'mobile' => $request->mobile ?? $branch->mobile, // Use the old name if a new one is not provided

        ]);

        return response()->json(['message' => 'Branch updated successfully', 'branch' => $branch], 200);
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255', // Validate that class_id is an integer and exists in the student_classes table
            "address" => 'required|string|max:255',
            'img' => 'nullable|string', // Ensure the img is a base64 string
        ]);

        $imageName = null;
        if ($request->img != null) {
            // Decode the base64 image
            $base64Image = $request->input('img');
            if (!str_starts_with($base64Image, 'data:image/')) {
                $base64Image = 'data:image/jpeg;base64,' . $base64Image; // Assuming it's a JPEG
            }

            if ($base64Image) {
                // Match the base64 string to extract the image extension
                if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                    // Strip the base64 metadata and decode the image
                    $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = 'uploads/branches/' . uniqid() . '.' . $imageExtension[1];

                    // Store the image
                    \Storage::disk('public')->put($imageName, base64_decode($image));
                } else {
                    // Return an error if the base64 string format is incorrect
                    return response()->json(['error' => 'Invalid base64 image format'], 400);
                }
            }
        }
        // Create the branch record in the database
        $branch = Branch::create([
            'name' => $request->name,
            'address' => $request->address,
            'img' => $imageName, // Store the image path
            'mobile' => $request->mobile
        ]);

        return response()->json(['message' => 'Branch created successfully', 'branch' => $branch], 200);
    }
}
