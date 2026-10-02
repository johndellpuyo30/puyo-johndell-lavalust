USE mydb;

CREATE TABLE auth_users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_auth_users_username (username)
);

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO auth_users (username, password) VALUES
('admin', '$2y$12$VLMz922I7G1sFigFDL3Ymuk0IlCMR2a3TL6Y8YBMVKhhg9s.wrfeS');

INSERT INTO products (product_name, description, price, quantity) VALUES
('Wireless Mouse', 'Compact wireless mouse for everyday use.', 599.00, 15),
('Mechanical Keyboard', 'USB mechanical keyboard with durable switches.', 1899.00, 8),
('Laptop Stand', 'Adjustable aluminum stand for laptops.', 899.00, 12);
