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

CREATE TABLE order_shipping (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivery_type ENUM('delivery', 'pickup') NOT NULL,
    address VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    reference VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- PAYMENTS
-- =========================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method ENUM('transfer','yape','paypal','contraentrega') NOT NULL,
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

-- =========================
-- USERS
-- =========================
INSERT INTO users (name, email, password, verification_code, verified, role)
VALUES
('Admin Master', 'admin@projex321.free.nf', 'hashed_password_123', NULL, 1, 'admin'),
('Carlos Ramos', 'carlosr@gmail.com', 'hashed_password_456', 'A12X9K', 1, 'customer'),
('Lucía Torres', 'lucia.t@gmail.com', 'hashed_password_789', 'B99Q2P', 0, 'customer'),
('Sofía Morales', 'sofia.morales@projex321.free.nf', 'hashed_password_abc', NULL, 1, 'editor'),
('Juan Pérez', 'juan.perez@projex321.free.nf', 'hashed_password_xyz', NULL, 1, 'support');

-- =========================
-- CLIENTS
-- =========================
INSERT INTO clients (user_id, address, phone, document_number)
VALUES
(2, 'Av. Los Próceres 123, Lima', '999111222', 'DNI 74859612'),
(3, 'Jr. Las Flores 456, Arequipa', '988555111', 'DNI 74125896');

-- =========================
-- CATEGORIES
-- =========================
INSERT INTO categories (name, slug)
VALUES
('Útiles Escolares', 'utiles-escolares'),
('Papelería', 'papeleria'),
('Oficina', 'oficina');

-- =========================
-- PRODUCTS
-- =========================
INSERT INTO products (category_id, name, slug, description, price_cents, currency, stock, active)
VALUES
(1, 'Cuaderno Verde Universitario', 'cuaderno-verde-universitario', 'Cuaderno de tapa dura, 100 hojas, tamaño A4.', 8900, 'PEN', 25, 1),
(1, 'Lapicero Plástico Azul', 'lapicero-plastico-azul', 'Lapicero ergonómico de tinta azul, cuerpo plástico resistente.', 2900, 'PEN', 100, 1),
(1, 'Set de Lápices Artesco', 'set-lapices-artesco', 'Set de 12 lápices de colores marca Artesco, punta suave.', 12900, 'PEN', 50, 1),
(1, 'Lápiz Negro Nº2 HB', 'lapiz-negro-n2-hb', 'Lápiz de grafito HB, madera de alta calidad.', 1900, 'PEN', 200, 1);

-- =========================
-- PRODUCT IMAGES
-- =========================
INSERT INTO product_images (product_id, image_path, is_main)
VALUES
(1, 'https://projex321.free.nf/ecommerce/uploads/products/cuadernoVerde.jpg', 1),
(2, 'https://projex321.free.nf/ecommerce/uploads/products/lapiceroPlastico.jpg', 1),
(3, 'https://projex321.free.nf/ecommerce/uploads/products/lapicesArtesco.jpg', 1),
(4, 'https://projex321.free.nf/ecommerce/uploads/products/lapizNegro.png', 1);



-- =========================
-- ORDERS
-- =========================
INSERT INTO orders (user_id, total_cents, currency, status)
VALUES
(2, 14700, 'PEN', 'paid'),
(3, 12900, 'PEN', 'pending');

-- =========================
-- ORDER ITEMS
-- =========================
INSERT INTO order_items (order_id, product_id, quantity, price_cents)
VALUES
(1, 1, 1, 8900),
(1, 4, 3, 1900),
(2, 3, 1, 12900);

-- =========================
-- PAYMENTS
-- =========================
INSERT INTO payments (order_id, method, amount_cents, currency, voucher_path, provider_txn_id, status)
VALUES
(1, 'transfer', 14700, 'PEN', 'https://projex321.free.nf/ecommerce/uploads/vouchers/voucher1.jpg', 'TRX654321PE', 'confirmed'),
(2, 'yape', 12900, 'PEN', 'https://projex321.free.nf/ecommerce/uploads/vouchers/voucher2.jpg', 'YP1238741', 'pending');
