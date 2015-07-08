<?php
session_start();
include 'config.php';
include 'database.php';
include 'wechat.php';

//$db = new Database($dbconfig);
//
//$sql =  "UPDATE counter SET count_number = count_number + 1";
//$db->query($sql);
//
//$sql =  "SELECT * FROM counter";
//$results = $db->query($sql);
//
//$count = $db->getResults($results);

$now = time();

//$xml = simplexml_load_string($row['xml_in']);

$wechat = new wechat();

$accessToken = $wechat->getAccessToken();

//var_dump($_SESSION);


    $touser = 'ohwRntyT5ZxXDaOdj0-L6uCfIcGE';

    $jsonOutput = json_encode(array(
        'touser' => $touser,
        'msgtype' => 'text',
        'text' => array(
            'content' => 'It works! PUSH SCRIPT '.$count['count_number']
        )

    ));


    $test = $wechat->curl_download('https://api.wechat.com/cgi-bin/message/custom/send?access_token='.$accessToken,$jsonOutput, true);
// end reply


//    $sql = 'INSERT INTO xml_logs (`xml_in`, `xml_out`) VALUES ("code: '.$test->errcode.'  message: '.$test->errmsg.' counter: '.$count['count_number'].'", "<pre>'.var_export($test, true).'</pre>")';
//    $db->query($sql);
    //Echo response from API
    echo '<pre>';
    var_dump($test);
    echo '</pre>';


//
////shows rows
//while ($row = $data->fetch_array()) {
//    $xml = simplexml_load_string($row['xml_in']);
//    echo '<form action="admin" method="post">';
//    echo $xml . '<br><br>';
//    echo $row['created'] . " " . '<input type = "hidden" name = "id" value="' . $row['id'] . '">' . " -- " . '<input type="hidden" name="FromUserName" value = "' . $xml->FromUserName . '">'. $xml->FromUserName . " -- " . $xml->MsgType . " -- " . $xml->Content . '<input type = "submit" value = "Push" name = "submit" >';
//    echo "<br />";
//    echo '</form>';
//
//
//}

//$dbHandle->close();

