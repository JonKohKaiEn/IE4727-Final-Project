CREATE DATABASE IF NOT EXISTS bento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bento;

-- HOMEPAGE PRODUCTS TABLE
DROP TABLE IF EXISTS homeproducts;
CREATE TABLE homeproducts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  category ENUM('offer','new') NOT NULL,
  price DECIMAL(7,2) NOT NULL,
  description TEXT,
  image_url VARCHAR(255) DEFAULT NULL
);

INSERT INTO homeproducts (name, category, price, description, image_url) VALUES
-- PROMO PRODUCTS
('Teriyaki Chicken Bento','offer',6.50,'Grilled chicken in teriyaki glaze, steamed rice, pickles, salad','assets/images/#'),
('Salmon Furikake Bento','offer',8.50,'Seared salmon with furikake rice, miso soup and vegetables','assets/images/#'),
('Tofu Veggie Bento','offer',5.60,'Crispy tofu, edamame, quinoa, sesame dressing','assets/images/#'),
('Tempura Bento','offer',8.50,'Assorted tempura fried golden, rice, miso soup and vegetables','assets/images/#'),
('Mushroom Veggie Bento','offer',5.80,'Braised shiitake mushrooms, mixed vegetables, fried tofu, rice and miso soup','assets/images/#'),
('Tofu Medley Bento','offer',5.60,'Steamed tofu with light soy-sauce, fried tofu puffs, stir-fried vegetables, rice and miso soup','assets/images/#'),
-- NEW PRODUCTS
('Katsu Curry Bento','new',7.50,'Chicken katsu with Japanese curry and seasonal greens','assets/images/#'),
('Beef Sukiyaki Bento','new',8.20,'Beef slices simmered in sweet soy sauce, rice, tofu, and vegetables','assets/images/#'),
('Spicy Miso Pork Bento','new',8.00,'Miso-marinated pork with kimchi and rice','assets/images/#'),
('Beef Curry Bento','new',8.50,'Beef slices with Japanese curry and seasonal greens','assets/images/#'),
('Chicken Sukiyaki Bento','new',7.20,'Chicken slices simmered in sweet soy sauce, rice, tofu, and vegetables','assets/images/#'),
('Spicy Miso Veggie Bento','new',8.00,'Miso-marinated vegetables with kimchi and rice','assets/images/#');

-- REVIEWS TABLE
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80),
  rating DECIMAL (2,1) NOT NULL,
  comment TEXT,
  avatar_url VARCHAR(255)
);

INSERT INTO reviews (name, rating, comment, avatar_url) VALUES
('Samantha',5,'Absolutely delicious! Great value for money.','assets/images/#'),
('Marcus',4,'The bentos taste great and the portions are generous.','assets/images/#'),
('Priya',5,'Loved the variety and the freshness!','assets/images/#'),
('Jin',4.5,'The spicy miso is my go-to. Would love more sides!','assets/images/#');
