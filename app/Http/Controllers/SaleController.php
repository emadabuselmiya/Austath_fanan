<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    //

    public function getData(Request $request)
    {
        $sales = Sale::all();

        if ($sales->isEmpty()) {
            return response()->json(['images' => [], 'description' => null]);
        }

        $images = $sales->pluck('img')->filter()->toArray();
        $description = $sales->first()->description;

        return response()->json([
            'images' => $images,
            'description' => $description
        ]);
    }
    // public function getData(Request $request)
    // {
    //     $sale = Sale::first();
    //     return response()->json($sale);
    // }
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:4000',
            'images' => 'required|array',
            'images.*' => 'string', // Each image should be a base64 string
        ]);

        $description = $request->input('description');
        $images = $request->input('images');
        $createdSales = [];

        foreach ($images as $base64Image) {
            if (!str_starts_with($base64Image, 'data:image/')) {
                $base64Image = 'data:image/jpeg;base64,' . $base64Image;
            }

            $imageName = null;
            if ($base64Image) {
                if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                    $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = 'uploads/sales/' . uniqid() . '.' . $imageExtension[1];

                    \Storage::disk('public')->put($imageName, base64_decode($image));
                } else {
                    return response()->json(['error' => 'Invalid base64 image format'], 400);
                }
            }

            $sale = Sale::create([
                'description' => $description,
                'img' => $imageName,
            ]);

            $createdSales[] = $sale;
        }

        return response()->json(['message' => 'Sales created successfully', 'sales' => $createdSales], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:4000',
            'images' => 'required|array',
            'images.*' => 'string', // Each image should be a base64 string
        ]);

        // Delete all existing sales and their images
        $existingSales = Sale::all();
        foreach ($existingSales as $existingSale) {
            if ($existingSale->img) {
                \Storage::disk('public')->delete($existingSale->img);
            }
        }
        Sale::truncate();

        $description = $request->input('description');
        $images = $request->input('images');
        $createdSales = [];

        foreach ($images as $base64Image) {
            if (!str_starts_with($base64Image, 'data:image/')) {
                $base64Image = 'data:image/jpeg;base64,' . $base64Image;
            }

            $imageName = null;
            if ($base64Image) {
                if (preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension)) {
                    $image = str_replace('data:image/' . $imageExtension[1] . ';base64,', '', $base64Image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = 'uploads/sales/' . uniqid() . '.' . $imageExtension[1];

                    \Storage::disk('public')->put($imageName, base64_decode($image));
                } else {
                    return response()->json(['error' => 'Invalid base64 image format'], 400);
                }
            }

            $sale = Sale::create([
                'description' => $description,
                'img' => $imageName,
            ]);

            $createdSales[] = $sale;
        }

        return response()->json(['message' => 'Sales updated successfully', 'sales' => $createdSales], 200);
    }

}
