<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'string|max:2000',
            'img' => 'required|string', // Ensure the img is a base64 string
        ]);

        // Decode the base64 image
        $base64Image = $request->input('img');
        if (!str_starts_with($base64Image, 'data:image/')) {
            $base64Image = 'data:image/jpeg;base64,' . $base64Image; // Assuming it's a JPEG
        }


        $imageName = null;
        if ($base64Image) {
            // Match the base64 string to extract the image extension
            if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                // Strip the base64 metadata and decode the image
                $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'uploads/teachers/' . uniqid() . '.' . $imageExtension[1];

                // Store the image
                \Storage::disk('public')->put($imageName, base64_decode($image));
            } else {
                // Return an error if the base64 string format is incorrect
                return response()->json(['error' => 'Invalid base64 image format'], 400);
            }
        }

        // Create the course record in the database
        $teacher = Teacher::create([
            'name' => $request->input('name'),
            'spiecialization' => $request->input('specialization'),
            'bio' => $request->input("bio"),
            'img' => $imageName, // Store the image path
        ]);

        return response()->json(['message' => 'Teacher created successfully', 'teacher' => $teacher], 200);
    }

    public function getTeachers(Request $request)
    {
        $teachers = Teacher::all();
        return response()->json($teachers, 200);
    }

    public function deleteTeacher($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        if ($teacher) {
            $teacher->delete();
            return response()->json(['message' => 'teacher deleted successfully.'], 200);
        } else {
            return response()->json(['error' => 'teacher not found'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validate request inputs
            $request->validate([
                'name' => 'required|string|max:255',
                'specialization' => 'required|string|max:255',
                'bio' => 'string|max:2000',
                'img' => 'sometimes|string', // Ensure the img is a base64 string
            ]);
            $teacher = Teacher::findOrFail($request->id);

            if ($request->img) {
                // Decode the base64 image
                $base64Image = $request->input('img');
                if (!str_starts_with($base64Image, 'data:image/')) {
                    $base64Image = 'data:image/jpeg;base64,' . $base64Image; // Assuming it's a JPEG
                }
                $imageName = null;
                if ($base64Image) {
                    // Match the base64 string to extract the image extension
                    if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                        // Strip the base64 metadata and decode the image
                        $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                        $image = str_replace(' ', '+', $image);
                        $imageName = 'uploads/lessons/' . uniqid() . '.' . $imageExtension[1];

                        // Store the image
                        \Storage::disk('public')->put($imageName, base64_decode($image));
                    } else {
                        // Return an error if the base64 string format is incorrect
                        return response()->json(['error' => 'Invalid base64 image format'], 400);
                    }
                }
            } else {
                $imageName = $teacher->img;
            }
            // Update lesson record (excluding subject_id)
            $teacher->update([
                'name' => $request->input('name', $teacher->name), // Use existing value if not provided
                'spiecialization' => $request->input('specialization', $teacher->specialization), // Use existing value if not provided
                'bio' => $request->input('bio', $teacher->bio),
                'img' => $imageName,
            ]);

            // Return response
            return response()->json(['message' => 'teacher updated successfully', 'teacher' => $teacher], 200);

        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

}
