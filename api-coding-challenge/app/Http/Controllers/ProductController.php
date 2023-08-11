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
    public function uploadPhoto($photoData, $productName) {
        $imageMime = explode(';', $photoData)[0];
        $imageMime = str_replace('data:image/', '', $imageMime);
        $image = str_replace("data:image/$imageMime;base64", '', $photoData);        
        $image =  base64_decode($image);
        $fileName = $productName.'-'.time().'.png';
        $filepath = public_path('storage/products/' . $fileName);
        file_put_contents($filepath, $image);

        return $fileName;
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required',
            ]);
            if ($request->input('photo')) {
                $fileName = $this->uploadPhoto($request->input('photo'), $request->input('name'));
                $data = $request->all();
                $data['photo'] = $fileName;
            }
            $product = $this->productRepository->create($data);

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
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required',
            ]);
            $data = $request->all();
            if ($request->input('photo')) {
                $fileName = $this->uploadPhoto($request->input('photo'), $request->input('name'));
                $data = $request->all();
                $data['photo'] = $fileName;
            }
            $product = $this->productRepository->updateData($id, $data);

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
