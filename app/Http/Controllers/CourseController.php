<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentClass;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:students_classes,id', // Validate that class_id is an integer and exists in the student_classes table
            'img' => 'required|string', // Ensure the img is a base64 string
            'video' => 'nullable|file|mimes:mp4,mkv,avi,mov|max:524288',
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
                $imageName = 'uploads/courses/' . uniqid() . '.' . $imageExtension[1];

                // Store the image
                \Storage::disk('public')->put($imageName, base64_decode($image));
            } else {
                // Return an error if the base64 string format is incorrect
                return response()->json(['error' => 'Invalid base64 image format'], 400);
            }
        }

        // Handle file upload
        $videoName = null;
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = 'demos/' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoFile->storeAs('public', $videoName);  // Store in 'public/videos'
        }

        $student_class = StudentClass::find($request->class_id);

        // Create the course record in the database
        $course = Course::create([
            'name' => $request->input('name'),
            'class_id' => $request->input('class_id'),
            'img' => $imageName, // Store the image path
            'type' => $student_class->name,
            'demo' => $videoName,
        ]);

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 200);
    }

    public function getCourses(Request $request)
{
    $courses = Course::all()->map(function ($course) {
        return [
            'id' => $course->id,
            'name' => $course->name,
            'video' => $course->demo,
            'img' => $course->img,
            'class_id' => $course->class_id,
            'type' => $course->type,
        ];
    });

    return response()->json($courses);
}

    public function destroy(Request $request)
    {
        try {
            $course = Course::findOrFail($request->id);
            $course->delete();
            return response()->json(["message" => "course deleted successfully"]);

        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);

        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:courses,id', // Validate that id is an integer and exists in the courses table
            'name' => 'nullable|string|max:255',
            'img' => 'nullable|string', // Ensure the img is a base64 string if provided
            'video' => 'sometimes|file|mimes:mp4,mkv,avi,mov|max:524288', // Optional
        ]);

        $course = Course::findOrFail($request->id);

        $imageName = $course->img; // Keep the current image name if no new image is provided
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
                $newImageName = 'uploads/courses/' . uniqid() . '.' . $imageExtension[1];

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

        // Handle file upload if a new video is provided
        $videoName = $course->demo; // Keep the old video by default
        if ($request->hasFile('video')) {
            // Delete the old video if it exists
            if ($course->demo && \Storage::exists('public/' . $course->demo)) {
                \Storage::delete('public/' . $course->demo);
            }

            // Upload the new video
            $videoFile = $request->file('video');
            $videoName = 'demos/' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoFile->storeAs('public', $videoName);  // Store in 'public/videos'
        }

        // Update the course record in the database
        $course->update([
            'name' => $request->input('name') ?? $course->name, // Use the old name if a new one is not provided
            'img' => $imageName, // Update the image path if a new image is provided
            'demo' => $videoName,
        ]);

        return response()->json(['message' => 'Course updated successfully', 'course' => $course], 200);
    }


    public function getCourseDemo(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if the course has a demo file
            if (!$course->demo || empty($course->demo)) {
                return response()->json(["error" => "No demo file available for this course"], 404);
            }

            // Get the file path from public storage
            $filePath = public_path('storage/' . $course->demo);

            // Check if file exists
            if (!file_exists($filePath)) {
                return response()->json(["error" => "Demo file not found"], 404);
            }

            // Return the path as a JSON response
            $fileUrl = asset('storage/' . $course->demo);
            return response()->json(["video" => $fileUrl]);

        } catch (Exception $ex) {
            \Log::error($ex);
            return response()->json(["error" => $ex->getMessage()], 500);
        }
    }

    public function saveSubjectsOrder(Request $request, $course_id)
    {
        try {
            $course = Course::find($course_id);
            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            $subjects = $request->input('order');
            if (!is_array($subjects)) {
                return response()->json(['error' => 'Invalid subjects format'], 400);
            }

            foreach ($subjects as $index => $subjectData) {
                $subject = $course->subjects()->where('id', $subjectData)->first();
                if ($subject) {
                    $subject->update(['order' => $index + 1]);
                }
            }

            return response()->json(['message' => 'Subjects order updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the order'], 500);
        }
    }


}
