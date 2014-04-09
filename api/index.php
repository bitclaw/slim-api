<?php

// http://127.0.0.1/slim-api/api/index.php/Daniel
// Make sure Virtual directory has AllowOverride All enabled
// curl http://127.0.0.1/slim-api/api/install{"action":"install","success":true,"id":0}
// propel reverse "mysql:host=localhost;dbname=bookmark;user=root;password=RootCognox"
//Propel is not instantiated by creating a class.
//
//Here is a sample initialization file:
//
//<?php
//// Include the main Propel script require_once '/path/to/propel/runtime/lib/Propel.php';
//
//// Initialize Propel with the runtime configuration Propel::init("/path/to/bookstore/build/conf/bookstore-conf.php");
//
//// Add the generated 'classes' directory to the include path set_include_path("/path/to/bookstore/build/classes" . PATH_SEPARATOR . get_include_path());
//
//the above is taken from propel documentation.
//
//BUT STILL, PASSING $app TO THE FUNCTION SEEM TO WORK FOR NOW!
//SLIM IS AWESOME!
//
//will post if any problems are found as going deep using Slim for creating REST Services with Propel.

require '../vendor/autoload.php';

$app = new \Slim\Slim();
$app->contentType('application/json');
//$db = new PDO('sqlite:db.sqlite3');
//$db = new PDO('sqlite:.subscribers.db');
//$db = new SQLite3('mysqlitedb.db');
//$db = new \SQLite3('mysqlitedb.db');
$db = new PDO('mysql:host=localhost;dbname=bookmark', 'root', 'root');

//$app->get('/:name', function ($name) {
//    echo "Hello, $name";
//});

function getTitleFromUrl($url)
{
    preg_match('/<title>(.+)<\/title>/', file_get_contents($url), $matches);
    return mb_convert_encoding($matches[1], 'UTF-8', 'UTF-8');
}

function returnResult($action, $success = true, $id = 0)
{
    echo json_encode([
        'action' => $action,
        'success' => $success,
        'id' => intval($id),
    ]);
}

$app->get('/bookmark', function () use ($db, $app) {
    $sth = $db->query('SELECT * FROM bookmark;');
    echo json_encode($sth->fetchAll(PDO::FETCH_CLASS));
});

$app->get('/bookmark/:id', function ($id) use ($db, $app) {
    $sth = $db->prepare('SELECT * FROM bookmark WHERE id = ? LIMIT 1;');
    $sth->execute([intval($id)]);
    echo json_encode($sth->fetchAll(PDO::FETCH_CLASS)[0]);
});

$app->post('/bookmark', function () use ($db, $app) {
    $title = $app->request()->post('title');
    $sth = $db->prepare('INSERT INTO bookmark (url, title) VALUES (?, ?);');
    $sth->execute([
        $url = $app->request()->post('url'),
        empty($title) ? getTitleFromUrl($url) : $title,
    ]);

    returnResult('add', $sth->rowCount() == 1, $db->lastInsertId());
});

$app->put('/bookmark/:id', function ($id) use ($db, $app) {
    $sth = $db->prepare('UPDATE bookmark SET title = ?, url = ? WHERE id = ?;');
    $sth->execute([
        $app->request()->post('title'),
        $app->request()->post('url'),
        intval($id),
    ]);

    returnResult('edit', $sth->rowCount() == 1, $id);
});

$app->delete('/bookmark/:id', function ($id) use ($db) {
    $sth = $db->prepare('DELETE FROM bookmark WHERE id = ?;');
    $sth->execute([intval($id)]);

    returnResult('delete', $sth->rowCount() == 1, $id);
});

$app->get('/install', function () use ($db) {
    $db->exec('  CREATE TABLE IF NOT EXISTS bookmark (
                    id INTEGER PRIMARY KEY,
                    title TEXT,
                    url TEXT UNIQUE);');

    returnResult('install');
});

$app->run();
