<?php
	class CFAPI
	{
		public static function getResult($url, $level = 0)
		{
			$err = 0;
			$content = "";
			do
			{
				$options = array(
						CURLOPT_RETURNTRANSFER => true,     // return web page
						CURLOPT_HEADER         => false,    // don't return headers
						CURLOPT_FOLLOWLOCATION => true,     // follow redirects
						CURLOPT_AUTOREFERER    => true,     // set referer on redirect
						CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
						CURLOPT_TIMEOUT        => 120,      // timeout on response
						CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
						);

				$ch      = curl_init( $url );
				curl_setopt_array( $ch, $options );
				$content = curl_exec( $ch );
				$err     = curl_errno( $ch );
				$header  = curl_getinfo( $ch );
				curl_close( $ch );
			}while($err != 0);
			$res = json_decode($content, true);
			if (!array_key_exists("status", $res) || $res["status"] != "OK")
			{
				if ($level == 0)
				{
					sleep(3);
					return self::getResult($url, 1);
				}
				else
					die($content);
			}
			return json_decode($content, true)["result"];
		}

	};
?>
