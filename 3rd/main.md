## Lab-3: SELECT, WHERE, RENAME, ALIAS, OPERATORS, DISTINCT, LIMIT, BETWEEN, LIKE, NULL, ORDER BY, IN, NOT IN, CONCATENATION


```sql
CREATE TABLE students (
  id INT,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  age INT,
  gender VARCHAR(10),
  department VARCHAR(30),
  gpa DECIMAL(3,2),
  email VARCHAR(100)
);

INSERT INTO students VALUES
(1, 'Alice', 'Smith', 21, 'Female', 'CSE', 3.70, 'alice@univ.edu'),
(2, 'Bob', 'Johnson', 23, 'Male', 'EEE', 3.40, 'bob@univ.edu'),
(3, 'Charlie', 'Brown', 20, 'Male', 'BBA', 3.20, 'charlie@univ.edu'),
(4, 'Diana', 'Scott', 22, 'Female', 'CSE', 3.90, NULL),
(5, 'Evan', 'Taylor', 21, 'Male', 'LAW', 3.10, 'evan@univ.edu'),
(6, 'Fatima', 'Ali', 24, 'Female', 'EEE', 3.85, 'fatima@univ.edu');
```







```sql
-- Select all columns
SELECT * FROM students;

-- Select specific columns
SELECT first_name, age FROM students;
```




```sql
-- Fetch students older than 21
SELECT * FROM students WHERE age > 21;
```


```sql
-- Rename column: PostgreSQL syntax
ALTER TABLE students RENAME COLUMN first_name TO fname;
```

```sql
-- Use alias for column and table
SELECT first_name AS fname, last_name AS lname FROM students AS s;
```



```sql
-- Arithmetic and comparison operators
SELECT first_name, gpa, gpa + 0.1 AS updated_gpa FROM students WHERE age >= 21 AND gpa > 3.5;
```



```sql
-- Get unique departments
SELECT DISTINCT department FROM students;
```



```sql
-- Show only 3 rows
SELECT * FROM students LIMIT 3;
```



```sql
-- Students aged between 21 and 23
SELECT * FROM students WHERE age BETWEEN 21 AND 23;
```



```sql
-- Email with domain univ.edu
SELECT * FROM students WHERE email LIKE '%@univ.edu';
```



```sql
-- Students with missing email
SELECT * FROM students WHERE email IS NULL;
```



```sql
-- Order by GPA descending
SELECT * FROM students ORDER BY gpa DESC;
```



```sql
-- Students from CSE or EEE
SELECT * FROM students WHERE department IN ('CSE', 'EEE');

-- Exclude CSE and EEE
SELECT * FROM students WHERE department NOT IN ('CSE', 'EEE');
```




```sql
-- Full name
SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM students;
```