<?php

namespace App\Classes;

use Illuminate\Support\Facades\Log;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi;

class Sms
{
	// protected KavenegarApi $api;

	// public function __construct(KavenegarApi $api)
	// {
	// 	$this->api = $api;
	// }

	// public function send(string $mobile, string $message): array
	// {
	// 	try {
	// 		$sender = config('kavenegar.sender');
	// 		$this->api->send($sender, $mobile, $message);

	// 		$result = [
	// 			'success' => true,
	// 			'message' => 'Successfully'
	// 		];
	// 	} catch (ApiException $e) {
	// 		$result = [
	// 			'success' => false,
	// 			'message' => 'Api Error:' . $e->errorMessage()
	// 		];
	// 		//If webservice output not 200.
	// 		Log::error('Send SMS Api Error: ' . $e->errorMessage());
	// 	} catch (HttpException $e) {

	// 		$result = [
	// 			'success' => false,
	// 			'message' => 'Connection Error: ' . $e->errorMessage()
	// 		];
	// 		//Problem with connection to webservice
	// 		Log::error('Send SMS Connection Error: ' . $e->errorMessage());
	// 	}

	// 	return $result;
	// }

	// public function lookup(string $mobile, string $token1, $token2 = '', $token3 = '', $template = 'verify'): array
	// {
	// 	try {
	// 		$this->api->VerifyLookup($mobile, $token1, $token2, $token3, $template);

	// 		$result = [
	// 			'success' => true,
	// 			'message' => 'Successfully'
	// 		];
	// 	} catch (ApiException $e) {
	// 		$result = [
	// 			'success' => false,
	// 			'message' => 'Api Error:' . $e->errorMessage()
	// 		];
	// 		//If webservice output not 200.	
	// 		Log::error('Send SMS Api Error: ' . $e->errorMessage());
	// 	} catch (HttpException $e) {

	// 		$result = [
	// 			'success' => false,
	// 			'message' => 'Connection Error: ' . $e->errorMessage()
	// 		];
	// 		//Problem with connection to webservice
	// 		Log::error('Send SMS Connection Error: ' . $e->errorMessage());
	// 	}

	// 	return $result;
	// }

	public static function execute_manually_to_kavenegar($data = null)
	{
		$url = "https://api.kavenegar.com/v1/" . env('KAVENEGAR_API_KEY') . "/verify/lookup.json/";

		$headers = [
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'charset: utf-8'
		];
		$fields_string = "";
		if (!is_null($data)) {
			$fields_string = http_build_query($data);
		}
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

		$response = curl_exec($handle);
		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		$curl_errno = curl_errno($handle);
		$curl_error = curl_error($handle);
		if ($curl_errno) {
			throw new HttpException($curl_error, $curl_errno);
		}
		$json_response = json_decode($response);
		if ($code != 200 && is_null($json_response)) {
			throw new HttpException("Request have errors", $code);
		} else {
			$json_return = $json_response->return;
			if ($json_return->status != 200) {
				Log::debug('', [
					debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4)
				]);
				throw new ApiException(
					$json_return->message . var_export($data, true),
					$json_return->status
				);
			}
			return [
				'status' => $code
			];
		}
	}

	public static function send_tracking_code($pattern, $trackingCode, $mobile)
	{
		$smsPatternManually = [
			"receptor" => $mobile,
			"token" => $trackingCode,
			"token2" => null,
			"token3" => null,
			"template" => $pattern,
			"type" => null,
			"token10" => null,
			"token20" => null,
		];

		return self::execute_manually_to_kavenegar($smsPatternManually);
	}


	public static function send_as_group($messages)
	{
		$receptor = [];
		$sender = [];
		$message = [];
		foreach ($messages as $message) {
		}
	}

	public static function execute_manually_to_kavenegar_as_group($data = null)
	{
		$coreSetting = app(CoreSettings::class);
		$url = "https://api.kavenegar.com/v1/" . $coreSetting->get('sms.api_key') . "/sms/sendarray.json";

		$headers = [
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'charset: utf-8'
		];
		$fields_string = "";
		if (!is_null($data)) {
			$fields_string = http_build_query($data);
		}
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

		$response = curl_exec($handle);
		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		$curl_errno = curl_errno($handle);
		$curl_error = curl_error($handle);
		if ($curl_errno) {
			throw new HttpException($curl_error, $curl_errno);
		}
		$json_response = json_decode($response);
		if ($code != 200 && is_null($json_response)) {
			throw new HttpException("Request have errors", $code);
		} else {
			$json_return = $json_response->return;
			if ($json_return->status != 200) {
				Log::debug('', [
					debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4)
				]);
				throw new ApiException(
					$json_return->message . var_export($data, true),
					$json_return->status
				);
			}
			return [
				'status' => $code
			];
		}
	}
}
