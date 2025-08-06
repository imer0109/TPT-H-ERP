<?php

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

if (!function_exists('__200')) {

	/**
	 *
	 * @OA\Schema(
	 *   schema="200 HTTP Response",
	 *   title="Modèle d'une réponse HTTP de statut code 200",
	 *   type="object",
	 *   description="Cette ressource indique que la requête s'est bien passée."
	 *   @OA\Property(property="message", type="string", example="No special message for this response")
	 *   @OA\Property(property="code", type="integer", example="200"),
	 *   @OA\Property(property="status", type="string", example="ok"),
	 *   @OA\Property(property="data", type="array", example="['moreInfo1' => 'More information 1 value']")
	 * )
	 *
	 * Return a standard 200 response with optional data
	 *
	 * @param string $message
	 * @param array|null $data
	 * @param bool $spread
	 * @param string $dataKey
	 * @param bool $isEmpty
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __200(string $message = 'No special message for this response', array $data = null, bool $spread = true, string $dataKey = 'data', bool $isEmpty = false): Response|ResponseFactory
	{
		if ($isEmpty) {
			return response()->noContent(ResponseAlias::HTTP_OK);
		}
		$content = [
			'status' => 'ok',
			'code' => ResponseAlias::HTTP_OK,
			'message' => $message
		];

		if ($data)
			$content = $spread ? [...$content, ...$data] : [...$content, $dataKey => $data];

		return response($content, ResponseAlias::HTTP_OK);
	}
}

if (!function_exists('__404')) {

	/**
	 * Return a standard 404 error message response
	 *
	 * @param string $message
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __404(string $message = 'Oups, aucune resource n\'a été trouvée'): Response|ResponseFactory
	{
		return response([
			'message' => $message,
			'status' => 'failed',
			'code' => ResponseAlias::HTTP_NOT_FOUND
		], ResponseAlias::HTTP_NOT_FOUND);
	}
}

if (!function_exists('__401')) {

	/**
	 * Return a standard 404 error message response
	 *
	 * @param string $message
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __401(string $message = "Les identifiants saisis ne correspondent pas à nos enregistrements"): Response|ResponseFactory
	{
		return response([
			'message' => $message,
			'status' => 'failed',
			'code' => ResponseAlias::HTTP_UNAUTHORIZED
		], ResponseAlias::HTTP_UNAUTHORIZED);
	}
}

if (!function_exists('__403')) {

	/**
	 * Return a standard 404 error message response
	 *
	 * @param string $message
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __403(string $message = "Vous n'êtes pas autorisé à effectuer cette action"): Response|ResponseFactory
	{
		return response([
			'message' => $message,
			'status' => 'failed',
			'code' => ResponseAlias::HTTP_FORBIDDEN
		], ResponseAlias::HTTP_FORBIDDEN);
	}
}

if (!function_exists('__422')) {

	/**
	 * Return a standard 422 error message response after a failed validation
	 *
	 * @param string|array $messages
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __422(string|array $messages): Response|ResponseFactory
	{
		return response([
			'message' => is_array($messages) ? $messages[0] : $messages,
			'status' => 'Validation failed',
			'code' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
		], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
	}
}

if (!function_exists('__500')) {

	/**
	 * @OA\Schema(
	 *   schema="500 HTTP Response",
	 *   title="Modèle d'une réponse HTTP de statut code 500",
	 *   type="object",
	 *   description="Cette response indique q'une erreur est inattendue est survenue lors du traiement."
	 *   @OA\Property(property="message", type="string", example="Oups, une erreur inattendue est survenue")
	 *   @OA\Property(property="code", type="integer", example="500"),
	 *   @OA\Property(property="status", type="string", example="failed"),
	 * )
	 *
	 * Return a standard 500 error message response
	 *
	 * @param array|string|null $message
	 * @return Response|ResponseFactory
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function __500(array|string $message = null): Response|ResponseFactory
	{
		$content = [
			'status' => 'failed',
			'code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
		];

		if (!$message) {
			$content['message'] = 'Oups, une erreur inattendue est survenue';
		} else {
			$content['message'] = is_array($message) ? Arr::first($message) : $message;
		}
		return response($content, ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
	}
}

if (!function_exists('getPublicImagePath')) {

	/**
	 * Returns a string corresponding to the url of an image on the public disk
	 *
	 * @param string $path
	 * @return string
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function getPublicImagePath(string $path): string
	{
		return Storage::disk('public')->url($path);
	}
}

if (!function_exists('getFileDownloadableUrl')) {

	/**
	 * Returns a string corresponding to the url of a file on the public disk
	 *
	 * @param string $path
	 * @return string
	 * @package IAI-SITE
	 * @author SOSSOU-GAH Ézéchiel
	 * @created 2024-07-10
	 */
	function getFileDownloadableUrl(string $path): string
	{
		return 'storage/' . $path;
	}
}
