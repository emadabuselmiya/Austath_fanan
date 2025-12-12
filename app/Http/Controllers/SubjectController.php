<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Subject;
use Exception;

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'course_id' => 'required|exists:courses,id',
        ]);

        $subject = Subject::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'course_id' => $request->input('course_id'),
        ]);

        return response()->json(['message' => 'Subject created successfully', 'subject' => $subject], 200);
    }

    public function getCourseSubjects($course_id)
    {
        $course = Course::with([
            'subjects' => function ($query) {
                $query->orderBy('order', 'asc'); // or 'desc' if needed
            }
        ])->find($course_id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        return response()->json($course->subjects, 200);
    }

    public function getSubjectLessons($subject_id)
    {
        $subject = Subject::with([
            'lessons' => function ($query) {
                $query->orderBy('order', 'asc'); // or 'desc' if needed
            }
        ])->find($subject_id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json($subject->lessons, 200);
    }


    public function delete($subject_id)
    {
        $subject = Subject::find($subject_id);
        if (!$subject) {
            return response()->json(['error' => 'subject not found.'], 404);
        }
        $subject->delete();
        return response()->json(['message' => 'subject deleted successfully.'], 200);
    }

    public function update(Request $request)
    {
        $subject = Subject::find($request->id);
        if (!$subject) {
            return response()->json([
                'error' => Err('
            ')
            ], 404);
        }

        $subject->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json(['message' => 'subject updated successfully'], 200);

    }

    public function destroy(Request $request)
    {
        $subject = Subject::find($request->id);
        if (!$subject) {

            return response()->json(['error' => "not found"], 404);
        }
        $subject->delete();
        return response()->json(["message" => "subject deleted successfully"], 200);
    }


    public function saveLessonsOrder(Request $request, $subject_id)
    {
        try {
            $subject = Subject::find($subject_id);
            if (!$subject) {
                return response()->json(['error' => 'Subject not found'], 404);
            }

            $lessons = $request->input('order');
            if (!is_array($lessons)) {
                return response()->json(['error' => 'Invalid lessons format'], 400);
            }

            foreach ($lessons as $index => $lessonData) {
                $lesson = $subject->lessons()->where('id', $lessonData)->first();
                if ($lesson) {
                    $lesson->update(['order' => $index + 1]);
                }
            }

            return response()->json(['message' => 'Lessons order updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the order'], 500);
        }
    }

}
