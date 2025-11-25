<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class lessonController extends Controller
{

    public function store(Request $request)
    {
        try {
            // Validate request inputs
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'video' => 'required|file|mimes:mp4,mkv,avi,mov|max:524288',
                'subject_id' => 'required|exists:subjects,id',
                'img' => 'required|string',
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
                    $imageName = 'uploads/lessons/' . uniqid() . '.' . $imageExtension[1];

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
                $videoName = 'videos/' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $videoFile->storeAs('public', $videoName);  // Store in 'public/videos'
            }

            // Create lesson record
            $lesson = Lesson::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'video' => $videoName,
                'subject_id' => $request->input('subject_id'),
                'img' => $imageName,
            ]);

            // Return response
            return response()->json(['message' => 'Lesson created successfully', 'lesson' => $lesson], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error creating lesson: ' . $e->getMessage());

            // Return a response indicating failure
            return response()->json(['message' => 'An error occurred while creating the lesson'], 500);
        }
    }



    public function getLesson($lesson_id)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthorized access. Please log in to view this lesson.'
            ], 403);
        }
        
        // Proceed with fetching the lesson if user is authenticated
        $lesson = Lesson::find($lesson_id);
        
        // Check if lesson exists
        if (!$lesson) {
            return response()->json([
                'message' => 'Lesson not found.'
            ], 404);
        }
        
        // Get the authenticated user
        $user = auth()->user();
        
        // Get the course ID associated with this lesson through subject
        $courseId = $lesson->subject->course_id;
        
        // Check if the user has an active subscription for this course
        $hasActiveCourse = $user->activeCourses()
            ->where('course_id', $courseId)
            ->where('status', 'active')  // Optional: add status check if applicable
            ->exists();
        
        if (!$hasActiveCourse) {
            return response()->json([
                'message' => 'You do not have access to this lesson. Please activate the required course.'
            ], 403);
        }
        
        return response()->json($lesson, 200);
    }


    public function deleteLesson($lesson_id)
    {
        try {
            // Find the lesson
            $lesson = Lesson::find($lesson_id);
            if (!$lesson) {
                return response()->json(['error' => 'Lesson not found.'], 404);
            }

            // Delete the video file from storage if it exists
            if ($lesson->video && Storage::exists('public/' . $lesson->video)) {
                Storage::delete('public/' . $lesson->video);
            }

            // Delete the lesson record
            $lesson->delete();

            // Return success response
            return response()->json(['message' => 'Lesson deleted successfully.'], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error deleting lesson: ' . $e->getMessage());

            // Return a response indicating failure
            return response()->json(['message' => 'An error occurred while deleting the lesson.'], 500);
        }
    }

    public function updateLesson(Request $request)
    {
        try {
            // Validate request inputs
            $request->validate([
                'id' => 'required|exists:lessons,id', // Ensure the lesson exists
                'name' => 'sometimes|string|max:255', // Optional
                'description' => 'sometimes|string|max:1000', // Optional
                'video' => 'sometimes|file|mimes:mp4,mkv,avi,mov|max:524288', // Optional
                'img' => 'sometimes|string',
            ]);

            // Find the lesson
            $lesson = Lesson::find($request->id);
            if (!$lesson) {
                return response()->json(['error' => 'Lesson not found'], 404);
            }
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
                $imageName = $lesson->img;
            }


            // Handle file upload if a new video is provided
            $videoName = $lesson->video; // Keep the old video by default
            if ($request->hasFile('video')) {
                // Delete the old video if it exists
                if ($lesson->video && Storage::exists('public/' . $lesson->video)) {
                    Storage::delete('public/' . $lesson->video);
                }

                // Upload the new video
                $videoFile = $request->file('video');
                $videoName = 'videos/' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $videoFile->storeAs('public', $videoName);  // Store in 'public/videos'
            }

            // Update lesson record (excluding subject_id)
            $lesson->update([
                'name' => $request->input('name', $lesson->name), // Use existing value if not provided
                'description' => $request->input('description', $lesson->description), // Use existing value if not provided
                'video' => $videoName, // Update video path if a new video was uploaded
                'img' => $imageName,
            ]);

            // Return response
            return response()->json(['message' => 'Lesson updated successfully', 'lesson' => $lesson], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating lesson: ' . $e->getMessage());

            // Return a response indicating failure
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    

public function moveLesson(Request $request)
{
    // Validate request inputs
    $request->validate([
        'lesson_id' => 'required|exists:lessons,id',
        'subject_id' => 'required|exists:subjects,id',
        'operation' => 'required|in:c,v', // c = copy, v = cut
    ]);

    try {
        // Start DB transaction
        return DB::transaction(function () use ($request) {
            $lesson = Lesson::findOrFail($request->lesson_id);
            $subject = Subject::findOrFail($request->subject_id);

            if ($request->operation == 'c') {
                // Copy: create new lesson with new subject_id
                $newLesson = $lesson->replicate();
                $newLesson->subject_id = $subject->id;
                $newLesson->save();

                return response()->json([
                    'message' => 'Lesson copied successfully.',
                    'lesson' => $newLesson
                ], 201);
            } 
            elseif ($request->operation == 'v') {
                // Cut: copy then delete original
                $newLesson = $lesson->replicate();
                $newLesson->subject_id = $subject->id;
                $newLesson->save();

                // Delete original lesson
                $lesson->delete();

                return response()->json([
                    'message' => 'Lesson moved (cut) successfully.',
                    'lesson' => $newLesson
                ], 200);
            }
        });
    } catch (\Exception $ex) {
        \Log::error($ex);
        return response()->json(["error" => $ex->getMessage()], 500);
    }
}

}
