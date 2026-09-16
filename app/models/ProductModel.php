<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';
    protected $fillable = ['product_name', 'description', 'price', 'quantity'];

    public function getAllProducts()
    {
        return $this->all();
    }

    public function getProduct($id)
    {
        return $this->find((int) $id);
    }

    public function createProduct(array $data)
    {
        return $this->insert($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->update((int) $id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->delete((int) $id);
    }
}
