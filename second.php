<?php
try {
    $dbConnect = new PDO('mysql:host=localhost;dbname=test', 'book', '12345qwe');
    $dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbConnect->query("CREATE TABLE 
IF NOT EXISTS
`logs` (
`created_at` DATETIME NOT NULL DEFAULT NOW(),
`text` TEXT,
INDEX logs_created_at (`created_at`)
)");

    $dbConnect->query("CREATE TABLE 
IF NOT EXISTS
`archived_logs` (
`created_at` DATETIME NOT NULL DEFAULT NOW(),
`text` TEXT,
INDEX logs_created_at (`created_at`)
)");


} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


try {

    $dbConnect->beginTransaction();
    $getDataSql = 'insert into archived_logs select * from logs  where created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH)';
    $get = $dbConnect->prepare($getDataSql);
    $get->execute();
    $delOldData = 'delete from logs where created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH)';
    $del = $dbConnect->prepare($delOldData);
    $del->execute();
    $dbConnect->commit();

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
}
