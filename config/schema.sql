use bbf9n1hmqktwbjmir6hi;

-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    -- Campos nuevos para verificación
    verification_code VARCHAR(10) DEFAULT NULL,
    verified TINYINT(1) DEFAULT 0,

    role ENUM('customer','admin','editor','support') DEFAULT 'customer',
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================
-- CLIENTS (extiende users)
-- =========================
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    document_number VARCHAR(50) NULL, -- DNI, RUC, etc.
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- CATEGORIES
-- =========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- PRODUCTS
-- =========================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    price_cents INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'PEN',
    stock INT DEFAULT 0,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_main TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ORDERS
-- =========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_cents INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'PEN',
    status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_cents INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- =========================
-- PAYMENTS
-- =========================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method ENUM('transfer','yape','paypal') NOT NULL,
    amount_cents INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'PEN',
    voucher_path VARCHAR(255) NULL,
    provider_txn_id VARCHAR(255) NULL,
    status ENUM('pending','confirmed','failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ÍNDICES
-- =========================
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_payments_order ON payments(order_id);


-- ⚠️ Ejecutar primero este bloque para limpiar todo
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;

-- Usuarios
INSERT INTO users (name, email, password, role) VALUES
('Admin Master', 'admin@shop.com', MD5('admin123'), 'admin'),
('Juan Cliente', 'juan@cliente.com', MD5('123456'), 'customer');

-- Cliente asociado al segundo usuario
INSERT INTO clients (user_id, address, phone, document_number) VALUES
(2, 'Av. Siempre Viva 123', '987654321', 'DNI12345678');

-- Categorías
INSERT INTO categories (name, slug) VALUES
('Electrónica', 'electronica'),
('Ropa', 'ropa');

-- Productos
INSERT INTO products (category_id, name, slug, description, price_cents, stock) VALUES
(1, 'Laptop Gamer', 'laptop-gamer', 'Laptop de alto rendimiento', 350000, 10),
(2, 'Polera Negra', 'polera-negra', 'Polera básica de algodón', 5000, 50);

-- Imagenes
INSERT INTO product_images (product_id, image_path, is_main) VALUES
(1, '/uploads/products/laptop.jpg', 1),
(2, '/uploads/products/polera.jpg', 1);


-- use bbf9n1hmqktwbjmir6hi;

-- -- =========================
-- -- USERS
-- -- =========================
-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     email VARCHAR(150) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     role ENUM('customer','admin','editor','support') DEFAULT 'customer',
--     active TINYINT(1) DEFAULT 1,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- CLIENTS (extiende users)
-- -- =========================
-- CREATE TABLE clients (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     address VARCHAR(255) NULL,
--     phone VARCHAR(20) NULL,
--     document_number VARCHAR(50) NULL, -- DNI, RUC, etc.
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- CATEGORIES
-- -- =========================
-- CREATE TABLE categories (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     slug VARCHAR(255) NOT NULL UNIQUE,
--     active TINYINT(1) DEFAULT 1,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- PRODUCTS
-- -- =========================
-- CREATE TABLE products (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     category_id INT NOT NULL,
--     name VARCHAR(150) NOT NULL,
--     slug VARCHAR(255) NOT NULL UNIQUE,
--     description TEXT,
--     price_cents INT UNSIGNED NOT NULL,
--     currency CHAR(3) NOT NULL DEFAULT 'PEN',
--     stock INT DEFAULT 0,
--     active TINYINT(1) DEFAULT 1,
--     image_url VARCHAR(255) DEFAULT NULL, -- URL de la imagen
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     FOREIGN KEY (category_id) REFERENCES categories(id)
-- ) ENGINE=InnoDB;

-- CREATE TABLE product_images (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     product_id INT NOT NULL,
--     image_path VARCHAR(255) NOT NULL,
--     is_main TINYINT(1) DEFAULT 0,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- ORDERS
-- -- =========================
-- CREATE TABLE orders (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     total_cents INT UNSIGNED NOT NULL,
--     currency CHAR(3) NOT NULL DEFAULT 'PEN',
--     status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(id)
-- ) ENGINE=InnoDB;

-- CREATE TABLE order_items (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     order_id INT NOT NULL,
--     product_id INT NOT NULL,
--     quantity INT NOT NULL,
--     price_cents INT UNSIGNED NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
--     FOREIGN KEY (product_id) REFERENCES products(id)
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- PAYMENTS
-- -- =========================
-- CREATE TABLE payments (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     order_id INT NOT NULL,
--     method ENUM('transfer','yape','paypal') NOT NULL,
--     amount_cents INT UNSIGNED NOT NULL,
--     currency CHAR(3) NOT NULL DEFAULT 'PEN',
--     voucher_path VARCHAR(255) NULL,
--     provider_txn_id VARCHAR(255) NULL,
--     status ENUM('pending','confirmed','failed') DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB;

-- -- =========================
-- -- ÍNDICES
-- -- =========================
-- CREATE INDEX idx_users_email ON users(email);
-- CREATE INDEX idx_products_slug ON products(slug);
-- CREATE INDEX idx_orders_user ON orders(user_id);
-- CREATE INDEX idx_payments_order ON payments(order_id);


-- -- ⚠️ Ejecutar primero este bloque para limpiar todo
-- DROP TABLE IF EXISTS payments;
-- DROP TABLE IF EXISTS order_items;
-- DROP TABLE IF EXISTS orders;
-- DROP TABLE IF EXISTS product_images;
-- DROP TABLE IF EXISTS products;
-- DROP TABLE IF EXISTS categories;
-- DROP TABLE IF EXISTS clients;
-- DROP TABLE IF EXISTS users;

-- -- Usuarios
-- INSERT INTO users (name, email, password, role) VALUES
-- ('Admin Master', 'admin@shop.com', MD5('admin123'), 'admin'),
-- ('Juan Cliente', 'juan@cliente.com', MD5('123456'), 'customer');

-- -- Cliente asociado al segundo usuario
-- INSERT INTO clients (user_id, address, phone, document_number) VALUES
-- (2, 'Av. Siempre Viva 123', '987654321', 'DNI12345678');

-- -- Categorías
-- INSERT INTO categories (name, slug) VALUES
-- ('Electrónica', 'electronica'),
-- ('Ropa', 'ropa');

-- -- Productos
-- INSERT INTO products (category_id, name, slug, description, price_cents, currency, stock, active, image_url)
-- VALUES
-- (1, 'Cuaderno Verde', 'cuaderno-verde', 'Cuaderno de hojas verdes, tamaño A5.', 1200, 'PEN', 50, 1, '/uploads/products/cuadernoVerde.jpg'),
-- (1, 'Lapicero Plástico', 'lapicero-plastico', 'Lapicero de plástico color azul.', 500, 'PEN', 100, 1, '/uploads/products/lapiceroPlastico.jpg'),
-- (1, 'Lápices Artesco', 'lapices-artesco', 'Set de 12 lápices de colores.', 2500, 'PEN', 30, 1, '/uploads/products/lapicesArtesco.jpg'),
-- (1, 'Lápiz Negro', 'lapiz-negro', 'Lápiz negro de grafito HB.', 300, 'PEN', 200, 1, '/uploads/products/lapizNegro.png');

