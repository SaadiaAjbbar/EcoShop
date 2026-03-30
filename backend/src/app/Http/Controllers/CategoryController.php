<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: "/categories",
        summary: "Get all categories",
        tags: ["categories"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of categories"
            )
        ]
    )]



    public function index()
    {
        return Category::all();
    }

    #[OA\Get(
        path: "/categories/{id}/products",
        summary: "Get products in a category",
        tags: ["categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of products in the category"
            )
        ]
    )]

    public function products($id)
    {
        return Category::with('products')->findOrFail($id);
    }

    #[OA\Post(
        path: "/categories",
        summary: "Create a new category",
        tags: ["categories"],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Electronics")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created"
            )
        ]
    )]

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json($category, 201);
    }

    #[OA\Put(
        path: "/categories/{id}",
        summary: "Update a category",
        tags: ["categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Electronics")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated"
            )
        ]
    )]

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);

        return response()->json($category);
    }

    #[OA\Delete(
        path: "/categories/{id}",
        summary: "Delete a category",
        tags: ["categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Category deleted"
            )
        ]
    )]

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }


}
