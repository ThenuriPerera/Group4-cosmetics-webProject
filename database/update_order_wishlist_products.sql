USE lumine_glow;

-- Run these only if variant_id and its foreign key are not already present.
ALTER TABLE Order_Item
    ADD COLUMN variant_id INT NULL AFTER product_id;

ALTER TABLE Order_Item
    ADD CONSTRAINT fk_order_item_variant
    FOREIGN KEY (variant_id)
    REFERENCES Product_Variant(variant_id);

-- Remove duplicate records before adding unique keys.
SET SQL_SAFE_UPDATES = 0;

DELETE s1
FROM Shipment AS s1
INNER JOIN Shipment AS s2
    ON s1.order_id = s2.order_id
   AND s1.shipment_id > s2.shipment_id;

DELETE w1
FROM Wishlist AS w1
INNER JOIN Wishlist AS w2
    ON w1.user_id = w2.user_id
   AND w1.product_id = w2.product_id
   AND w1.wishlist_id > w2.wishlist_id;

SET SQL_SAFE_UPDATES = 1;

-- Run each only if the corresponding key is not already present.
ALTER TABLE Shipment
    ADD UNIQUE KEY unique_shipment_per_order (order_id);

ALTER TABLE Wishlist
    ADD UNIQUE KEY unique_wishlist_product (user_id, product_id);

UPDATE Product
SET skin_tone = 'all'
WHERE product_id IN (13, 14, 15, 16, 17);

UPDATE Product
SET skin_tone = 'medium'
WHERE product_id = 1;

UPDATE Product
SET skin_type = 'oily'
WHERE product_id IN (3, 23, 26, 30);

UPDATE Product
SET skin_type = 'dry'
WHERE product_id IN (4, 24, 25, 28, 29);

UPDATE Product
SET skin_type = 'combination'
WHERE product_id IN (27, 31, 32);

UPDATE Product
SET skin_type = 'normal'
WHERE product_id IN (33, 34, 35);