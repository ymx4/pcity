<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weixin
{
    private $CI;

    private $_APPID = 'wx35de4aedd8758e5e';

    private $_APPSECRET = '7dd97262b416e07ae22dc31735dd8497'; 

    const WX_OAUTH2_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";

    const WX_USERINFO_URL = "https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN";

    const WX_OAUTH2_URL = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect";

    const WX_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";

    const WX_JSAPI_TICKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi";

    const WX_GET_MEDIA = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s";

    const WX_CREATE_MENU = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";

    public function __construct(array $config = array()){
        $this->CI =& get_instance();
        $this->CI->load->helper('weixin');
        $this->CI->load->model('weixin_model');

        if (isset($config['appid'])){
            $this->_APPID = $config['appid'];
        }
        if (isset($config['appsecret'])){
            $this->_APPSECRET = $config['appsecret'];
        }
    }

    public function save_media($media_id,$state = ''){
        $token = $this->get_access_token();
        if ($token !== false){
            $media_url = sprintf(Weixin::WX_GET_MEDIA,$token,$media_id);
            $data = wx_curl_http_get_media($media_url,20);

            $info = $data['header'];

            if (isset($info['Content-disposition'])){
                if (preg_match("/filename=\"(.*?)\"/",$info['Content-disposition'],$match)){
                    $file_name = $match[1];
                }else{
                    log_message('error','fetch_media:'.print_r($info,true));
                    return false;
                }
            }else{
                log_message('error','fetch_media:'.print_r($info,true));
                return false;
            }

            $dir = 'uploads/weixin';

            if (empty($state)){
                $state = 'common';
            }
            $dir = $dir.'/'.$state;
            $tmpfile = md5($file_name);
            $dir.= '/'.$tmpfile{0}.$tmpfile{1}.$tmpfile{2};
            if (!is_dir(FCPATH . $dir)){
                mkdir($dir,0777,TRUE);
            }
            file_put_contents(FCPATH . $dir.'/'.$file_name, $data['body']);

            $cid = $this->CI->weixin_model->save_media($media_id,$dir.'/'.$file_name,$state);
        
            return array($cid,$dir.'/'.$file_name);
        }
    }


    public function get_jsapi_ticket($access_token){
        $rt = $this->CI->weixin_model->get_store_data($this->_APPID,'jsapi_ticket');
        if ($rt === -1 || $rt === -2){
            $access_token_url = sprintf(Weixin::WX_JSAPI_TICKET,$access_token);
            $response = wx_curl_https_post($access_token_url, array(), array(), 5);

            if ($response != NULL){
                $tmp_data = json_decode($response,true);
                if (isset($tmp_data['errcode']) && $tmp_data['errcode'] != 0){
                    log_message('error', 'WX:get_jsapi_ticket-'.$tmp_data['errcode'].':'.$tmp_data['errmsg']);   
                    return false;  
                }

                $this->CI->weixin_model->set_store_data($this->_APPID,'jsapi_ticket', $tmp_data['ticket'], $tmp_data['expires_in']);
                return $tmp_data['ticket'];
            }

        }else{
            return $rt;
        }
        return false;
    }

    public function get_access_token(){
        $rt = $this->CI->weixin_model->get_store_data($this->_APPID,'access_token');
        if ($rt === -1 || $rt === -2){
            $access_token_url = sprintf(Weixin::WX_ACCESS_TOKEN_URL,$this->_APPID,$this->_APPSECRET);
            $response = wx_curl_https_post($access_token_url, array(), array(), 5);

            if ($response != NULL){
                $tmp_data = json_decode($response,true);
                if (isset($tmp_data['errcode'])){
                    log_message('error', 'WX:get_access_token-'.$tmp_data['errcode'].':'.$tmp_data['errmsg']);   
                    return false;  
                }

                $this->CI->weixin_model->set_store_data($this->_APPID,'access_token', $tmp_data['access_token'], $tmp_data['expires_in']);
                return $tmp_data['access_token'];
            }

        }else{
            return $rt;
        }
        return false;
    }

    public function get_oauth2_url($redirect_uri,$state,$userinfo = false){
         if ($userinfo === TRUE){
            $scope = "snsapi_userinfo";
         }else{
            $scope = "snsapi_base";
         }

         $oauth2_url = sprintf(Weixin::WX_OAUTH2_URL,$this->_APPID,urlencode($redirect_uri),$scope,$state);
         return $oauth2_url;
    }

    public function get_oauth2_access_token($code){
        $access_token_url = sprintf(Weixin::WX_OAUTH2_ACCESS_TOKEN_URL,$this->_APPID,$this->_APPSECRET,$code);

        $response = wx_curl_https_post($access_token_url, array(), array(), 5);

        if ($response != NULL){
            $tmp_data = json_decode($response,true);
            if (isset($tmp_data['errcode'])){
                log_message('error', 'WX:get_oauth2_access_token-'.$tmp_data['errcode'].':'.$tmp_data['errmsg']); 
                return false;  
            }

            if (isset($tmp_data['access_token']) && isset($tmp_data['openid'])){
                return array('access_token'=>$tmp_data['access_token'],'openid'=>$tmp_data['openid']);
            }
        }
        return false;
    }

    public function create_menu($menu)
    {
        $token = $this->get_access_token();
        if ($token !== false){
            $url = sprintf(self::WX_CREATE_MENU, $token);

            $response = wx_curl_https_post($url, $menu, array(), 5);

            if ($response != NULL){
                $tmp_data = json_decode($response, true);
                if (isset($tmp_data['errcode'])){
                    if ($tmp_data['errcode']) {
                        log_message('error', 'WX:create_menu-'.$tmp_data['errcode'].':'.$tmp_data['errmsg']);
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    log_message('error', 'WX:create_menu-no-return');
                }
            }
        }

        return false;
    }

    public function get_userinfo($access_token,$openid){
        $userinfo_url = sprintf(Weixin::WX_USERINFO_URL,$access_token,$openid);

        $response = wx_curl_https_post($userinfo_url, array(), array(), 5);

         if ($response != NULL){
            $tmp_data = json_decode($response,true);
            if (isset($tmp_data['errcode'])){
                log_message('error', 'WX:get_userinfo-'.$tmp_data['errcode'].':'.$tmp_data['errmsg']);
                return false;     
            }

            if (isset($tmp_data['openid'])){
                return $tmp_data;
            }
        }

        return false;
    }

    public function get_js_config($url,$apiList,$debug = false){

        /* API list
        onMenuShareTimeline
        onMenuShareAppMessage
        onMenuShareQQ
        onMenuShareWeibo
        onMenuShareQZone
        startRecord
        stopRecord
        onVoiceRecordEnd
        playVoice
        pauseVoice
        stopVoice
        onVoicePlayEnd
        uploadVoice
        downloadVoice
        chooseImage
        previewImage
        uploadImage
        downloadImage
        translateVoice
        getNetworkType
        openLocation
        getLocation
        hideOptionMenu
        showOptionMenu
        hideMenuItems
        showMenuItems
        hideAllNonBaseMenuItem
        showAllNonBaseMenuItem
        closeWindow
        scanQRCode
        chooseWXPay
        openProductSpecificView
        addCard
        chooseCard
        openCard
        */

        $config = array();
        $config['debug'] = $debug;
        $config['appId'] = $this->_APPID;
        $config['timestamp'] = time();
        $config['nonceStr'] = "pc".rand(10,9999)."ity".rand(10,9999);

        $token = $this->get_access_token();
        if ($token !== false){
            $ticket = $this->get_jsapi_ticket($token);
            $string = "jsapi_ticket={$ticket}&noncestr={$config['nonceStr']}&timestamp={$config['timestamp']}&url={$url}";
            $config['signature'] = sha1($string);
            $config['jsApiList'] = $apiList;
            return $config;
        }


        return false;
    }

}
