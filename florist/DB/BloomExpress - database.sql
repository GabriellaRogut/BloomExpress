CREATE DATABASE BloomExpress;
USE BloomExpress;

CREATE TABLE Gallery (
    galleryPhID INT PRIMARY KEY AUTO_INCREMENT,
    image_path VARCHAR(255) NOT NULL
);

CREATE TABLE PromoCode(
	promoCodeID INT PRIMARY KEY AUTO_INCREMENT,
    promo_code VARCHAR(6),
    type ENUM("Birthday", "New User"),
    expirationDate DATE,
    promotion_value DECIMAL(10, 2) 
);

CREATE TABLE User(
    userID INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(200) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,

    first_name VARCHAR(200),
    last_name VARCHAR(200),
    phone VARCHAR(50),
    address VARCHAR(200),
    city VARCHAR(100),
    ZIPCode VARCHAR(10),
    birthday DATE
);

CREATE TABLE User_PromoCode (
    userID INT NOT NULL,
    promoCodeID INT NOT NULL,
    status ENUM ("Used", "Available", "Expired"),
    PRIMARY KEY (userID, promoCodeID),
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE,
    FOREIGN KEY (promoCodeID) REFERENCES PromoCode(promoCodeID) ON DELETE CASCADE
);


CREATE TABLE Flowers (
    flowerID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    flowerName VARCHAR(100) UNIQUE,
    price DECIMAL(10, 2)
);

CREATE TABLE Ready_Made_Bouquets(
	readyMadeID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    name VARCHAR(50),
    price DECIMAL(10, 2),
    category ENUM("Weddings & Engagements", "Birthdays", "Say ILY", "Congratulations", "Just Because"),
    size ENUM("Small", "Big")
);
 
CREATE TABLE R_M_Bouquets_Flowers (
    readyMadeID INT NOT NULL,
    flowerID INT NOT NULL,
    PRIMARY KEY (readyMadeID, flowerID),
    FOREIGN KEY (readyMadeID) REFERENCES Ready_Made_Bouquets(readyMadeID) 
		ON DELETE CASCADE 
		ON UPDATE CASCADE,
	FOREIGN KEY (flowerID) REFERENCES Flowers(flowerID)
		ON DELETE CASCADE 
		ON UPDATE CASCADE
);

-- CREATE TABLE Custom_Bouquets (
-- 	customID INT PRIMARY KEY AUTO_INCREMENT,
-- 	price DECIMAL(10, 2),
-- 	size ENUM("Small", "Big")
-- );

-- CREATE TABLE Custom_Bouquets_Flowers (
-- 	customID INT NOT NULL,
--     flowerID INT NOT NULL,
--     PRIMARY KEY (customID, flowerID),
--     FOREIGN KEY (customID) REFERENCES Custom_Bouquets(customID) 
-- 		ON DELETE CASCADE 
-- 		ON UPDATE CASCADE,
--     FOREIGN KEY (customID) REFERENCES Flowers(flowerID)
-- 		ON DELETE CASCADE
--         ON UPDATE CASCADE
-- );

CREATE TABLE Cart (
    cartID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    userID INT,
    total_price_cart DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

CREATE TABLE Cart_Items (
    cartItemID INT PRIMARY KEY AUTO_INCREMENT,
    cartID INT NOT NULL,
    readyMadeID INT,
    -- customID INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (cartID) REFERENCES Cart(cartID) ON DELETE CASCADE,
    FOREIGN KEY (readyMadeID) REFERENCES Ready_Made_Bouquets(readyMadeID) ON DELETE CASCADE
    -- FOREIGN KEY (customID) REFERENCES Custom_Bouquets(customID) ON DELETE CASCADE
);


CREATE TABLE Orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    promo_code_order VARCHAR(6),
    orderNum VARCHAR(7),
    userID INT,
    order_status ENUM('Ongoing', 'Past') DEFAULT 'Ongoing',
    placed_on DATE,
    delivery_date DATE,
    pickupShopLocation VARCHAR(255),
    payment_method ENUM('Pay Online', 'Pay on Delivery', "Fetch at Shop") NOT NULL,
    
    email VARCHAR(255),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(10),
    city VARCHAR(50),
    address VARCHAR(200),
    ZIPCode VARCHAR(4),
    cardNum VARCHAR(16),
    expDate char(5),
    cvv INT,
    
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

CREATE TABLE Order_Items (
    orderID INT NOT NULL,
    readyMadeID INT NOT NULL,
    -- customID INT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (orderID, readyMadeID),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (readyMadeID) REFERENCES Ready_Made_Bouquets(readyMadeID)
);


CREATE TABLE GuestUserOrder(
	guestOrderID INT PRIMARY KEY AUTO_INCREMENT,
    orderNum VARCHAR(7),
    order_status ENUM("Ongoing", "Past"),
    placed_on DATE,
    delivery_date DATE,
    payment_method ENUM('Pay Online', 'Pay on Delivery', "Fetch at Shop") NOT NULL,
    pickupShopLocation VARCHAR(70),
    
    email VARCHAR(255),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(10),
    city VARCHAR(50),
    address VARCHAR(200),
    ZIPCode VARCHAR(4),
    
    cardNum VARCHAR(16),
    expDate char(5),
    cvv INT
);


CREATE TABLE Guest_Order_Items(
	guestOrderID INT NOT NULL,
    readyMadeID INT NOT NULL,
    -- customID INT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (guestOrderID, readyMadeID),
    FOREIGN KEY (guestOrderID) REFERENCES GuestUserOrder(guestOrderID) ON DELETE CASCADE,
    FOREIGN KEY (readyMadeID) REFERENCES Ready_Made_Bouquets(readyMadeID)
);



-- INSERTS
INSERT INTO Gallery ( image_path )
VALUES ("images/flower.jpg"),
("images/flower1.jpg"),
("images/flower2.jpg"),
("images/flower3.jpg"),
("images/flower4.jpg"),
("images/flower5.jpg"),
("images/flower6.jpg");

-- INSERT INTO Flowers (flowerName, price)
-- VALUES ("Roses", 2.10),
-- 	("Tulips", 1.10),
--     ("Lillies", 1.10);

-- INSERT INTO Ready_Made_Bouquets (image_path, name, price, category, size)
-- VALUES  ("images/flower1.jpg", "Bouquet", 12.20, "Birthdays", "Small"),
-- 		("images/flower2.jpg", "Bouquet2", 25.90, "Weddings & Engagements", "Big"),
-- 		("images/flower5.jpg", "Bouquet3", 11.40, "Weddings & Engagements", "Small"),
-- 		("images/flower4.jpg", "Bouquet7", 19.50, "Just Because", "Big"),
-- 		("images/flower6.jpg", "Bouquet4", 9.50, "Congratulations", "Small"),
-- 		("images/flower2.jpg", "Bouquet5", 12.90, "Say ILY", "Big"),
-- 		("images/flower1.jpg", "Bouquet6", 15.90, "Say ILY", "Big"),
--         ("images/flower3.jpg", "Bouquet8", 10.90, "Weddings & Engagements", "Small"),
--         ("images/flower1.jpg", "Bouquet9", 17.30, "Weddings & Engagements", "Big");

-- INSERT INTO R_M_Bouquets_Flowers(readyMadeID, flowerID)
-- VALUES(1, 1),
-- 	(1, 2),
--     (1, 3);
--     
-- INSERT INTO R_M_Bouquets_Flowers(readyMadeID, flowerID)
-- VALUES(2, 1),
-- 	(3, 2),
--     (3, 3),
--     (4, 2),
--     (4, 1);
    

-- TRIGGERS
DELIMITER //
CREATE TRIGGER SetPromoPercentageBeforeInsert
BEFORE INSERT ON PromoCode
FOR EACH ROW
BEGIN
    IF NEW.type = 'Birthday' THEN
        SET NEW.promotion_value = 0.1;
        END IF;
    IF NEW.type = 'New User' THEN
        SET NEW.promotion_value = 0.2;
    END IF;
END //
DELIMITER ;

-- DROP TRIGGER SetPromoPercentageBeforeInsert;


-- test
SELECT * FROM GuestUserOrder;
SELECT * FROM Orders;
SELECT * from User;

SELECT * FROM User_PromoCode;
SELECT * FROM PromoCode;

USE BloomExpress;


INSERT INTO Flowers (flowerName, price)
VALUES 
("Roses", 2.10),
("Tulips", 1.10),
("Lilies", 1.30),
("Daisies", 0.90),
("Carnations", 1.00),
("Orchids", 2.50),
("Peonies", 2.20),
("Sunflowers", 1.70),
("Chrysanthemums", 1.40),
("Hydrangeas", 1.80),
("Gardenias", 2.30),
("Ranunculus", 1.60),
("Anemones", 1.50),
("Freesias", 1.20),
("Gladiolus", 1.90),
("Irises", 1.10),
("Lavender", 0.80),
("Marigolds", 1.00),
("Narcissus", 1.30),
("Poppies", 1.40),
("Snapdragons", 1.60),
("Sweet Peas", 1.20),
("Zinnias", 1.00),
("Camellias", 1.50),
("Magnolias", 2.00),
("Protea", 2.40),
("Bluebells", 1.10),
("Asters", 1.30),
("Begonias", 1.00),
("Amaryllis", 2.10);





INSERT INTO Ready_Made_Bouquets (image_path, name, price, category, size)
VALUES
("images/flower1.jpg", "Sunset Serenade", 22.56, "Just Because", "Big"),
("images/flower2.jpg", "Blushing Beauty", 25.34, "Say ILY", "Small"),
("images/flower3.jpg", "Birthday Bliss", 28.91, "Birthdays", "Small"),
("images/flower4.jpg", "Golden Wishes", 19.76, "Congratulations", "Big"),
("images/flower5.jpg", "Scarlet Whisper", 23.58, "Say ILY", "Big"),
("images/flower6.jpg", "Elegant Vows", 27.81, "Weddings & Engagements", "Small"),
("images/flower7.jpg", "Forever Yours", 24.99, "Weddings & Engagements", "Big"),
("images/flower8.jpg", "Petal Parade", 17.49, "Birthdays", "Small"),
("images/flower9.jpg", "Lily Lane", 21.77, "Just Because", "Small"),
("images/flower10.jpg", "Jubilant Joy", 29.65, "Congratulations", "Big"),
("images/flower11.jpg", "Morning Mist", 18.73, "Just Because", "Small"),
("images/flower12.jpg", "Crimson Charm", 26.91, "Say ILY", "Big"),
("images/flower13.jpg", "Peachy Promise", 20.18, "Birthdays", "Big"),
("images/flower14.jpg", "Violet Dreams", 24.55, "Just Because", "Small"),
("images/flower15.jpg", "Orchid Kiss", 19.88, "Weddings & Engagements", "Small"),
("images/flower16.jpg", "Radiant Romance", 28.72, "Say ILY", "Small"),
("images/flower17.jpg", "Sweet Surprise", 23.99, "Congratulations", "Big"),
("images/flower18.jpg", "Twilight Bloom", 21.12, "Birthdays", "Big"),
("images/flower19.jpg", "Whispering Petals", 25.83, "Say ILY", "Small"),
("images/flower20.jpg", "Floral Fiesta", 29.22, "Just Because", "Big"),
("images/flower1.jpg", "Golden Glow", 26.19, "Weddings & Engagements", "Big"),
("images/flower2.jpg", "Honeydew Hug", 17.91, "Congratulations", "Small"),
("images/flower3.jpg", "Pink Promise", 20.77, "Say ILY", "Big"),
("images/flower4.jpg", "Buttercup Breeze", 22.55, "Birthdays", "Small"),
("images/flower5.jpg", "Lover's Lane", 24.66, "Say ILY", "Big"),
("images/flower6.jpg", "Sapphire Sky", 21.33, "Just Because", "Small"),
("images/flower7.jpg", "Spring Waltz", 23.71, "Weddings & Engagements", "Big"),
("images/flower8.jpg", "Amour Toujours", 28.59, "Say ILY", "Small"),
("images/flower9.jpg", "Daisy Daydream", 19.42, "Birthdays", "Big"),
("images/flower10.jpg", "Cherry Bloom", 27.28, "Just Because", "Small"),
("images/flower11.jpg", "Majestic Meadow", 22.11, "Congratulations", "Big"),
("images/flower12.jpg", "Lemon Light", 18.65, "Birthdays", "Small"),
("images/flower13.jpg", "Rose Reverie", 24.89, "Say ILY", "Small"),
("images/flower14.jpg", "Lavender Lace", 23.38, "Just Because", "Big"),
("images/flower15.jpg", "Tulip Tango", 26.24, "Birthdays", "Small"),
("images/flower16.jpg", "Promise Path", 20.99, "Weddings & Engagements", "Big"),
("images/flower17.jpg", "Bright Beginnings", 28.12, "Congratulations", "Small"),
("images/flower18.jpg", "Pastel Haze", 25.17, "Just Because", "Big"),
("images/flower19.jpg", "Forever Bloom", 19.77, "Say ILY", "Small"),
("images/flower20.jpg", "Graceful Garden", 21.86, "Weddings & Engagements", "Small"),
("images/flower1.jpg", "Citrus Charm", 26.52, "Birthdays", "Big"),
("images/flower2.jpg", "Poetic Pink", 22.88, "Just Because", "Small"),
("images/flower3.jpg", "Blossom Bliss", 24.04, "Say ILY", "Big"),
("images/flower4.jpg", "Delightful Dawn", 29.76, "Congratulations", "Small"),
("images/flower5.jpg", "Velvet Heart", 18.35, "Say ILY", "Small"),
("images/flower6.jpg", "Golden Garland", 27.47, "Weddings & Engagements", "Big"),
("images/flower7.jpg", "Garden Grace", 20.18, "Birthdays", "Big"),
("images/flower8.jpg", "Coral Cascade", 23.94, "Just Because", "Small"),
("images/flower9.jpg", "Rose Gold Glow", 28.31, "Congratulations", "Small"),
("images/flower10.jpg", "Berry Bliss", 19.74, "Say ILY", "Big"),
("images/flower11.jpg", "Eternal Ember", 24.22, "Weddings & Engagements", "Big"),
("images/flower12.jpg", "Lush Lilac", 21.69, "Just Because", "Small"),
("images/flower13.jpg", "Petal Passion", 25.91, "Birthdays", "Small"),
("images/flower14.jpg", "Dewdrop Delight", 22.88, "Congratulations", "Big"),
("images/flower15.jpg", "Midnight Petals", 20.43, "Just Because", "Big"),
("images/flower16.jpg", "Velvet Sunset", 29.01, "Say ILY", "Small"),
("images/flower17.jpg", "Creamy Clouds", 18.97, "Weddings & Engagements", "Small"),
("images/flower18.jpg", "Pink Echo", 26.74, "Birthdays", "Big"),
("images/flower19.jpg", "Amethyst Aura", 21.59, "Just Because", "Small"),
("images/flower20.jpg", "Peony Passion", 27.65, "Weddings & Engagements", "Big"),
("images/flower1.jpg", "Love Letters", 23.37, "Say ILY", "Big"),
("images/flower2.jpg", "Floral Harmony", 19.91, "Birthdays", "Small"),
("images/flower3.jpg", "Whimsical Winds", 25.49, "Just Because", "Small"),
("images/flower4.jpg", "Romantic Radiance", 22.71, "Say ILY", "Big"),
("images/flower5.jpg", "Marigold Magic", 24.15, "Congratulations", "Small"),
("images/flower6.jpg", "Serenity Sprig", 20.87, "Birthdays", "Big"),
("images/flower7.jpg", "Frosted Rose", 26.61, "Just Because", "Small"),
("images/flower8.jpg", "Scarlet Sunset", 27.11, "Weddings & Engagements", "Small"),
("images/flower9.jpg", "Blooming Bond", 21.73, "Say ILY", "Big"),
("images/flower10.jpg", "Honey Heart", 25.95, "Just Because", "Big"),
("images/flower11.jpg", "Chic Charm", 18.99, "Congratulations", "Small"),
("images/flower12.jpg", "Azure Allure", 20.54, "Weddings & Engagements", "Big"),
("images/flower13.jpg", "Lavish Lilies", 29.36, "Say ILY", "Small"),
("images/flower14.jpg", "Romance Rush", 23.66, "Birthdays", "Small"),
("images/flower15.jpg", "Twilight Treasure", 26.98, "Just Because", "Big"),
("images/flower16.jpg", "Soft Symphony", 22.89, "Congratulations", "Small"),
("images/flower17.jpg", "Heartfelt Hues", 20.33, "Say ILY", "Big"),
("images/flower18.jpg", "Orchid Oasis", 19.55, "Weddings & Engagements", "Small"),
("images/flower19.jpg", "Sunlit Soirée", 25.63, "Birthdays", "Big"),
("images/flower20.jpg", "Sweet Sonata", 24.48, "Just Because", "Small"),
("images/flower1.jpg", "Pearl Petals", 21.18, "Say ILY", "Small"),
("images/flower2.jpg", "Eternal Spring", 27.77, "Congratulations", "Big"),
("images/flower3.jpg", "Crimson Waltz", 26.02, "Birthdays", "Small"),
("images/flower4.jpg", "Dreamy Drift", 18.74, "Just Because", "Big"),
("images/flower5.jpg", "Charmed Violet", 19.88, "Weddings & Engagements", "Big"),
("images/flower6.jpg", "Rosy Whirl", 23.17, "Say ILY", "Small"),
("images/flower7.jpg", "Sunbeam Sway", 22.92, "Birthdays", "Small"),
("images/flower8.jpg", "Bold Bouquet", 29.79, "Congratulations", "Big"),
("images/flower9.jpg", "Mint Melody", 20.11, "Just Because", "Small"),
("images/flower10.jpg", "Lily Light", 26.21, "Say ILY", "Big"),
("images/flower11.jpg", "Amber Aura", 24.88, "Weddings & Engagements", "Small"),
("images/flower12.jpg", "Rosette Rush", 18.59, "Just Because", "Big"),
("images/flower13.jpg", "Fleur Fantasy", 27.04, "Say ILY", "Small"),
("images/flower14.jpg", "Moonbeam Meadow", 23.29, "Congratulations", "Small"),
("images/flower15.jpg", "Passion Petals", 22.53, "Weddings & Engagements", "Big"),
("images/flower16.jpg", "Velvet Breeze", 25.14, "Say ILY", "Small"),
("images/flower17.jpg", "Satin Sunrise", 21.94, "Birthdays", "Big"),
("images/flower18.jpg", "Aurora Bloom", 26.73, "Just Because", "Small"),
("images/flower19.jpg", "Crimson Crown", 24.38, "Weddings & Engagements", "Big"),
("images/flower20.jpg", "Golden Heart", 28.06, "Congratulations", "Small");

INSERT INTO R_M_Bouquets_Flowers (readyMadeID, flowerID) VALUES
(1, 3), (1, 7), (1, 10),
(2, 1), (2, 11), (2, 19), (2, 25),
(3, 2), (3, 5), (3, 6), (3, 15),
(4, 4), (4, 12), (4, 14),
(5, 8), (5, 13), (5, 21), (5, 26),
(6, 1), (6, 9), (6, 30),
(7, 2), (7, 4), (7, 10), (7, 18),
(8, 3), (8, 6), (8, 23),
(9, 7), (9, 11), (9, 15), (9, 29),
(10, 5), (10, 8), (10, 13),
(11, 1), (11, 17), (11, 21),
(12, 4), (12, 7), (12, 22), (12, 27),
(13, 2), (13, 10), (13, 14),
(14, 3), (14, 5), (14, 6),
(15, 1), (15, 8), (15, 12), (15, 30),
(16, 9), (16, 11), (16, 19),
(17, 4), (17, 7), (17, 13),
(18, 2), (18, 6), (18, 15), (18, 21),
(19, 3), (19, 10), (19, 25),
(20, 1), (20, 14), (20, 18), (20, 27),
(21, 2), (21, 9), (21, 13), (21, 22),
(22, 5), (22, 7), (22, 19),
(23, 3), (23, 6), (23, 15), (23, 29),
(24, 1), (24, 11), (24, 17),
(25, 4), (25, 10), (25, 14), (25, 20),
(26, 7), (26, 12), (26, 23),
(27, 2), (27, 6), (27, 13), (27, 26),
(28, 3), (28, 8), (28, 15),
(29, 1), (29, 10), (29, 25), (29, 27),
(30, 4), (30, 9), (30, 14),
(31, 7), (31, 13), (31, 21), (31, 29),
(32, 2), (32, 5), (32, 10),
(33, 3), (33, 12), (33, 17), (33, 19),
(34, 1), (34, 8), (34, 15),
(35, 4), (35, 7), (35, 22), (35, 26),
(36, 2), (36, 6), (36, 11),
(37, 3), (37, 9), (37, 14), (37, 25),
(38, 1), (38, 10), (38, 15),
(39, 4), (39, 7), (39, 13), (39, 23),
(40, 2), (40, 8), (40, 19),
(41, 3), (41, 5), (41, 12), (41, 20),
(42, 1), (42, 9), (42, 14),
(43, 4), (43, 6), (43, 13), (43, 26),
(44, 2), (44, 7), (44, 15),
(45, 3), (45, 11), (45, 17), (45, 21),
(46, 1), (46, 8), (46, 10),
(47, 4), (47, 12), (47, 22), (47, 27),
(48, 2), (48, 6), (48, 14),
(49, 3), (49, 9), (49, 15), (49, 23),
(50, 1), (50, 5), (50, 10),
(51, 4), (51, 7), (51, 19), (51, 21),
(52, 2), (52, 11), (52, 13),
(53, 3), (53, 6), (53, 15), (53, 25),
(54, 1), (54, 8), (54, 14),
(55, 4), (55, 10), (55, 12), (55, 23),
(56, 2), (56, 7), (56, 19),
(57, 3), (57, 9), (57, 15), (57, 27),
(58, 1), (58, 5), (58, 11),
(59, 4), (59, 6), (59, 14), (59, 20),
(60, 2), (60, 8), (60, 13),
(61, 3), (61, 10), (61, 15), (61, 25),
(62, 1), (62, 7), (62, 12),
(63, 4), (63, 9), (63, 14), (63, 22),
(64, 2), (64, 6), (64, 13),
(65, 3), (65, 8), (65, 15), (65, 27),
(66, 1), (66, 5), (66, 11),
(67, 4), (67, 7), (67, 14), (67, 20),
(68, 2), (68, 10), (68, 13),
(69, 3), (69, 6), (69, 15), (69, 25),
(70, 1), (70, 9), (70, 12),
(71, 4), (71, 7), (71, 14),
(72, 2), (72, 8), (72, 19), (72, 21),
(73, 3), (73, 5), (73, 10),
(74, 1), (74, 6), (74, 13), (74, 25),
(75, 4), (75, 9), (75, 15),
(76, 2), (76, 7), (76, 11), (76, 22),
(77, 3), (77, 6), (77, 13),
(78, 1), (78, 8), (78, 15), (78, 20),
(79, 4), (79, 10), (79, 14),
(80, 2), (80, 5), (80, 11), (80, 27),
(81, 3), (81, 7), (81, 13),
(82, 1), (82, 9), (82, 15),
(83, 4), (83, 6), (83, 10), (83, 21),
(84, 2), (84, 8), (84, 14),
(85, 3), (85, 5), (85, 13), (85, 27),
(86, 1), (86, 7), (86, 12),
(87, 4), (87, 9), (87, 15),
(88, 2), (88, 6), (88, 11), (88, 25),
(89, 3), (89, 10), (89, 13),
(90, 1), (90, 5), (90, 14), (90, 20),
(91, 4), (91, 7), (91, 15),
(92, 2), (92, 9), (92, 13), (92, 22),
(93, 3), (93, 6), (93, 10),
(94, 1), (94, 8), (94, 14), (94, 27),
(95, 4), (95, 5), (95, 15),
(96, 2), (96, 7), (96, 11),
(97, 3), (97, 10), (97, 13), (97, 20),
(98, 1), (98, 6), (98, 15),
(99, 4), (99, 8), (99, 12), (99, 25),
(100, 5), (100, 13), (100, 20);

