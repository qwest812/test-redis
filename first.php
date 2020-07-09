<?php
try {
    $dbConnect = new PDO('mysql:host=localhost;dbname=test', 'book', '12345qwe');
    $dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbConnect->query("CREATE TABLE IF NOT EXISTS `test`.`books` ( `id` INT NOT NULL AUTO_INCREMENT , `row` MEDIUMTEXT NOT NULL , PRIMARY KEY (`id`), UNIQUE (`row`(100)))");
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

if (file_exists("book.txt")) {
    $handle = fopen("book.txt", "r");
    echo "<pre>";

    echo "</pre>";
    if ($handle) {
        while (!feof($handle)) {
            $row = fgets($handle);
            try {
                $sqlInsert =("INSERT INTO `books`( `row`) VALUES ('{$row}')");

                $dbConnect->query($sqlInsert);

            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
            }
        }
        fclose($handle);
    }
}
