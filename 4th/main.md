```sql
/* Setup: Create Database and Tables */
DROP DATABASE IF EXISTS LabRetail;
CREATE DATABASE LabRetail;
USE LabRetail;

CREATE TABLE Customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name  VARCHAR(50),
    last_name   VARCHAR(50),
    birth_date  DATE,
    country     VARCHAR(50) NOT NULL
);

CREATE TABLE Orders (
    order_id    INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date  DATETIME,
    amount      DECIMAL(10,2),
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    CHECK (amount >= 0)
);
```





```sql
/* Intermediate: Bulk insert 9 more customers */
INSERT INTO Customers (first_name, last_name, birth_date, country) VALUES
('Mira', 'Rahman', '1995-07-04', 'Bangladesh'),
('Luis', 'Silva', '1988-01-23', 'Brazil'),
('Sofia', 'Gonzalez', '1992-11-15', 'Mexico'),
('John', 'Smith', '1985-05-30', 'USA'),
('Anna', 'Ivanova', '1993-09-19', 'Russia'),
('Chen', 'Wang', '1987-06-08', 'China'),
('Fatima', 'Al-Sayed', '1991-08-22', 'Egypt'),
('James', 'Brown', '1980-01-10', 'UK'),
('Elena', 'Popescu', '1994-12-02', 'Romania');

-- Check all customers
SELECT COUNT(*) AS total_customers FROM Customers;

```


```sql
/* Intermediate: Insert 12 orders */
INSERT INTO Orders (customer_id, order_date, amount) VALUES
(1, '2025-07-10 10:30:00', 1500.00),
(2, '2025-07-05 08:15:00', 3000.00),
(3, '2025-07-25 14:00:00', 750.00),
(4, '2025-07-20 13:30:00', 5430.75),
(5, '2025-07-22 15:00:00', 1000.00),
(6, '2025-07-11 09:45:00', 980.25),
(7, '2025-07-18 17:15:00', 1340.00),
(8, '2025-07-10 12:05:00', 2030.00),
(9, '2025-07-14 11:25:00', 1675.00),
(10, '2025-07-19 14:30:00', 1810.00),
(1, '2025-07-24 13:20:00', 410.00),
(5, '2025-07-26 17:05:00', 3300.00);

-- Verify orders
SELECT COUNT(*) AS total_orders FROM Orders;


```



```sql
/* Easy: Simple UPDATE - Fix a name */
UPDATE Customers
SET first_name = 'Meera'
WHERE customer_id = 2;

-- Check the change
SELECT customer_id, first_name, last_name FROM Customers WHERE customer_id = 2;


```


```sql
/* Intermediate: Conditional UPDATE - Update multiple records */
UPDATE Customers
SET country = 'United Kingdom'
WHERE country = 'UK';

-- Update order amounts with a 10% increase for high-value orders
UPDATE Orders
SET amount = amount * 1.10
WHERE amount > 2000;

-- Check results
SELECT * FROM Customers WHERE country = 'United Kingdom';
SELECT order_id, amount FROM Orders WHERE amount > 2000;


```


```sql
/* Easy: Simple DELETE - Remove one order */
DELETE FROM Orders WHERE order_id = 3;

-- Verify deletion
SELECT COUNT(*) AS remaining_orders FROM Orders;


```

```sql
/* Intermediate: DELETE with FK constraints */
-- This will FAIL due to foreign key constraint
DELETE FROM Customers WHERE customer_id = 7;

-- First delete the customer's orders
DELETE FROM Orders WHERE customer_id = 7;

-- Now delete the customer (this will succeed)
DELETE FROM Customers WHERE customer_id = 7;

-- Verify customer 7 is gone
SELECT * FROM Customers WHERE customer_id = 7;


```

```sql
/* Easy: Basic date and time functions */
SELECT 
    NOW() AS current_datetime,
    CURDATE() AS current_date,
    CURTIME() AS current_time,
    YEAR(CURDATE()) AS current_year,
    MONTH(CURDATE()) AS current_month,
    DAY(CURDATE()) AS current_day;


```

```sql
/* Intermediate: Date arithmetic and formatting */
SELECT 
    order_id,
    order_date,
    DATE_ADD(order_date, INTERVAL 5 DAY) AS expected_delivery,
    DATE_FORMAT(order_date, '%Y-%m-%d') AS formatted_date,
    DATEDIFF(CURDATE(), order_date) AS days_since_order
FROM Orders
ORDER BY order_date;
```

```sql
/* Easy: Basic aggregate functions */
SELECT 
    COUNT(*) AS total_orders,
    SUM(amount) AS total_revenue,
    AVG(amount) AS average_order_value,
    MAX(amount) AS highest_order,
    MIN(amount) AS lowest_order
FROM Orders;

```

```sql
/* Intermediate: Aggregates by customer */
SELECT 
    customer_id,
    COUNT(*) AS order_count,
    SUM(amount) AS total_spent,
    AVG(amount) AS avg_order_value,
    MAX(amount) AS largest_order,
    MIN(amount) AS smallest_order
FROM Orders
GROUP BY customer_id
ORDER BY total_spent DESC;


```

```sql
/* Easy: Simple GROUP BY */
SELECT 
    country,
    COUNT(*) AS customer_count
FROM Customers
GROUP BY country
ORDER BY customer_count DESC;


```

```sql
/* Intermediate: GROUP BY with HAVING clause */
SELECT 
    c.customer_id,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    c.country,
    COUNT(o.order_id) AS order_count,
    SUM(o.amount) AS total_spent
FROM Customers c
JOIN Orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.first_name, c.last_name, c.country
HAVING total_spent > 2000
ORDER BY total_spent DESC;


```

```sql
/* Easy: INNER JOIN - Orders with customer details */
SELECT 
    o.order_id,
    o.order_date,
    c.first_name,
    c.last_name,
    c.country,
    o.amount
FROM Orders o
INNER JOIN Customers c ON o.customer_id = c.customer_id
ORDER BY o.order_date;


```

```sql
/* Intermediate: LEFT JOIN - Find customers without orders */
SELECT 
    c.customer_id,
    c.first_name,
    c.last_name,
    c.country,
    o.order_id
FROM Customers c
LEFT JOIN Orders o ON c.customer_id = o.customer_id
WHERE o.order_id IS NULL;

-- Also show customers WITH their order counts
SELECT 
    c.customer_id,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    COUNT(o.order_id) AS order_count
FROM Customers c
LEFT JOIN Orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.first_name, c.last_name
ORDER BY order_count DESC;


```

```sql
/* Easy: Simple subquery - Orders above average */
SELECT 
    order_id,
    customer_id,
    amount
FROM Orders
WHERE amount > (SELECT AVG(amount) FROM Orders)
ORDER BY amount DESC;
```

```sql
/* Intermediate: Correlated subquery */
-- Find orders that are above each customer's own average
SELECT 
    o1.order_id,
    o1.customer_id,
    o1.amount,
    (SELECT AVG(o2.amount) FROM Orders o2 WHERE o2.customer_id = o1.customer_id) AS customer_avg
FROM Orders o1
WHERE o1.amount > (
    SELECT AVG(o2.amount)
    FROM Orders o2
    WHERE o2.customer_id = o1.customer_id
)
ORDER BY o1.customer_id, o1.amount DESC;
```

```sql
/* Easy: Demonstrate FK constraint violation */
-- This INSERT will FAIL due to foreign key constraint
-- INSERT INTO Orders (customer_id, order_date, amount)
-- VALUES (999, '2025-08-01 10:00:00', 500.00);

-- Uncomment above line to see the error, then comment it back
SELECT 'Foreign key constraint prevents inserting orders for non-existent customers' AS message;

```

```sql
/* Intermediate: Demonstrate CHECK constraint violation */
-- This INSERT will FAIL due to CHECK constraint (amount >= 0)
-- INSERT INTO Orders (customer_id, order_date, amount)
-- VALUES (1, '2025-08-01 10:00:00', -100.00);

-- Uncomment above line to see the error, then comment it back
SELECT 'CHECK constraint prevents inserting negative amounts' AS message;

-- Show constraint information
SHOW CREATE TABLE Orders;
```

```sql
/* Optional: Cleanup database */
-- Uncomment the line below if you want to remove the database
-- DROP DATABASE LabRetail;

-- Or just show final statistics
SELECT 
    'Lab completed successfully!' AS status,
    (SELECT COUNT(*) FROM Customers) AS total_customers,
    (SELECT COUNT(*) FROM Orders) AS total_orders,
    (SELECT SUM(amount) FROM Orders) AS total_revenue;
```



### Order of Precedence

- FROM
- WHERE
- GROUP BY
- HAVING
- SELECT
- ORDER BY
- LIMIT/OFFSET





### Named CHECK constraint on multiple columns
```sql
CREATE TABLE Persons (
    ID INT NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    FirstName VARCHAR(255),
    Age INT,
    City VARCHAR(255),
    CONSTRAINT CHK_Person CHECK (Age >= 18 AND City = 'Sandnes')
);
```