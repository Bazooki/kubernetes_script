<?php

class wechat
{

    const APP_ID = 'wxe6909b1d6d324bb7';
    const APP_SECRET = '463c770db8fc27d37c2dd1777ae02be6';

    public function __construct()
    {

    }

    public function getAccessToken(){

        if(!isset($_SESSION['AccessToken']) && !isset($_SESSION['expires'])){
            if($_SESSION['expires'] > time()){
                $url = 'https://api.wechat.com/cgi-bin/token?grant_type=client_credential&appid=' . self::APP_ID . '&secret=' . self::APP_SECRET;
                $response = file_get_contents($url);
                $response = json_decode($response, true);
                $_SESSION['AccessToken'] =  $response['access_token'];
                $_SESSION['expires'] = time() + ($response['expires_in'] - 1000);
            }
        }
        return $_SESSION['AccessToken'];

    }

    public function sendTextMessage($toUser, $fromUser, $message, $push = false)
    {

        if($push){
            $access_token = $this->getAccessToken();
            $post_data = array();

            $post_data['touser'] = (string)$toUser;
            $post_data['msgtype'] = 'text';
            $post_data['text'] = array('content'=> 'PUSH MESSAGE:'.$message);

            $url = 'https://api.wechat.com/cgi-bin/message/custom/send?access_token='.$access_token;
            $resp = $this->curl_download($url, json_encode($post_data));
//            $file = fopen('response.txt', 'w+');
//            fwrite($file, (string)$toUser);
//            fclose($file);



        }else {
            return '<xml>
                   <ToUserName><![CDATA[' . $toUser . ']]></ToUserName>
                   <FromUserName><![CDATA[' . $fromUser . ']]></FromUserName>
                   <CreateTime>' . time() . '</CreateTime>
                   <MsgType><![CDATA[text]]></MsgType>
                   <Content><![CDATA[' . $message . ']]></Content>
                   <FuncFlag>0</FuncFlag>
                </xml>';
        }

    }

    public function sendMediaMessage($toUser, $fromUser, $media_id, $type = 'image', $thumb_id = null)
    {
        if($type == 'voice'){
            $xml = '<MsgType><![CDATA[voice]]></MsgType>
                   <Voice>
                   <MediaId><![CDATA['.$media_id.']]></MediaId>
                   </Voice>';
        }
        else if ($type == 'video'){
            $xml = '<MsgType><![CDATA[video]]></MsgType>
                   <Video>
                   <MediaId><![CDATA['.$media_id.']]></MediaId>
                   <ThumbMediaId><![CDATA['.$thumb_id.']]></ThumbMediaId>
                   </Video>';
        }
        else if ($type == 'music'){
            if (is_array($media_id)) {
                $xml = '<MsgType><![CDATA[music]]></MsgType>
                   <Music>
                   <Title><![CDATA[' . $media_id['title'] . ']]></Title>
                   <Description><![CDATA[' . $media_id['description'] . ']]></Description>
                   <MusicUrl><![CDATA[' . $media_id['music_url'] . ']]></MusicUrl>
                   <HQMusicUrl><![CDATA[' . $media_id['hq_music_url'] . ']]></HQMusicUrl>
                   <ThumbMediaId><![CDATA[' . $thumb_id . ']]></ThumbMediaId>
                   </Music>';
            }else {
                return false;
            }

        }
        else{
            $xml = '<MsgType><![CDATA[image]]></MsgType>
                   <Image>
                   <MediaId><![CDATA['.$media_id.']]></MediaId>
                   </Image>';
        }
        return '<xml>
                   <ToUserName><![CDATA[' . $toUser . ']]></ToUserName>
                   <FromUserName><![CDATA[' . $fromUser . ']]></FromUserName>
                   <CreateTime>' . time() . '</CreateTime>
                   '.$xml.'
                </xml>';

    }

    public function sendRichTextMessage($toUserName, $fromUserName, $articles){

        $articles_xml = '';
        foreach ($articles as $article){

            $articles_xml .= '<item>
                       <Title><![CDATA['.$article['title'].']]></Title>
                       <Description><![CDATA['.$article['description'].']]></Description>
                       <PicUrl><![CDATA['.$article['picUrl'].']]></PicUrl>
                       <Url><![CDATA['.$article['url'].']]></Url>
                     </item>';

        }


        return  '<xml>
                     <ToUserName><![CDATA['.$toUserName.']]></ToUserName>
                     <FromUserName><![CDATA['.$fromUserName.']]></FromUserName>
                     <CreateTime>'.time().'</CreateTime>
                     <MsgType><![CDATA[news]]></MsgType>
                     <ArticleCount>'.count($articles).'</ArticleCount>
                     <Articles>
                     '.$articles_xml.'
                     </Articles>
                 </xml>';

    }

    function curl_download($Url, $data = array(), $json = false){

        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Now set some options (most are optional)

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $Url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        //curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
        curl_close($ch);

        if($json){
            $output = json_decode($output);
        }


        return $output;
    }

}