```sql
-- Connect to test database
USE test;

-- Create main tables
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100),
    price DECIMAL(10,2),
    stock_quantity INT DEFAULT 0,
    category VARCHAR(50)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'confirmed', 'shipped', 'delivered') DEFAULT 'pending',
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    book_id INT,
    quantity INT,
    unit_price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);
```


```sql
-- Drop existing users if they exist
DROP USER IF EXISTS 'store_admin'@'localhost';
DROP USER IF EXISTS 'sales_clerk'@'localhost';
DROP USER IF EXISTS 'customer_user'@'localhost';

-- Now create the users
CREATE USER 'store_admin'@'localhost' IDENTIFIED BY 'admin123';
CREATE USER 'sales_clerk'@'localhost' IDENTIFIED BY 'clerk123';
CREATE USER 'customer_user'@'localhost' IDENTIFIED BY 'customer123';

-- Grant permissions for test database
GRANT ALL PRIVILEGES ON test.* TO 'store_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE ON test.orders TO 'sales_clerk'@'localhost';
GRANT SELECT ON test.books TO 'sales_clerk'@'localhost';
GRANT SELECT, INSERT ON test.customers TO 'customer_user'@'localhost';
GRANT SELECT ON test.books TO 'customer_user'@'localhost';

-- Flush privileges to apply changes
FLUSH PRIVILEGES;
```


```sql
USE test;

INSERT INTO books (title, author, price, stock_quantity, category) VALUES
('Database Systems', 'Elmasri & Navathe', 89.99, 50, 'Technology'),
('Clean Code', 'Robert Martin', 45.50, 30, 'Programming'),
('The Alchemist', 'Paulo Coelho', 15.99, 100, 'Fiction');

INSERT INTO customers (username, email, password_hash, full_name) VALUES
('john_doe', 'john@email.com', SHA2('password123', 256), 'John Doe'),
('jane_smith', 'jane@email.com', SHA2('mypass456', 256), 'Jane Smith');
```


```sql
USE test;

-- This view hides the password from customers table
CREATE VIEW safe_customers AS
SELECT customer_id, username, email, full_name, registration_date
FROM customers;

-- Test the view
SELECT * FROM safe_customers;
```


```sql
-- This view shows only books that are in stock
CREATE VIEW available_books AS
SELECT book_id, title, author, price
FROM books
WHERE stock_quantity > 0;

-- Test the view
SELECT * FROM available_books;
```


```sql
-- This view combines customer and order information
CREATE VIEW customer_order_history AS
SELECT 
    c.username,
    c.full_name,
    o.order_id,
    o.order_date,
    o.total_amount,
    o.status
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id;

-- Test the view
SELECT * FROM customer_order_history WHERE username = 'john_doe';
```


```sql
USE test;

DELIMITER //
CREATE TRIGGER reduce_stock
    AFTER INSERT ON order_items
    FOR EACH ROW
BEGIN
    UPDATE books 
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE book_id = NEW.book_id;
END//
DELIMITER ;
```


```sql
-- Check current stock
SELECT title, stock_quantity FROM books WHERE book_id = 1;

-- Place an order (this will trigger stock reduction)
INSERT INTO orders (customer_id, status) VALUES (1, 'pending');
INSERT INTO order_items (order_id, book_id, quantity, unit_price) 
VALUES (LAST_INSERT_ID(), 1, 3, 89.99);

-- Check stock again - it should be reduced by 3
SELECT title, stock_quantity FROM books WHERE book_id = 1;
```


```sql
USE test;

-- Create indexes for common queries
CREATE INDEX idx_customer_email ON customers(email);
CREATE INDEX idx_customer_username ON customers(username);
CREATE INDEX idx_book_category ON books(category);
CREATE INDEX idx_order_date ON orders(order_date);
CREATE INDEX idx_order_status ON orders(status);

-- Test query performance
EXPLAIN SELECT * FROM books WHERE category = 'Technology' AND price < 50.00;
EXPLAIN SELECT o.*, c.username FROM orders o JOIN customers c ON o.customer_id = c.customer_id WHERE o.order_date > '2024-01-01';
```


```sql
USE test;

-- 1. Atomicity: Complete order placement transaction
START TRANSACTION;

-- Insert new order
INSERT INTO orders (customer_id, status) VALUES (1, 'pending');
SET @order_id = LAST_INSERT_ID();

-- Add order items
INSERT INTO order_items (order_id, book_id, quantity, unit_price) VALUES 
(@order_id, 1, 2, 89.99),
(@order_id, 2, 1, 45.50);

-- Check if sufficient stock exists
SELECT book_id, stock_quantity FROM books WHERE book_id IN (1, 2);

-- Commit the transaction if everything is correct
COMMIT;

-- 2. Rollback demonstration
START TRANSACTION;

INSERT INTO orders (customer_id, status) VALUES (2, 'pending');
SET @order_id = LAST_INSERT_ID();

-- Simulate an error condition
INSERT INTO order_items (order_id, book_id, quantity, unit_price) VALUES 
(@order_id, 3, 150, 15.99); -- Trying to order more than available

-- Rollback the transaction
ROLLBACK;

-- Verify rollback worked
SELECT COUNT(*) FROM orders WHERE customer_id = 2;
```