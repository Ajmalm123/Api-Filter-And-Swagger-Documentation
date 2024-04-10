<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Service 'Short blog API'", version="0.1")
 * @OAS\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer"
 * )
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
    //
}
