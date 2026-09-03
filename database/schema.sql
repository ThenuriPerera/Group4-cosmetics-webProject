-- =====================================================
-- Lumine Glow - Cosmetic E-Commerce System
-- Database Schema (19 tables, matches Section 8 of proposal)
-- Group 04
-- =====================================================

CREATE DATABASE IF NOT EXISTS lumine_glow;
USE lumine_glow;

-- ================= USER & ACCESS =================
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('guest','customer','editor','admin') NOT NULL DEFAULT 'customer',
    status ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Address (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    street VARCHAR(150),
    city VARCHAR(80),
    postal_code VARCHAR(20),
    state VARCHAR(80),
    country VARCHAR(80),
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- ================= CATALOGUE =================
CREATE TABLE Brand (
    brand_id INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(100) NOT NULL,
    manufacturer_country VARCHAR(80)
);

CREATE TABLE Category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    category_description VARCHAR(255)
);

CREATE TABLE Product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category_id INT,
    brand_id INT,
    image VARCHAR(255),
    description TEXT,
    skin_tone VARCHAR(50),
    skin_type VARCHAR(50),
    FOREIGN KEY (category_id) REFERENCES Category(category_id),
    FOREIGN KEY (brand_id) REFERENCES Brand(brand_id)
);

CREATE TABLE Product_Variant (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    shade VARCHAR(50),
    size VARCHAR(50),
    price DECIMAL(10,2),
    stock INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- ================= SMART FEATURES =================
CREATE TABLE Skin_Quiz (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    answers TEXT,
    result_skin_type VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

CREATE TABLE Beauty_Profile (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    skin_tone VARCHAR(50),
    skin_type VARCHAR(50),
    concern VARCHAR(150),
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- ================= CART =================
CREATE TABLE Cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

CREATE TABLE Cart_Item (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES Cart(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id),
    FOREIGN KEY (variant_id) REFERENCES Product_Variant(variant_id)
);

-- ================= ORDERS / PAYMENT / SHIPMENT =================
CREATE TABLE `Order` (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (address_id) REFERENCES Address(address_id)
);

CREATE TABLE Order_Item (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Order_History (
    order_history_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    order_history_status VARCHAR(50),
    time_stamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id) ON DELETE CASCADE
);

CREATE TABLE Promo_Code (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percentage DECIMAL(5,2) NOT NULL,
    expiry_date DATE
);

CREATE TABLE Payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    promo_id INT,
    transaction_id VARCHAR(150),
    method VARCHAR(50),
    amount DECIMAL(10,2),
    status ENUM('Pending','Completed','Failed') DEFAULT 'Pending',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id),
    FOREIGN KEY (promo_id) REFERENCES Promo_Code(promo_id)
);

CREATE TABLE Courier (
    courier_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30)
);

CREATE TABLE Shipment (
    shipment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    courier_id INT,
    tracking_number VARCHAR(100),
    delivery_status ENUM('Pending','Shipped','In Transit','Delivered') DEFAULT 'Pending',
    estimate_delivery DATE,
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id) ON DELETE CASCADE,
    FOREIGN KEY (courier_id) REFERENCES Courier(courier_id)
);

-- ================= SOCIAL =================
CREATE TABLE Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    rating_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

CREATE TABLE Wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    UNIQUE (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- ================= SEED DATA (minimal, for dev/testing) =================
INSERT INTO Category (category_name, category_description) VALUES
('Makeup','Face, eyes, lips, cheeks, brows'),
('Skincare','Cleansers, moisturizers, treatments'),
('Haircare','Shampoo, conditioner, styling'),
('Fragrance','Perfume, body mist'),
('Bath & Body','Body wash, lotion, scrub'),
('Tools & Brushes','Makeup brushes, blenders');

INSERT INTO Brand (brand_name, manufacturer_country) VALUES
('Glow Basics','Sri Lanka'),
('Pure Derm','South Korea');

INSERT INTO User (name, email, phone, password, role) VALUES
('Admin User','admin@lumineglow.com','0770000000', '$2y$10$examplehashexamplehashexamplehas', 'admin');
