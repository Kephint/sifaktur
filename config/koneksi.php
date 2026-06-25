<?php


require_once __DIR__ . '/../classes/Database.php';


$db = Database::getInstance();
$conn = $db->getConnection();


$base_url = '/ujikom';
