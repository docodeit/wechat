<?php
/**
 * Date: 2018/4/3
 * Time: 16:47
 */

namespace JinWeChat\Tests\OfficialAccount\Cookies;

use JinWeChat\Tests\TestCase;
use JinWeChat\OfficialAccount\Cookies\Cookies;
use JinWeChat\Factory;



class CookiesTest extends TestCase
{
    public function testSet()
    {
        $cookie = '{"token":"1057896193","data":[],"cookieJar":[{"Name":"ua_id","Value":"aEh1IWT0AultljgOAAAAAC9ijdwqFL0qF2CMFEhhS0Q=","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":2147483647,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"uuid","Value":"fc170888bf8bda00dce24cb17ca8b91e","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"master_sid","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"master_user","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"master_ticket","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"sp_user","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"sp_sid","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"sp_slave_user","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"safecode","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628201,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"ticket","Value":"4ef210ed0a1a5c673eb5932d21e5c4aaf3ce3d5f","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"ticket_id","Value":"gh_7d3d9e33515c","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"cert","Value":"LrT8c7orOpQssSBSjXmap2vME5ZH2rPx","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"data_bizuin","Value":"3531325527","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"bizuin","Value":"3596244841","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"data_ticket","Value":"uEJDJqOGaD6JeQJ9zUm7kpUOB8AhZuhj9gd71FfL6yg9Tb7RXE12nRMH2x/XyuoZ","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"slave_sid","Value":"RWVRZ19Ydk9nbGtBSmc5TTU5cnZ5SFE2RUk5U01CNmFLTzlpX2N2TWxjeDhLYjBadDlIaFk0bGYyMlZJSXNoSU1HRlFYUDVWaUxSenZGZDcxS0RlbGdWWms2eUdjaUVOX3lkODRmaFlBVWFVbFRqRHY5anNUYlpaOG82V2lhM0VYRjE0OGQ3Tjh6ZldvUkJa","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"slave_user","Value":"gh_7d3d9e33515c","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":null,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"xid","Value":"5531c34ac542880c82b24bd7b84ba7f7","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":2147483647,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"openid2ticket_oV8B70tOLPKiCdlwhpgJ3CPR72J8","Value":"Ftm1w2K4Ov22Gjfv1AIj0Nvkd8xZ+4C0/6UzQxyKj6I=","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1525306627,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"ticket_uin","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628227,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"ticket_certificate","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628227,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"login_certificate","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628227,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"fake_id","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628227,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"login_sid_ticket","Value":"EXPIRED","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":1522628227,"Secure":true,"Discard":false,"HttpOnly":true},{"Name":"mm_lang","Value":"zh_CN","Domain":"mp.weixin.qq.com","Path":"/","Max-Age":null,"Expires":4294967295,"Secure":true,"Discard":false,"HttpOnly":false}],"baseRefer":"https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=1057896193","cacheFloder":"/web/wechatbi/public/","wxh_name":"zql3138166@163.com"}';
        $cookie = json_decode($cookie, true);
        $config = [
            'username' => 'gh_0dda50a7fedd',
            'password' => 'zhangwan123',
            'response_type' => 'object',
            'cookies' => $cookie
        ];
        $app = Factory::officialAccount($config);
        $cookie = new Cookies($app);
        $cookie->set();
    }
}