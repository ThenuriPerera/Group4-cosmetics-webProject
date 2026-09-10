-- ============================================================
-- LUMINE GLOW - COSMETIC E-COMMERCE SYSTEM
-- Group 04
--
-- File: database/schema.sql
-- Purpose: Complete schema and demonstration catalogue
--
-- FRESH INSTALLATION ONLY
-- Import into an empty lumine_glow database.
-- Stop on any SQL error. Do not run with a continue-on-error option.
--
-- Includes:
--   21 tables
--    6 categories
--    5 brands
--   50 demonstration products
--   61 product variants
--   50 product source records
--
-- Prices are demonstration values in LKR.
-- Product images and unprovided product details remain NULL.
-- No real customer information or default passwords are included.
--
-- Admins and editors use the User table and its role column.
-- Store passwords using PHP password_hash().
--
-- This script is not an existing-database migration.
-- CREATE TABLE statements intentionally do not silently skip
-- existing tables with potentially different definitions.
-- ============================================================

CREATE DATABASE IF NOT EXISTS lumine_glow
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE lumine_glow;

SET NAMES utf8mb4;

-- ============================================================
-- 01. USER
-- Customers, editors and administrators
-- ============================================================

CREATE TABLE `User` (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM(
        'guest',
        'customer',
        'editor',
        'admin'
    ) NOT NULL DEFAULT 'customer',
    status ENUM(
        'active',
        'suspended'
    ) NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 02. ADDRESS
-- ============================================================

CREATE TABLE Address (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    street VARCHAR(150),
    city VARCHAR(80),
    postal_code VARCHAR(20),
    state VARCHAR(80),
    country VARCHAR(80),

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 03. BRAND
-- Includes brand_description from the extended schema
-- ============================================================

CREATE TABLE Brand (
    brand_id INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(100) NOT NULL,
    manufacturer_country VARCHAR(80),
    brand_description TEXT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 04. CATEGORY
-- ============================================================

CREATE TABLE Category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    category_description VARCHAR(255)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 05. PRODUCT
-- Includes every original and extended product column
-- ============================================================

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

    sub_category VARCHAR(100) DEFAULT NULL,
    product_type VARCHAR(100) DEFAULT NULL,

    sku VARCHAR(50) DEFAULT NULL,
    barcode VARCHAR(50) DEFAULT NULL,
    short_description VARCHAR(255) DEFAULT NULL,

    ingredients TEXT,
    how_to_use TEXT,
    warnings TEXT,

    finish VARCHAR(50) DEFAULT NULL,
    coverage VARCHAR(50) DEFAULT NULL,

    FOREIGN KEY (category_id)
        REFERENCES Category(category_id),

    FOREIGN KEY (brand_id)
        REFERENCES Brand(brand_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 06. PRODUCT VARIANT
-- ============================================================

CREATE TABLE Product_Variant (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,

    shade VARCHAR(50),
    size VARCHAR(50),
    price DECIMAL(10,2),
    stock INT DEFAULT 0,

    sku VARCHAR(60) DEFAULT NULL,
    shade_hex CHAR(7) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 07. PRODUCT IMAGE
-- Additional product photos and attribution information
-- ============================================================

CREATE TABLE Product_Image (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT NOT NULL DEFAULT 0,

    source_url VARCHAR(500),
    photographer VARCHAR(150),
    license_name VARCHAR(100),
    license_url VARCHAR(500),

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 08. PRODUCT SOURCE
-- ============================================================

CREATE TABLE Product_Source (
    source_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    source_name VARCHAR(150) NOT NULL,
    source_url VARCHAR(500),
    verified_fields VARCHAR(255),
    checked_at DATE,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 09. SKIN QUIZ
-- ============================================================

CREATE TABLE Skin_Quiz (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    answers TEXT,
    result_skin_type VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. BEAUTY PROFILE
-- ============================================================

CREATE TABLE Beauty_Profile (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    skin_tone VARCHAR(50),
    skin_type VARCHAR(50),
    concern VARCHAR(150),

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. CART
-- ============================================================

CREATE TABLE Cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. CART ITEM
-- ============================================================

CREATE TABLE Cart_Item (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity INT NOT NULL DEFAULT 1,

    FOREIGN KEY (cart_id)
        REFERENCES Cart(cart_id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id),

    FOREIGN KEY (variant_id)
        REFERENCES Product_Variant(variant_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. ORDER
-- Backticks are required because ORDER is an SQL keyword
-- ============================================================

CREATE TABLE `Order` (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,

    order_status ENUM(
        'Pending',
        'Processing',
        'Shipped',
        'Delivered',
        'Cancelled'
    ) DEFAULT 'Pending',

    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id),

    FOREIGN KEY (address_id)
        REFERENCES Address(address_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. ORDER ITEM
-- price stores the unit price used for the purchase
-- ============================================================

CREATE TABLE Order_Item (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id)
        REFERENCES `Order`(order_id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 15. ORDER HISTORY
-- ============================================================

CREATE TABLE Order_History (
    order_history_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    order_history_status VARCHAR(50),
    time_stamp DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
        REFERENCES `Order`(order_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. PROMO CODE
-- ============================================================

CREATE TABLE Promo_Code (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percentage DECIMAL(5,2) NOT NULL,
    expiry_date DATE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 17. PAYMENT
-- ============================================================

CREATE TABLE Payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    promo_id INT,

    transaction_id VARCHAR(150),
    method VARCHAR(50),
    amount DECIMAL(10,2),

    status ENUM(
        'Pending',
        'Completed',
        'Failed'
    ) DEFAULT 'Pending',

    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
        REFERENCES `Order`(order_id),

    FOREIGN KEY (promo_id)
        REFERENCES Promo_Code(promo_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 18. COURIER
-- ============================================================

CREATE TABLE Courier (
    courier_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 19. SHIPMENT
-- ============================================================

CREATE TABLE Shipment (
    shipment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    courier_id INT,

    tracking_number VARCHAR(100),

    delivery_status ENUM(
        'Pending',
        'Shipped',
        'In Transit',
        'Delivered'
    ) DEFAULT 'Pending',

    estimate_delivery DATE,

    FOREIGN KEY (order_id)
        REFERENCES `Order`(order_id)
        ON DELETE CASCADE,

    FOREIGN KEY (courier_id)
        REFERENCES Courier(courier_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 20. REVIEW
-- ============================================================

CREATE TABLE Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,

    status ENUM(
        'Pending',
        'Approved',
        'Rejected'
    ) DEFAULT 'Pending',

    rating_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 21. WISHLIST
-- ============================================================

CREATE TABLE Wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    FOREIGN KEY (user_id)
        REFERENCES `User`(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES Product(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DEMONSTRATION DATA
--
-- Explicit IDs make this fresh-install seed easy to read.
-- They are not intended for merging into existing records.
-- Schema creation above is not rolled back by this transaction.
-- ============================================================

START TRANSACTION;

-- ============================================================
-- CATEGORIES
-- Original descriptions retained with sample collection labels
-- ============================================================

INSERT INTO Category (
    category_id,
    category_name,
    category_description
) VALUES
(1, 'Makeup',
 'Face, eyes, lips, cheeks, brows. Makeup sample collection'),
(2, 'Skincare',
 'Cleansers, moisturizers, treatments. Skincare sample collection'),
(3, 'Haircare',
 'Shampoo, conditioner, styling. Haircare sample collection'),
(4, 'Fragrance',
 'Perfume, body mist. Fragrance sample collection'),
(5, 'Bath & Body',
 'Body wash, lotion, scrub. Bath & Body sample collection'),
(6, 'Tools & Brushes',
 'Makeup brushes, blenders. Tools & Brushes sample collection');

-- ============================================================
-- BRANDS
-- Original two brands plus three extended sample brands
-- ============================================================

INSERT INTO Brand (
    brand_id,
    brand_name,
    manufacturer_country,
    brand_description
) VALUES
(1, 'Glow Basics', 'Sri Lanka', NULL),
(2, 'Pure Derm', 'South Korea', NULL),
(3, 'Lumine Studio', NULL, 'Fictional assignment brand.'),
(4, 'Petal Ritual', NULL, 'Fictional assignment brand.'),
(5, 'Bloom Atelier', NULL, 'Fictional assignment brand.');

-- ============================================================
-- ALL 50 PRODUCTS
--
-- The repeated demonstration disclaimer is appended below
-- to preserve the original full descriptions without repeating
-- the same long sentence in every INSERT row.
--
-- Fields absent from the source remain NULL:
-- image, barcode, ingredients, how_to_use, warnings,
-- skin_tone and skin_type.
-- ============================================================

INSERT INTO Product (
    product_id,
    sku,
    product_name,
    category_id,
    brand_id,
    sub_category,
    product_type,
    short_description,
    description,
    finish,
    coverage,
    price,
    stock
) VALUES
(1,'LGS-FND-001','Silk Veil Liquid Foundation',1,3,'Face','Foundation','Sample foundation. Pack: 30 ml.','Liquid foundation with five illustrative shades in pump bottles.','Satin','Medium',3200,95),
(2,'LGS-LIP-001','Velvet Petal Lipstick',1,3,'Lips','Lipstick','Sample lipstick. Pack: 3.5 g.','Matte lipstick with Rose Nude, Berry Plum and Coral Peach options.','Matte','Full',1800,65),
(3,'PTR-CLN-001','Cloud Facial Cleanser',2,4,'Cleansers','Face Wash','Sample face wash. Pack: 100 ml.','Facial cleanser offered in 100 ml and 200 ml bottles.',NULL,NULL,2200,50),
(4,'PTR-CRM-001','Soft Bloom Face Cream',2,4,'Moisturizers','Face Cream','Sample face cream. Pack: 50 g.','Face cream supplied in a compact jar with a screw-top lid.',NULL,NULL,2900,35),
(5,'BLA-SHP-001','Silken Roots Shampoo',3,5,'Wash & Care','Shampoo','Sample shampoo. Pack: 250 ml.','Shampoo supplied in 250 ml and 500 ml bottles.',NULL,NULL,1900,40),
(6,'BLA-CND-001','Silken Lengths Conditioner',3,5,'Wash & Care','Conditioner','Sample conditioner. Pack: 250 ml.','Rinse-out conditioner packaged in a capped bottle.',NULL,NULL,2100,30),
(7,'BLA-PRF-001','Petal Hour Perfume',4,5,'Fine Fragrance','Perfume','Sample perfume. Pack: 30 ml.','Perfume presented in 30 ml and 50 ml glass spray bottles.',NULL,NULL,4500,25),
(8,'BLA-MST-001','Morning Bloom Body Mist',4,5,'Body Fragrance','Body Mist','Sample body mist. Pack: 150 ml.','Body mist supplied in a tall spray bottle.',NULL,NULL,2300,25),
(9,'PTR-BWS-001','Bloom Bath Body Wash',5,4,'Cleansing','Body Wash','Sample body wash. Pack: 300 ml.','Body wash presented in a capped bottle for the bath-and-body category.',NULL,NULL,1700,40),
(10,'PTR-LOT-001','Velvet Body Lotion',5,4,'Moisturizing','Body Lotion','Sample body lotion. Pack: 200 ml.','Body lotion offered in 200 ml and 400 ml packs.',NULL,NULL,2000,45),
(11,'LGS-BRS-001','Studio Soft Brush Set',6,3,'Makeup Tools','Brush Set','Sample brush set. Pack: 5 pieces.','Five-piece set with powder, foundation and blush brushes plus two eye brushes.',NULL,NULL,3500,20),
(12,'LGS-SPG-001','Cloud Blend Makeup Sponge',6,3,'Makeup Tools','Makeup Sponge','Sample makeup sponge. Pack: 1 piece.','One makeup sponge per pack, available in Rose and Lavender colours.',NULL,NULL,850,55),
(13,'LGS-CON-001','Soft Focus Concealer',1,3,'Face','Concealer','Sample concealer. Pack: 10 ml.','Liquid concealer in a compact tube with an applicator; shade and coverage are unspecified.',NULL,NULL,2100,30),
(14,'LGS-POW-001','Cloud Finish Loose Powder',1,3,'Face','Setting Powder','Sample setting powder. Pack: 20 g.','Loose setting powder packaged in a jar with a dispensing sifter.',NULL,NULL,2400,25),
(15,'LGS-BLS-001','Petal Touch Cream Blush',1,3,'Cheeks','Blush','Sample blush. Pack: 8 g.','Cream blush supplied in a small single-pan compact.',NULL,NULL,1950,35),
(16,'LGS-BRZ-001','Golden Hour Bronzer',1,3,'Cheeks','Bronzer','Sample bronzer. Pack: 12 g.','Pressed bronzing powder supplied in a single-pan compact.',NULL,NULL,2600,20),
(17,'LGS-HLT-001','Moonbeam Highlighter',1,3,'Cheeks','Highlighter','Sample highlighter. Pack: 9 g.','Pressed highlighter packaged in a compact for the cheek makeup collection.',NULL,NULL,2300,25),
(18,'LGS-EYE-001','Rose Muse Eyeshadow Palette',1,3,'Eyes','Eyeshadow Palette','Sample eyeshadow palette. Pack: 9 pans.','Nine-pan palette with illustrative rose, brown and champagne colours, sold as one unit.',NULL,NULL,4200,18),
(19,'LGS-LNR-001','Fine Line Liquid Eyeliner',1,3,'Eyes','Eyeliner','Sample eyeliner. Pack: 1 ml.','Liquid eyeliner in a pen-style package; wear duration is unspecified.',NULL,NULL,1600,40),
(20,'LGS-MSC-001','Petal Lift Mascara',1,3,'Eyes','Mascara','Sample mascara. Pack: 8 ml.','Mascara supplied in a tube with a brush applicator.',NULL,NULL,2200,30),
(21,'LGS-BRW-001','Brow Sketch Pencil',1,3,'Brows','Brow Pencil','Sample brow pencil. Pack: 1 piece.','Eyebrow pencil with a spoolie at the opposite end; shade is unspecified.',NULL,NULL,1250,45),
(22,'LGS-BLM-001','Petal Pocket Lip Balm',1,3,'Lips','Lip Balm','Sample lip balm. Pack: 4 g.','Lip balm supplied in a portable twist-up stick.',NULL,NULL,950,50),
(23,'PTR-TNR-001','Dew Ritual Facial Toner',2,4,'Toners','Face Toner','Sample face toner. Pack: 150 ml.','Facial toner supplied in a capped bottle.',NULL,NULL,1800,30),
(24,'PTR-SRM-001','Morning Dew Face Serum',2,4,'Treatments','Face Serum','Sample face serum. Pack: 30 ml.','Facial serum presented in a dropper bottle; active ingredients are unspecified.',NULL,NULL,3400,25),
(25,'PTR-NGT-001','Moon Petal Night Cream',2,4,'Moisturizers','Night Cream','Sample night cream. Pack: 50 g.','Night cream packaged in a jar for the moisturizer collection.',NULL,NULL,3200,20),
(26,'PTR-MIC-001','Clear Petal Micellar Water',2,4,'Cleansers','Micellar Water','Sample micellar water. Pack: 200 ml.','Micellar cleansing water supplied in a flip-cap bottle.',NULL,NULL,1900,35),
(27,'PTR-MSK-001','Quiet Ritual Face Mask',2,4,'Masks','Wash-off Mask','Sample wash-off mask. Pack: 75 g.','Wash-off facial mask supplied in a tube; application time is unspecified.',NULL,NULL,2500,20),
(28,'PTR-EYE-001','Soft Morning Eye Cream',2,4,'Eye Care','Eye Cream','Sample eye cream. Pack: 15 g.','Eye cream presented in a small tube; no treatment claims are made.',NULL,NULL,2800,18),
(29,'PTR-LPM-001','Petal Sleep Lip Mask',2,4,'Lip Care','Lip Mask','Sample lip mask. Pack: 15 g.','Lip mask supplied in a small jar.',NULL,NULL,1700,28),
(30,'PTR-CBM-001','Melt Away Cleansing Balm',2,4,'Cleansers','Cleansing Balm','Sample cleansing balm. Pack: 80 g.','Cleansing balm supplied in a wide-mouth jar.',NULL,NULL,2900,22),
(31,'BLA-HMK-001','Silken Ritual Hair Mask',3,5,'Treatments','Hair Mask','Sample hair mask. Pack: 200 g.','Rinse-out hair mask supplied in a tub.',NULL,NULL,2600,25),
(32,'BLA-HOL-001','Gloss Petal Hair Oil',3,5,'Treatments','Hair Oil','Sample hair oil. Pack: 50 ml.','Hair oil supplied in a small pump bottle; oil composition is unspecified.',NULL,NULL,2300,30),
(33,'BLA-LVN-001','Silken Air Leave-in Conditioner',3,5,'Conditioning','Leave-in Conditioner','Sample leave-in conditioner. Pack: 150 ml.','Leave-in conditioner in a bottle; no heat-protection claims are made.',NULL,NULL,2400,24),
(34,'BLA-SCS-001','Roots Ritual Scalp Serum',3,5,'Scalp Care','Scalp Serum','Sample scalp serum. Pack: 50 ml.','Scalp serum supplied in a dropper bottle; no hair-growth claims are made.',NULL,NULL,3100,18),
(35,'BLA-DRY-001','Cloud Fresh Dry Shampoo',3,5,'Wash & Care','Dry Shampoo','Sample dry shampoo. Pack: 150 ml.','Dry shampoo packaged as a spray product.',NULL,NULL,2100,26),
(36,'BLA-STY-001','Soft Shape Styling Cream',3,5,'Styling','Styling Cream','Sample styling cream. Pack: 100 ml.','Styling cream supplied in a squeeze tube; hold strength is unspecified.',NULL,NULL,1950,28),
(37,'BLA-AMB-001','Amber Evening Perfume',4,5,'Fine Fragrance','Perfume','Sample perfume. Pack: 50 ml.','Perfume in a glass spray bottle. The name does not establish ingredients or fragrance notes.',NULL,NULL,6200,15),
(38,'BLA-CIT-001','Citrus Daybreak Body Mist',4,5,'Body Fragrance','Body Mist','Sample body mist. Pack: 150 ml.','Body mist in a spray bottle. Fragrance composition is unspecified.',NULL,NULL,2400,30),
(39,'BLA-VAN-001','Vanilla Dusk Perfume',4,5,'Fine Fragrance','Perfume','Sample perfume. Pack: 50 ml.','Perfume in a glass bottle. The name does not establish the presence of vanilla.',NULL,NULL,5800,18),
(40,'BLA-TRV-001','Petal Journey Travel Perfume',4,5,'Travel Fragrance','Travel Perfume','Sample travel perfume. Pack: 10 ml.','Travel-size perfume sold individually in a compact spray bottle.',NULL,NULL,1900,35),
(41,'PTR-SCR-001','Petal Polish Body Scrub',5,4,'Exfoliating','Body Scrub','Sample body scrub. Pack: 200 g.','Body scrub supplied in a jar; exfoliating ingredients are unspecified.',NULL,NULL,2300,25),
(42,'PTR-HND-001','Soft Petal Hand Cream',5,4,'Moisturizing','Hand Cream','Sample hand cream. Pack: 50 g.','Hand cream supplied in a portable squeeze tube.',NULL,NULL,1200,45),
(43,'PTR-FOOT-001','Evening Ritual Foot Cream',5,4,'Foot Care','Foot Cream','Sample foot cream. Pack: 75 g.','Foot cream supplied in a tube; no claims to treat skin conditions are made.',NULL,NULL,1500,30),
(44,'PTR-SALT-001','Quiet Bloom Bath Salts',5,4,'Bath Care','Bath Salts','Sample bath salts. Pack: 300 g.','Bath salts supplied in a resealable container; dilution instructions are unspecified.',NULL,NULL,1700,22),
(45,'PTR-BTR-001','Velvet Petal Body Butter',5,4,'Moisturizing','Body Butter','Sample body butter. Pack: 200 g.','Body butter supplied in a wide-mouth tub.',NULL,NULL,2700,24),
(46,'PTR-HWS-001','Bloom Sink Hand Wash',5,4,'Cleansing','Hand Wash','Sample hand wash. Pack: 250 ml.','Liquid hand wash in a pump bottle; no disinfectant claims are made.',NULL,NULL,1100,40),
(47,'LGS-PBR-001','Studio Soft Powder Brush',6,3,'Makeup Tools','Powder Brush','Sample powder brush. Pack: 1 piece.','Individual powder brush with a rounded head and long handle; bristle material is unspecified.',NULL,NULL,1500,30),
(48,'LGS-EBR-001','Studio Eye Brush Trio',6,3,'Makeup Tools','Eye Brush Set','Sample eye brush set. Pack: 3 pieces.','Three-piece set with a blending brush, flat shader brush and angled detail brush.',NULL,NULL,1900,25),
(49,'LGS-CURL-001','Petal Curve Lash Curler',6,3,'Makeup Tools','Eyelash Curler','Sample eyelash curler. Pack: 1 piece.','Manual eyelash curler sold as one unit.',NULL,NULL,1250,28),
(50,'LGS-BAND-001','Cloud Ritual Beauty Headband',6,3,'Beauty Accessories','Headband','Sample headband. Pack: 1 piece.','Fabric beauty-routine headband sold individually; dimensions and fabric composition are unspecified.',NULL,NULL,750,40);

-- Restore the complete original demonstration descriptions.

UPDATE Product
SET description = CONCAT(
    'Fictional assignment product. ',
    description,
    ' Prices and stock are demonstration values.',
    ' Ingredients, usage directions and skin suitability have not been verified.'
)
WHERE product_id BETWEEN 1 AND 50;

-- ============================================================
-- ALL 61 PRODUCT VARIANTS
-- Includes original shades, hex colours, sizes, prices and stock
-- ============================================================

INSERT INTO Product_Variant (
    product_id,
    sku,
    shade,
    shade_hex,
    size,
    price,
    stock
) VALUES
(1,'LGS-FND-001-FAIR','Fair','#F4DCC9','30 ml',3200,15),
(1,'LGS-FND-001-LIGHT','Light','#E8BFA0','30 ml',3200,20),
(1,'LGS-FND-001-MEDIUM','Medium','#C98F66','30 ml',3200,25),
(1,'LGS-FND-001-TAN','Tan','#A56A3E','30 ml',3200,20),
(1,'LGS-FND-001-DEEP','Deep','#5C3A24','30 ml',3200,15),
(2,'LGS-LIP-001-ROSE','Rose Nude','#B76D78','3.5 g',1800,25),
(2,'LGS-LIP-001-BERRY','Berry Plum','#713C58','3.5 g',1800,20),
(2,'LGS-LIP-001-CORAL','Coral Peach','#DC8374','3.5 g',1800,20),
(3,'PTR-CLN-001-100',NULL,NULL,'100 ml',2200,30),
(3,'PTR-CLN-001-200',NULL,NULL,'200 ml',3500,20),
(4,'PTR-CRM-001-050',NULL,NULL,'50 g',2900,35),
(5,'BLA-SHP-001-250',NULL,NULL,'250 ml',1900,25),
(5,'BLA-SHP-001-500',NULL,NULL,'500 ml',3100,15),
(6,'BLA-CND-001-250',NULL,NULL,'250 ml',2100,30),
(7,'BLA-PRF-001-030',NULL,NULL,'30 ml',4500,15),
(7,'BLA-PRF-001-050',NULL,NULL,'50 ml',6500,10),
(8,'BLA-MST-001-150',NULL,NULL,'150 ml',2300,25),
(9,'PTR-BWS-001-300',NULL,NULL,'300 ml',1700,40),
(10,'PTR-LOT-001-200',NULL,NULL,'200 ml',2000,25),
(10,'PTR-LOT-001-400',NULL,NULL,'400 ml',3300,20),
(11,'LGS-BRS-001-SET',NULL,NULL,'5 pieces',3500,20),
(12,'LGS-SPG-001-ROSE','Rose','#DEA0AE','1 piece',850,30),
(12,'LGS-SPG-001-LAV','Lavender','#B8A1D5','1 piece',850,25),
(13,'LGS-CON-001-STD',NULL,NULL,'10 ml',2100,30),
(14,'LGS-POW-001-STD',NULL,NULL,'20 g',2400,25),
(15,'LGS-BLS-001-STD',NULL,NULL,'8 g',1950,35),
(16,'LGS-BRZ-001-STD',NULL,NULL,'12 g',2600,20),
(17,'LGS-HLT-001-STD',NULL,NULL,'9 g',2300,25),
(18,'LGS-EYE-001-STD',NULL,NULL,'9 pans',4200,18),
(19,'LGS-LNR-001-STD',NULL,NULL,'1 ml',1600,40),
(20,'LGS-MSC-001-STD',NULL,NULL,'8 ml',2200,30),
(21,'LGS-BRW-001-STD',NULL,NULL,'1 piece',1250,45),
(22,'LGS-BLM-001-STD',NULL,NULL,'4 g',950,50),
(23,'PTR-TNR-001-STD',NULL,NULL,'150 ml',1800,30),
(24,'PTR-SRM-001-STD',NULL,NULL,'30 ml',3400,25),
(25,'PTR-NGT-001-STD',NULL,NULL,'50 g',3200,20),
(26,'PTR-MIC-001-STD',NULL,NULL,'200 ml',1900,35),
(27,'PTR-MSK-001-STD',NULL,NULL,'75 g',2500,20),
(28,'PTR-EYE-001-STD',NULL,NULL,'15 g',2800,18),
(29,'PTR-LPM-001-STD',NULL,NULL,'15 g',1700,28),
(30,'PTR-CBM-001-STD',NULL,NULL,'80 g',2900,22),
(31,'BLA-HMK-001-STD',NULL,NULL,'200 g',2600,25),
(32,'BLA-HOL-001-STD',NULL,NULL,'50 ml',2300,30),
(33,'BLA-LVN-001-STD',NULL,NULL,'150 ml',2400,24),
(34,'BLA-SCS-001-STD',NULL,NULL,'50 ml',3100,18),
(35,'BLA-DRY-001-STD',NULL,NULL,'150 ml',2100,26),
(36,'BLA-STY-001-STD',NULL,NULL,'100 ml',1950,28),
(37,'BLA-AMB-001-STD',NULL,NULL,'50 ml',6200,15),
(38,'BLA-CIT-001-STD',NULL,NULL,'150 ml',2400,30),
(39,'BLA-VAN-001-STD',NULL,NULL,'50 ml',5800,18),
(40,'BLA-TRV-001-STD',NULL,NULL,'10 ml',1900,35),
(41,'PTR-SCR-001-STD',NULL,NULL,'200 g',2300,25),
(42,'PTR-HND-001-STD',NULL,NULL,'50 g',1200,45),
(43,'PTR-FOOT-001-STD',NULL,NULL,'75 g',1500,30),
(44,'PTR-SALT-001-STD',NULL,NULL,'300 g',1700,22),
(45,'PTR-BTR-001-STD',NULL,NULL,'200 g',2700,24),
(46,'PTR-HWS-001-STD',NULL,NULL,'250 ml',1100,40),
(47,'LGS-PBR-001-STD',NULL,NULL,'1 piece',1500,30),
(48,'LGS-EBR-001-STD',NULL,NULL,'3 pieces',1900,25),
(49,'LGS-CURL-001-STD',NULL,NULL,'1 piece',1250,28),
(50,'LGS-BAND-001-STD',NULL,NULL,'1 piece',750,40);

-- ============================================================
-- PRODUCT SOURCE RECORDS
-- One source record for each of the 50 demonstration products
-- ============================================================

INSERT INTO Product_Source (
    product_id,
    source_name,
    verified_fields
)
SELECT
    product_id,
    'Original fictional assignment sample',
    'None: product details, prices and stock are demonstration data'
FROM Product
WHERE product_id BETWEEN 1 AND 50;

-- No Product_Image rows are inserted:
-- the supplied source did not contain actual product photos.

-- No users, addresses, orders, payments, shipments or reviews
-- are invented for this catalogue seed.
-- These tables are populated through application activity.
--
-- Create the admin separately using a PHP-hashed password.
-- Do not commit a real admin password or real customer data.

COMMIT;

-- ============================================================
-- VERIFICATION QUERIES
-- ============================================================

-- Expected: 21 tables.

SELECT COUNT(*) AS total_tables
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_TYPE = 'BASE TABLE';

-- Expected:
-- categories = 6
-- brands = 5
-- products = 50
-- variants = 61
-- sources = 50
-- images = 0

SELECT 'categories' AS item, COUNT(*) AS total FROM Category
UNION ALL
SELECT 'brands', COUNT(*) FROM Brand
UNION ALL
SELECT 'products', COUNT(*) FROM Product
UNION ALL
SELECT 'variants', COUNT(*) FROM Product_Variant
UNION ALL
SELECT 'sources', COUNT(*) FROM Product_Source
UNION ALL
SELECT 'images', COUNT(*) FROM Product_Image;

-- Product counts by category.

SELECT
    c.category_id,
    c.category_name,
    COUNT(p.product_id) AS product_count
FROM Category c
LEFT JOIN Product p
    ON p.category_id = c.category_id
GROUP BY
    c.category_id,
    c.category_name
ORDER BY c.category_id;

-- Detailed catalogue.

SELECT
    p.product_id,
    p.sku,
    p.product_name,
    b.brand_name,
    c.category_name,
    p.sub_category,
    p.product_type,
    p.price AS starting_price_lkr,
    p.stock,
    p.short_description,
    p.description
FROM Product p
JOIN Brand b
    ON b.brand_id = p.brand_id
JOIN Category c
    ON c.category_id = p.category_id
ORDER BY
    c.category_id,
    p.product_name;

-- Foundation shades.

SELECT
    p.product_name,
    v.sku,
    v.shade,
    v.shade_hex,
    v.size,
    v.price,
    v.stock
FROM Product p
JOIN Product_Variant v
    ON v.product_id = p.product_id
WHERE p.sku = 'LGS-FND-001'
ORDER BY v.variant_id;

-- Expected: no rows.
-- Initial aggregate product stock must match variant stock.

SELECT
    p.product_id,
    p.sku,
    p.stock AS product_stock,
    SUM(v.stock) AS variant_stock
FROM Product p
JOIN Product_Variant v
    ON v.product_id = p.product_id
GROUP BY
    p.product_id,
    p.sku,
    p.stock
HAVING p.stock <> SUM(v.stock);

-- Expected: no rows.

SELECT sku, COUNT(*) AS occurrences
FROM Product
WHERE sku IS NOT NULL
GROUP BY sku
HAVING COUNT(*) > 1;

SELECT sku, COUNT(*) AS occurrences
FROM Product_Variant
WHERE sku IS NOT NULL
GROUP BY sku
HAVING COUNT(*) > 1;

-- ============================================================
-- END OF FILE
-- ============================================================