<?php
$webhook_url = $_GET["url"];
$message = $_GET["message"];

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
if ($webhook_url && $message) {
    $res = post($webhook_url, [
        "text" => $message
    ]);

    if ($res->error) {
        $title = "[ERROR] Message sending error.";
        $class_name = "error";
    } else {
        $title = "[OK] Message transmission completed.";
        $class_name = "success";
    }
?>
    <h2 class="<?= $class_name ?>"><?= $title ?></h2>
    <dl>
        <dt>Webhook URL</dt>
        <dd><code><?= $webhook_url ?></code></dd>
        <dt>Message</dt>
        <dd><pre><?= $message ?></pre></dd>
        <dt>Response</dt>
        <dd><pre><?= var_dump($res) ?></pre></dd>
    </dl>
<?php
} else {
?>
    <form action="<?= $_SERVER['REQUEST_URI']; ?>">
        <dl>
            <dt>Webhook URL</dt>
            <dd><input name="url" placeholder="https://" value="<?= $webhook_url ?>"></dd>
            <dt>Message</dt>
            <dd><textarea name="message" rows="20"><?= $message ?></textarea></dd>
        </dl>
        <div><input type="submit" value="POST"></div>
    </form>
<?php
}
?>
</body>
</html>