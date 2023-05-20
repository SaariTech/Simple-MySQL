# Simple MySQL
I developed my own PHP and Hack database library to speed up the development efficiency and ensure maximum security.

Here's an example of how the library works:
```
$query = $db->query('SELECT * FROM table WHERE id = :id OR name = :name', 
  [
    'id' => 1337,
    'name' => 'Saari'
  ]
);
```
With this small library, you can execute database queries in a single line of code.
