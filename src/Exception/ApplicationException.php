<?php

declare(strict_types=1);

namespace App\Exception;

interface ApplicationException extends \Throwable
{
    /**
     * The server could not understand the request due to invalid syntax.
     *
     * @link https://tools.ietf.org/html/rfc7231#section-6.5.1
     */
    public const int BAD_REQUEST = 400;

    /**
     * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated".
     * That is, the client must authenticate itself to get the requested response.
     *
     * @link https://tools.ietf.org/html/rfc7235#section-3.1
     */
    public const int UNAUTHORIZED = 401;

    /**
     * The client does not have access rights to the content; that is, it is unauthorized, so the server is refusing
     * to give the requested resource. Unlike 401, the client's identity is known to the server.
     *
     * @link https://tools.ietf.org/html/rfc7231#section-6.5.3
     */
    public const int FORBIDDEN = 403;

    /**
     * The server can not find requested resource. In the browser, this means the URL is not recognized.
     * In an API, this can also mean that the endpoint is valid but the resource itself does not exist.
     * Servers may also send this response instead of 403 to hide the existence of a resource from an unauthorized client.
     * This response code is probably the most famous one due to its frequent occurrence on the web.
     *
     * @link https://tools.ietf.org/html/rfc7231#section-6.5.4
     */
    public const int NOT_FOUND = 404;
}
