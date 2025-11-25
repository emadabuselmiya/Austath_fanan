<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    public function getClasses()
    {
        $classes = StudentClass::all();
        return response()->json($classes, 200);
    }

    public function store(Request $request)
    {
        $class = StudentClass::create([
            "name" => $request->name,
        ]);

        return response()->json(['message' => 'class created succesfully.'], 200);
    }


    public function getCoursesForClass($class_id)
    {
        $courseClass = StudentClass::where('id', $class_id)->first();
        $courses = $courseClass->courses;
        $mappedCourses = $courses->sortBy('order')->values()->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'video' => $course->demo,
                'img' => $course->img,
                'class_id' => $course->class_id,
                'type' => $course->type,
            ];
        });
        return response()->json($mappedCourses, 200);
    }

    public function destroy(Request $request)
    {
        $class = StudentClass::findOrFail($request->id);
        $class->delete();
        return response()->json(["message" => "class deleted successfully"], 200);

    }

    public function update(Request $request)
    {
        $class = StudentClass::findOrFail($request->id);
        $class->name = $request->name;
        $class->save();

        return response()->json(['message' => 'class updated successfully.'], 200);
    }

    public function saveCoursesOrder(Request $request, $class_id)
    {
        try {
            $class = StudentClass::find($class_id);
            if (!$class) {
                return response()->json(['error' => 'Class not found'], 404);
            }

            $courses = $request->input('order');
            if (!is_array($courses)) {
                return response()->json(['error' => 'Invalid courses format'], 400);
            }

            foreach ($courses as $index => $courseData) {
                $course = $class->courses()->where('id', $courseData)->first();
                if ($course) {
                    $course->update(['order' => $index + 1]);
                }
            }

            return response()->json(['message' => 'Courses order updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the order'], 500);
        }
    }

}
