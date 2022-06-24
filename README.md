# Simple MySQL
I've made my own database library in PHP and Hack, to speed up development and maximize security.<br>
Example:<br>
$query = $db->query('SELECT * FROM table WHERE id = :id', ['id' => 1]);
