<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Lab6ApiController extends Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        
        header('Cache-Control: no-store');
        header('Vary: Origin');
        
        $this->api->rate_limit();
    }

    public function preflight()
    {
        $this->api->respond([], 204);
    }

    public function health()
    {
        $this->api->respond(['status' => 'ok']);
    }

    /* ==========================================
       AUTHENTICATION & USER METHODS
       ========================================== */

    public function login()
    {
        $this->api->rate_limit('login_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 10, 60);

        $v = $this->input();
        $n = $v['username'] ?? '';
        $p = $v['password'] ?? '';

        if (!is_string($n) || !is_string($p) || trim($n) === '' || $p === '') {
            $this->api->respond_error('Username and password are required.', 422);
        }

        $u = $this->db->raw('SELECT * FROM users WHERE username = ? LIMIT 1', [trim($n)])
                      ->fetch(PDO::FETCH_ASSOC);

        if (!$u || !password_verify($p, $u['password'] ?? '')) {
            $this->api->respond_error('Invalid username or password.', 401);
        }

        $t = $this->api->issue_tokens(['id' => $u['id'], 'role' => $u['role']]);

        $this->api->respond([
            'user'   => $this->pub($u),
            'tokens' => $t
        ]);
    }

    public function me()
    {
        $a = $this->auth();
        $u = $this->db->raw('SELECT id, username, role FROM users WHERE id = ?', [$a['sub']])
                      ->fetch(PDO::FETCH_ASSOC);

        $this->api->respond(['user' => $this->pub($u)]);
    }

    public function refresh()
    {
        $v = $this->input();

        if (!is_string($v['refresh_token'] ?? null)) {
            $this->api->respond_error('Refresh token is required.', 422);
        }

        $this->api->refresh_access_token($v['refresh_token']);
    }

    public function logout()
    {
        $a = $this->auth();
        $this->db->raw(
            'DELETE FROM refresh_tokens WHERE user_id = ? AND jti = ?', 
            [$a['sub'], $a['sid']]
        );

        $this->api->respond(['message' => 'Logged out.']);
    }

    /* ==========================================
       PRODUCT CRUD METHODS
       ========================================== */

    public function products()
    {
        $this->auth();
        $v = $this->db->raw('SELECT * FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

        $this->api->respond(['products' => $v]);
    }

    public function show($id)
    {
        $this->auth();
        $this->api->respond(['product' => $this->product($id)]);
    }

    public function create()
    {
        $this->auth();
        $d = $this->valid($this->input());

        $this->db->raw(
            'INSERT INTO products (product_name, description, price, quantity) VALUES (?, ?, ?, ?)',
            array_values($d)
        );

        $id = $this->db->raw('SELECT LAST_INSERT_ID() AS id')->fetch(PDO::FETCH_ASSOC)['id'];

        $this->api->respond([
            'message' => 'Product added.',
            'product' => $this->product($id)
        ], 201);
    }

    public function update($id)
    {
        $this->auth();
        $this->product($id);

        $isPatch = ($_SERVER['REQUEST_METHOD'] ?? '') === 'PATCH';
        $d = $this->valid($this->input(), $isPatch);

        $c = implode(', ', array_map(fn($k) => $k . ' = ?', array_keys($d)));
        $this->db->raw(
            'UPDATE products SET ' . $c . ' WHERE id = ?',
            [...array_values($d), (int)$id]
        );

        $this->api->respond([
            'message' => 'Product updated.',
            'product' => $this->product($id)
        ]);
    }

    public function delete($id)
    {
        $this->auth();
        $this->product($id);

        $this->db->raw('DELETE FROM products WHERE id = ?', [(int)$id]);

        $this->api->respond(['message' => 'Product deleted.']);
    }

    /* ==========================================
       HELPER & VALIDATION METHODS
       ========================================== */

    private function input()
    {
        $v = json_decode(file_get_contents('php://input'), true);

        if (!is_array($v) || ($v !== [] && array_is_list($v))) {
            $this->api->respond_error('Send a JSON object.', 400);
        }

        return $v;
    }

    private function pub($u)
    {
        return [
            'id'       => (int)$u['id'],
            'username' => $u['username'],
            'role'     => $u['role']
        ];
    }

    private function auth()
    {
        $a = $this->api->require_jwt();

        $s = $this->db->raw(
            'SELECT id FROM refresh_tokens WHERE user_id = ? AND jti = ? AND expires_at > NOW() LIMIT 1',
            [$a['sub'], $a['sid'] ?? '']
        )->fetch(PDO::FETCH_ASSOC);

        if (!$s) {
            $this->api->respond_error('Session expired. Please log in again.', 401);
        }

        return $a;
    }

    private function product($id)
    {
        $p = $this->db->raw('SELECT * FROM products WHERE id = ?', [(int)$id])->fetch(PDO::FETCH_ASSOC);

        if (!$p) {
            $this->api->respond_error('Product not found.', 404);
        }

        return $p;
    }

    private function valid($v, $partial = false)
    {
        $r = [];
        $e = [];

        foreach (['product_name', 'description', 'price', 'quantity'] as $k) {
            if (!array_key_exists($k, $v)) {
                if (!$partial) {
                    $e[$k] = 'Required.';
                }
                continue;
            }

            $x = $v[$k];

            if ($k === 'product_name') {
                if (!is_string($x) || trim($x) === '' || strlen(trim($x)) > 100) {
                    $e[$k] = 'Use 1 to 100 characters.';
                } else {
                    $r[$k] = trim($x);
                }
            } elseif ($k === 'description') {
                if (!is_string($x) || strlen($x) > 65535) {
                    $e[$k] = 'Description is too long.';
                } else {
                    $r[$k] = trim($x);
                }
            } elseif ($k === 'price') {
                if ((!is_string($x) && !is_numeric($x)) || !preg_match('/^\d{1,8}(\.\d{1,2})?$/D', (string)$x)) {
                    $e[$k] = 'Enter a nonnegative price with up to 2 decimals.';
                } else {
                    $r[$k] = number_format((float)$x, 2, '.', '');
                }
            } else {
                // quantity
                if ((!is_string($x) && !is_int($x)) || !preg_match('/^\d+$/D', (string)$x) || (float)$x > 2147483647) {
                    $e[$k] = 'Enter a valid whole quantity.';
                } else {
                    $r[$k] = (int)$x;
                }
            }
        }

        if ($e || !$r) {
            $this->api->respond([
                'error'  => 'Check product fields.',
                'errors' => $e
            ], 422);
        }

        return $r;
    }
}
