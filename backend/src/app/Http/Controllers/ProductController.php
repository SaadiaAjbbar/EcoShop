<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: "/products",
        summary: "Get all products",
        tags: ["products"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of products"
            )
        ]
    )]

    public function index()
    {
        return Product::with('category')->get();
    }

    #[OA\Get(
        path: "/products/{id}",
        summary: "Get a product by ID",
        tags: ["products"],
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
                description: "Product details"
            )
        ]
    )]
    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    #[OA\Post(
        path: "/products",
        summary: "Create a new product",
        tags: ["products"],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Product Name"),
                        new OA\Property(property: "price", type: "number", format: "float", example: 19.99),
                        new OA\Property(property: "stock", type: "integer", example: 100),
                        new OA\Property(property: "category_id", type: "integer", example: 1)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Product created"
            )
        ]
    )]

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    #[OA\Put(
        path: "/products/{id}",
        summary: "Update a product",
        tags: ["products"],
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
                        new OA\Property(property: "name", type: "string", example: "Product Name"),
                        new OA\Property(property: "price", type: "number", format: "float", example: 19.99),
                        new OA\Property(property: "stock", type: "integer", example: 100),
                        new OA\Property(property: "category_id", type: "integer", example: 1)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Product updated"
            )
        ]
    )]


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product);

    }

    #[OA\Delete(
        path: "/products/{id}",
        summary: "Delete a product",
        tags: ["products"],
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
                description: "Product deleted"
            )
        ]
    )]

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }



}
