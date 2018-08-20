<?php
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$rpc_url = "";
	$error = '{"jsonrpc": "2.0", "error": {"code": -32001, "message": "Server not responding :("}, "id": null}';

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		$params = json_encode([
			'jsonrpc' 	=> "2.0",
			'method' 	=> isset($_GET["method"]) ? $_GET["method"] : "",
			'params' 	=> isset($_GET["params"]) ? $_GET["params"] : [],
			'id' 		=> "explorer"
		]);

		$content = @file_get_contents($rpc_url, null, stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-Type: application/json' . "\r\n"
				. 'Content-Length: ' . strlen($params) . "\r\n",
				'content' => $params,
				'timeout' => 10, // 10 seconds
			),
		)));

		if($content === false) {
			$result = $error;
		} else {
			$result = $content;
		}

	}

	echo $result;
?>
