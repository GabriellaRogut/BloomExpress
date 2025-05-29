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
    promotion_value DECIMAL 
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

INSERT INTO Flowers (flowerName, price)
VALUES ("Roses", 2.10),
	("Tulips", 1.10),
    ("Lillies", 1.10);

INSERT INTO Ready_Made_Bouquets (image_path, name, price, category, size)
VALUES  ("images/flower1.jpg", "Bouquet", 12.20, "Birthdays", "Small"),
		("images/flower2.jpg", "Bouquet2", 25.90, "Weddings & Engagements", "Big"),
		("images/flower5.jpg", "Bouquet3", 11.40, "Weddings & Engagements", "Small"),
		("images/flower4.jpg", "Bouquet7", 19.50, "Just Because", "Big"),
		("images/flower6.jpg", "Bouquet4", 9.50, "Congratulations", "Small"),
		("images/flower2.jpg", "Bouquet5", 12.90, "Say ILY", "Big"),
		("images/flower1.jpg", "Bouquet6", 15.90, "Say ILY", "Big"),
        ("images/flower3.jpg", "Bouquet8", 10.90, "Weddings & Engagements", "Small"),
        ("images/flower1.jpg", "Bouquet9", 17.30, "Weddings & Engagements", "Big");

INSERT INTO R_M_Bouquets_Flowers(readyMadeID, flowerID)
VALUES(1, 1),
	(1, 2),
    (1, 3);
    
INSERT INTO R_M_Bouquets_Flowers(readyMadeID, flowerID)
VALUES(2, 1),
	(3, 2),
    (3, 3),
    (4, 2),
    (4, 1);
    

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


