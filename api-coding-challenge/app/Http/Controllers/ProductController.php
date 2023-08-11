<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        try {
            $product = $this->productRepository->getAll();

            return response()->json([
                'data' => $product,
                'success' => true
            ]);  
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
    }
    public function uplaodPhoto($photo, $productName) {
        $fileName = $productName.time();
        $filepath = public_path('storage/products/' . $fileName);
        file_get_contents($filepath, $photo);

        return $fileName;
    }
    public function store(Request $request)
    {
        try {
            $this->uplaodPhoto($request->photo, $request->name);
            $product = $this->productRepository->create($request->all());

            return response()->json([
                'data' => $product,
                'success' => true
            ]);  
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = $this->productRepository->getById($id);

            return response()->json([
                'data' => $product,
                'success' => true
            ]);  
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = $this->productRepository->update($id, $request->all());

            return response()->json([
                'data' => $product,
                'success' => true
            ]);  
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = $this->productRepository->delete($id);

            return response()->json([
                'data' => $product,
                'success' => true
            ]);  
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
    }
}
