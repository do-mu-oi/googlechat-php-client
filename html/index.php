<?php
define("WEBHOOK_URL_FORMAT", "https://chat.googleapis.com/v1/spaces/%s/messages?key=%s&token=%s");

function post($url, $params) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json; charset=UTF-8"
        ],
        CURLOPT_POSTFIELDS => json_encode($params),
    ]);

    $res = curl_exec($curl);
    curl_close($curl);

    return json_decode($res);
}
?>
<html>
<head>
    <title>Google Chat PHP Client</title>
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Google Chat PHP Client</h1>
<?php
if (isset($_GET["room"]) && isset($_GET["key"]) && isset($_GET["token"])) {
    $url = sprintf(
        WEBHOOK_URL_FORMAT,
        urlencode($_GET["room"]),
        urlencode($_GET["key"]),
        urlencode($_GET["token"]),
    );

    $message = $_GET["message"];

    $res = post($url, [
        "text" => $message
    ]);

    if ($res->error) {
        $title = "[ERROR] Message sending error.";
        $res_message = $res->error->message;
    } else {
        $title = "[OK] Message transmission completed.";
        $res_message = $res->name;
    }
?>
    <h2><?= $title ?></h2>
    <dl>
        <dt>Webhook URL</dt>
        <dd><code><?= $url ?></code></dd>
        <dt>Message</dt>
        <dd><pre><?= $message ?></pre></dd>
        <dt>Response</dt>
        <dd><?= $res_message ?></dd>
    </dl>
<?php
} else {
?>
    <h2>Usage</h2>
    <p><code><?= $_SERVER['REQUEST_URI']; ?>?room=[ROOM_ID]&key=[ACCESS_KEY]&token=[ACCESS_TOKEN]&message=[MESSAGE_TEXT]</code></p>
<?php
}
?>
</body>
</html>