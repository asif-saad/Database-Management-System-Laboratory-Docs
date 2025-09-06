```php
<?php
// Using echo and print for simple output
echo "Welcome to PHP Basics!\n"; // Outputs text with newline
print "This is another way to output.\n"; // Alternative to echo

// Corner Case: Echo with multiple arguments
echo "Hello", " ", "Students!\n"; // Echo concatenates without separator
?>
```


```php
<?php
$integer = 42; // Integer
$float = 3.14; // Float
$string = "Hello, Students"; // String
$boolean = true; // Boolean
$null = null; // Null
$array = [1, 2, 3]; // Array (short syntax)

// Corner Case: Variable naming
$validName = "Valid"; // Valid variable name
$_valid = "Also valid"; // Underscore allowed
// $1invalid = "Invalid"; // Error: Cannot start with number
// $invalid-name = "Invalid"; // Error: Hyphens not allowed

echo "Integer: $integer, Float: $float, String: $string\n";
var_dump($boolean, $null); // Shows type and value
?>
```


```php
<?php
$integer = 42;
$float = 3.14;
$string = "Hello, Students";

$sum = $integer + 10; // Arithmetic
$comparison = ($integer > $float); // Comparison
$logical = ($integer > 0 && true); // Logical
$concat = $string . "!"; // String concatenation

echo "Sum: $sum, Comparison: " . var_export($comparison, true) . ", Concat: $concat\n";

// Corner Case: Type juggling
$numString = "10"; // String
$result = $numString + 5; // PHP converts string to int
echo "Type Juggling: $numString + 5 = $result\n"; // Outputs 15
// Beware: "10abc" + 5 = 15 (PHP takes numeric prefix)
?>
```


```php
<?php
$integer = 42;

if ($integer >= 18) {
    echo "$integer is adult age\n";
} elseif ($integer > 0) {
    echo "$integer is minor age\n";
} else {
    echo "Invalid age\n";
}

// Corner Case: Loose vs Strict Comparison
$zero = 0;
if ($zero == false) {
    echo "Loose comparison: 0 == false\n"; // True due to type juggling
}
if ($zero === false) {
    echo "Strict comparison: 0 === false\n"; // Won't print, strict check fails
}
?>
```


```php
<?php
// For loop
for ($i = 1; $i <= 3; $i++) {
    echo "For Loop: $i\n";
}

// While loop
$count = 1;
while ($count <= 3) {
    echo "While Loop: $count\n";
    $count++;
}

// Corner Case: Infinite loop prevention
$max = 5;
while ($max > 0) {
    echo "Decremented: $max\n";
    $max--;
    if ($max < -1000) { // Safety check
        echo "Safety break to prevent infinite loop\n";
        break;
    }
}
?>
```


```php
<?php
$fruits = ["Apple", "Banana", "Orange"];
$assoc = ["name" => "Alice", "age" => 20];

// Accessing arrays
echo "First fruit: $fruits[0]\n";
echo "Name: {$assoc['name']}\n";

// Corner Case: Undefined index
// echo $fruits[10]; // Warning: Undefined offset
if (isset($fruits[10])) {
    echo "Fruit exists\n";
} else {
    echo "Fruit index 10 does not exist\n";
}
?>
```


```php
<?php
function greet($name = "Guest") { // Default parameter
    return "Hello, $name!\n";
}

echo greet("Student");
echo greet(); // Uses default value

// Corner Case: Pass by value vs reference
function increment($num) {
    $num++;
    return $num;
}
function incrementByRef(&$num) {
    $num++;
}

$number = 5;
echo "Original: $number, After increment: " . increment($number) . ", Original unchanged: $number\n";
incrementByRef($number);
echo "After increment by reference: $number\n"; // Number changes
?>
```



```php
<?php
// Corner Case: Division by zero
$divisor = 0;
if ($divisor !== 0) {
    echo "Division result: " . (10 / $divisor) . "\n";
} else {
    echo "Error: Division by zero prevented\n";
}
?>
```



```php
<?php
$stringNum = "123";
$castedInt = (int)$stringNum;
echo "Casted string to int: $castedInt, Type: " . gettype($castedInt) . "\n";

// Corner Case: Invalid casting
$invalid = "not_a_number";
$castedInvalid = (int)$invalid;
echo "Invalid cast: $castedInvalid\n"; // Outputs 0
?>
```


```php
<?php
define("SITE_NAME", "Learning PHP");
const MAX_USERS = 100;

echo "Constant: " . SITE_NAME . ", Max Users: " . MAX_USERS . "\n";

// Corner Case: Constant redefinition
// define("SITE_NAME", "New Name"); // Error: Constant already defined
?>
```