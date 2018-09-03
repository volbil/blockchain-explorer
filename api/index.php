<?php
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$timeout = 10; // seconds
	$rpc_url = "";
	$error = [
		"not-responding" => '{"jsonrpc": "2.0", "error": {"code": -32001, "message": "Server Not Responding"}, "id": null}',
		"invalid-request" => '{"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}'
	];

	if (in_array($_SERVER["REQUEST_METHOD"], ["GET", "POST"])) {
		$request = isset($_GET) ? $_GET : $_POST;

		$params = json_encode([
			"jsonrpc" 	=> "2.0",
			"method" 	=> isset($request["method"]) ? $request["method"] : "",
			"params" 	=> isset($request["params"]) ? $request["params"] : [],
			"id" 		=> "explorer"
		]);

		$content = @file_get_contents($rpc_url, null, stream_context_create(array(
			"http" => array(
				"method" => "POST",
				"header" => "Content-Type: application/json" . "\r\n"
				. "Content-Length: " . strlen($params) . "\r\n",
				"content" => $params,
				"timeout" => $timeout,
			),
		)));

		if($content === false) {
			$result = $error["not-responding"];
		} else {
			$result = $content;
		}

		echo $result;
	} else {
		echo $error["invalid-request"];
	}
?>
