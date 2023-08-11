<?php 

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $product;

    public function getAll() {
        return $this->product::paginate(3);
    }

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function create($data) {
        return $this->product::create($data);
    }

    public function update($id, $data) {
        $product = $this->product::findOrFail($id);
        return $product::update($data);
    }

    public function getById($id) {
        return $this->product::findOrFail($id);
    }

    public function delete($id) {
        return $this->product::delete($id);
    }
}