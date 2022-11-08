<?php
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
} 

function http_response($url, $status = null, $postFields, $wait = 10)
{
    $time = microtime(true);
    $expire = $time + $wait;

    $data = json_encode($postFields);
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
    "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $head = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if (!$head) {
        return false;
    }

    if ($status === null) {
        if ($httpCode < 400) {
            return true;
        } else {
            return false;
        }
    } elseif ($status == $httpCode) {
        return true;
    }

    return false;
}

function getInfo($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

    $headers = ["Accept: application/json"];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $resp = curl_exec($curl);
    curl_close($curl);
    return json_decode($resp);
}

function checkAccessToken(
    $sessionId,
    $accessToken,
    $initialUrl = "https://api.afilaanda.co"
) {
    $info = getInfo($initialUrl . "/queue/" . $sessionId);

    $ip = (isset($_SERVER["HTTP_CLIENT_IP"])
            ? $_SERVER["HTTP_CLIENT_IP"]
            : isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        ? $_SERVER["HTTP_X_FORWARDED_FOR"]
        : $_SERVER["REMOTE_ADDR"];

    if ($info->status != "inactive") {
        $url = $initialUrl . "/queue/" . $sessionId . "/check/internal";
        $headers = getallheaders();
        $data = [
            "headers" => $headers,
            "ip" => $ip,
            "accessToken" => $accessToken,
        ];

        $is200 = http_response($url, 200, $data);

        $location = "https://queue.afilaanda.co/" . $sessionId;

        if (!$is200) {
            header("Location: " . $location, true, 307);
            echo "<meta http-equiv=\"refresh\" content=\"0; URL='$location'\"/>";
            echo "<a href=\"$location\">Não foi redirecionado? Clique aqui</a>";
            exit();
        }
    }
}

?>
