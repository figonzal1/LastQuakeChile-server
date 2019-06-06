<?php

require_once("../../configs/bd_files/mysql_adapter.php");

$mysql_adapter = new MysqlAdapter();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $input = file_get_contents('php://input');

    $json = json_decode($input, TRUE);

    $releases_url = $json['repository']['releases_url'];

    $releases_url = str_replace('{/id}', '', $releases_url);

    $ch = curl_init($releases_url);
    curl_setopt($ch, CURLOPT_URL, $releases_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'LastQuakeChile Releases');
    $releases = curl_exec($ch);
    curl_close($ch);

    $releases = json_decode($releases, TRUE);

    $i = sizeof($releases) - 1;

    while ($i >= 0) {

        $id_github = $releases[$i]['id'];
        $tag_name = $releases[$i]['tag_name'];
        $body = $releases[$i]['body'];
        $body = trim(str_replace("## Novedades", "", $body));

        $exist = $mysql_adapter->checkIfExistRelease($id_github);

        if (!$exist) {
            $mysql_adapter->addRelease($id_github, $tag_name, $body);
        } else if($exist) {
            $mysql_adapter->updateRelease($id_github, $tag_name, $body);
        }
        $i--;
    }

    $mysql_adapter->close();
}
