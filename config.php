<?php
//Database config variables
$dbconfig = array();
$dbconfig['host'] = ':/cloudsql/wechat-south-africa:qmanager-us';
$dbconfig['user'] = 'root';
$dbconfig['pass'] = '';
$dbconfig['db'] = 'test';



//Authentication Variables

$data = array();
$data[] = 'TwoCows3Dogs';
$data[] = $_GET['timestamp'];
$data[] = $_GET['nonce'];