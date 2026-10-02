<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('ProductModel');
        $this->startSession();
    }

    public function index()
    {
        $data = [
            'products' => $this->ProductModel->getAllProducts(),
            'notice' => trim($_GET['notice'] ?? ''),
            'username' => $_SESSION['auth_user']['username'] ?? 'User'
        ];

        $this->call->view('products/index', $data);
    }

    public function create()
    {
        $this->showForm('create', [], []);
    }

    public function store()
    {
        $input = $this->productInput();
        $errors = $this->validateProduct($input);

        if (!empty($errors)) {
            $this->showForm('create', $input, $errors);
            return;
        }

        $this->ProductModel->createProduct($input);
        redirect('products?notice=created');
    }

    public function edit($id)
    {
        $product = $this->ProductModel->getProduct($id);

        if (!$product) {
            redirect('products?notice=not-found');
            return;
        }

        $this->showForm('edit', $product, [], (int) $id);
    }

    public function update($id)
    {
        $product = $this->ProductModel->getProduct($id);

        if (!$product) {
            redirect('products?notice=not-found');
            return;
        }

        $input = $this->productInput();
        $errors = $this->validateProduct($input);

        if (!empty($errors)) {
            $input['id'] = (int) $id;
            $this->showForm('edit', $input, $errors, (int) $id);
            return;
        }

        $this->ProductModel->updateProduct($id, $input);
        redirect('products?notice=updated');
    }

    public function confirmDelete($id)
    {
        $product = $this->ProductModel->getProduct($id);

        if (!$product) {
            redirect('products?notice=not-found');
            return;
        }

        $this->call->view('products/delete', ['product' => $product]);
    }

    public function delete($id)
    {
        $product = $this->ProductModel->getProduct($id);

        if (!$product) {
            redirect('products?notice=not-found');
            return;
        }

        $this->ProductModel->deleteProduct($id);
        redirect('products?notice=deleted');
    }

    private function productInput()
    {
        return [
            'product_name' => trim($_POST['product_name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => trim($_POST['price'] ?? ''),
            'quantity' => trim($_POST['quantity'] ?? '')
        ];
    }

    private function validateProduct(array $input)
    {
        $errors = [];

        if ($input['product_name'] === '' || strlen($input['product_name']) > 100) {
            $errors['product_name'] = 'Enter a product name up to 100 characters.';
        }

        if ($input['description'] === '') {
            $errors['description'] = 'Enter a product description.';
        }

        if ($input['price'] === '' || !is_numeric($input['price']) || (float) $input['price'] < 0) {
            $errors['price'] = 'Enter a valid price of 0 or higher.';
        }

        if ($input['quantity'] === '' || filter_var($input['quantity'], FILTER_VALIDATE_INT) === false || (int) $input['quantity'] < 0) {
            $errors['quantity'] = 'Enter a whole-number quantity of 0 or higher.';
        }

        return $errors;
    }

    private function showForm($mode, array $product, array $errors, $id = null)
    {
        $this->call->view('products/form', [
            'mode' => $mode,
            'product' => $product,
            'errors' => $errors,
            'id' => $id
        ]);
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieName = config_item('sess_cookie_name') ?: 'LLSession';
            session_name($cookieName);
            session_start();
        }
    }
}
