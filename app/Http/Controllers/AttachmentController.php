<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate request inputs
            $request->validate([
                'name' => 'required|string|max:255',
                'attachment' => 'required|file',
                'lesson_id' => 'required|exists:lessons,id',
            ]);

            // Handle file upload
            $videoName = null;
            if ($request->hasFile('attachment')) {
                $videoFile = $request->file('attachment');
                $videoName = 'attachments/' . uniqid() . '-' . $request->name;
                $videoFile->storeAs('public', $videoName);  // Store in 'public/videos'
            }

            // Create lesson record
            $lesson = Attachment::create([
                'name' => $request->input('name'),
                'path' => $videoName,
                'lesson_id' => $request->input('lesson_id'),
            ]);

            // Return response
            return response()->json(['message' => 'Attachment created successfully', 'attachment' => $lesson], 200);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating lesson: ' . $e->getMessage());

            // Return a response indicating failure
            return response()->json(['message' => 'An error occurred while creating the lesson'], 500);
        }
    }


    /**
     * Get the file extension based on the MIME type.
     *
     * @param string $mimeType
     * @return string|null
     */
    private function getFileExtension(string $mimeType): ?string
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'text/plain' => 'txt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/zip' => 'zip',
        ];

        return $mimeMap[$mimeType] ?? null;
    }


    // List all attachments for a lesson
    public function index($lessonId)
    {
        $attachments = Attachment::where('lesson_id', $lessonId)->get();
        return response()->json($attachments);
    }

    // Delete an attachment by ID
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Delete the file from storage
        Storage::delete($attachment->path);

        // Delete the record from the database
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully']);
    }
}
