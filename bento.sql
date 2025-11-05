CREATE DATABASE IF NOT EXISTS bento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bento;

-- HOMEPAGE PRODUCTS TABLE
DROP TABLE IF EXISTS homeproducts;
CREATE TABLE homeproducts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  category ENUM('classic','classic') NOT NULL,
  price DECIMAL(7,2) NOT NULL,
  description TEXT,
  image_url VARCHAR(255) DEFAULT NULL
);

INSERT INTO homeproducts (name, category, price, description, image_url) VALUES
('Teriyaki Chicken Bento','classic',6.50,'Grilled chicken in teriyaki glaze, steamed rice, pickles, salad','assets/images/mains/Classic_TeriyakiChicken.jpg'),
('Salmon Mentaiko Bento','classic',8.50,'Seared salmon with furikake rice, miso soup and vegetables','assets/images/mains/Classic_SalmonMentai.jpg'),
('Tofu Veggie Bento','classic',5.60,'Crispy tofu, edamame, quinoa, sesame dressing','assets/images/mains/Classic_TofuVeg.png'),
('Tempura Bento','classic',8.50,'Ebi tempura fried golden, rice, miso soup and vegetables','assets/images/mains/Classic_EbiTemp.jpg'),
('Mushroom Veggie Bento','classic',5.80,'Braised shiitake mushrooms, mixed vegetables, fried tofu, rice and miso soup','assets/images/mains/Classic_MushrooomVeg.png'),
('Tofu Medley Bento','classic',5.60,'Steamed tofu with light soy-sauce, fried tofu puffs, stir-fried vegetables, rice and miso soup','assets/images/mains/Classic_TofuMedley.png'),
('Katsu Curry Bento','classic',7.50,'Chicken katsu with Japanese curry and seasonal greens','assets/images/mains/Classic_KatsuCurry.jpg'),
('Beef Sukiyaki Bento','classic',8.20,'Beef slices simmered in sweet soy sauce, rice, tofu, and vegetables','assets/images/mains/Classic_BeefSukiyaki.jpg'),
('Spicy Miso Pork Bento','classic',8.00,'Miso-marinated pork with kimchi and rice','assets/images/mains/Classic_SpicyPork.jpg'),
('Beef Curry Bento','classic',8.50,'Beef slices with Japanese curry and seasonal greens','assets/images/mains/Classic_BeefCurry.jpg'),
('Pork Sukiyaki Bento','classic',7.20,'Pork slices simmered in sweet soy sauce, rice, tofu, and vegetables','assets/images/mains/Classic_PorkSukiyaki.jpg'),
('Spicy Miso Veggie Bento','classic',8.00,'Miso-marinated vegetables with kimchi and rice','assets/images/mains/Classic_MisoVeg.png'),

('Spicy Cucumber Salad','side',4.50,'Diced cucumber marinated in chilli oil, soy sauce and diced garlic','assets/images/sides/Sides_SpicyCucumber.jpg'),
('Golden Enoki','side',5.00,'Battered enoki deep-fried to golden perfection. Served with choice of mentaiko sauce or wasabi mayo','assets/images/sides/Sides_GoldenEnoki.jpg'),
('Onsen Egg','side',2.00,'Half-boiled egg in light soy sauce','assets/images/sides/Sides_OnsenEgg.jpg'),
('Tamagoyaki','side',3.50,'Fluffy, sweet egg rolls','assets/images/sides/Sides_Tamago.jpg'),

('Matcha Green Tea','drink',3.50,'Ceremonial-grade matcha','assets/images/drinks/Drinks_Matcha.jpg'),
('Matcha Latte','drink',4.50,'Ceremonial-grade matcha with whole milk','assets/images/drinks/Drinks_MatchaLatte.jpg'),
('Hojicha Latte','drink',4.50,'Ceremonial-grade hojicha with whole milk','assets/images/drinks/Drinks_HojichaLatte.jpg'),
('Calpis Soda','drink',3.50,'Refreshing fizzy yoghurt drink','assets/images/drinks/Drinks_Calpis.jpg'),
('Cafe Au Lait','drink',3.50,'Milk coffee','assets/images/drinks/Drinks_CafeAuLait.jpg'),
('Cold Brew','drink',3.50,'Black coffee from beans steeped in cold water','assets/images/drinks/Drinks_BlackCoffee.jpg'),

('Ice Cream Mochi (3 pcs)','dessert',6.90,'Beat the heat with our ice cream mochis','assets/images/desserts/Desserts_Mochi.jpg'),
('Strawberry Daifuku (3 pcs)','dessert',6.00,'Tangy, whole strawberries encased in sweet red bean and chewy mochi','assets/images/desserts/Desserts_StrawberryDaifuku.jpg'),
('Matcha Crepe Cake','dessert',6.90,'Matcha crepe cake layered with light cream. Topped with house-made strawberry jam','assets/images/desserts/Desserts_CrepeCake.jpg');

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
('Samantha',5,'Absolutely delicious! Great value for money.','assets/images/User_Samantha.jpg'),
('Marcus',4,'The bentos taste great and the portions are generous.','assets/images/User_Marcus.jpg'),
('Priya',5,'Loved the variety and the freshness!','assets/images/User_Priya.jpg'),
('Jin',4,'The spicy miso is my go-to. Would love more sides!','assets/images/User_Jin.jpg');

CREATE TABLE newsletter_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

