<?php

namespace CodeDistortion\Adapt\Exceptions;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Exceptions generated when building a database remotely.
 */
class AdaptRemoteBuildException extends AdaptException
{
    /** @var string|null The message to show in the log - so the regular exception message can be different. */
    private ?string $messageForLog = null;

    /** @var string|null The url used to build a database remotely. */
    private ?string $remoteBuildUrl = null;

    /** @var integer|null The response http status code. */
    private ?int $responseStatusCode = null;

    /** @var string|null The error message generated by the remote server. */
    private ?string $renderedResponseMessage = null;



    /**
     * The request to build a database remotely failed.
     *
     * @param string $driver The driver that isn't allowed to be built remotely.
     * @return self
     */
    public static function databaseTypeCannotBeBuiltRemotely(string $driver): self
    {
        return new self("$driver databases cannot be built remotely");
    }

    /**
     * The request to build a database remotely failed.
     *
     * @param string $remoteBuildUrl The "remote-build" url.
     * @return self
     */
    public static function remoteBuildUrlInvalid(string $remoteBuildUrl): self
    {
        return new self("The remote build url \"$remoteBuildUrl\" is invalid");
    }

    /**
     * The request to build a database remotely failed.
     *
     * @param string                 $connection        The connection the database was being built for.
     * @param string|null            $remoteBuildUrl    The url used to build a database remotely.
     * @param ResponseInterface|null $response          The response to the build http request.
     * @param Throwable              $originalException The originally thrown exception.
     * @param boolean                $someLoggingIsOn   Whether some logging (stdout or laravel) is on or not.
     * @return static
     */
    public static function remoteBuildFailed(
        string $connection,
        string $remoteBuildUrl,
        ?ResponseInterface $response,
        Throwable $originalException,
        bool $someLoggingIsOn
    ): self {

        $renderedResponseMessage = static::buildResponseMessage(
            $remoteBuildUrl,
            $originalException,
            $response
        );

        $loggingExtra = '';
//        $loggingExtra = $someLoggingIsOn
//            ? '(see log for more details) '
//            : '(turn on logging for more details) ';

        $message =
            "The remote database for connection \"$connection\" could not be built $loggingExtra"
            ."- $renderedResponseMessage";

        $exception = new self($message, 0, $originalException);
        $exception->remoteBuildUrl = $remoteBuildUrl;
        $exception->responseStatusCode = $response ? $response->getStatusCode() : null;
        $exception->renderedResponseMessage = $renderedResponseMessage;
        $exception->messageForLog = "The remote database for connection \"$connection\" could not be built";

        return $exception;
    }





    /**
     * Get the http response status.
     *
     * @param string                 $remoteBuildUrl    The "remote-build" url.
     * @param Throwable|null         $originalException The exception that was originally thrown.
     * @param ResponseInterface|null $response          The response object returned by the remote Adapt installation.
     * @return string|null
     */
    private static function buildResponseMessage(
        string $remoteBuildUrl,
        ?Throwable $originalException,
        ?ResponseInterface $response
    ): ?string {

        $responseMessage = static::interpretRemoteMessage($response);

        if ($originalException instanceof ConnectException) {
            return "Could not connect to $remoteBuildUrl";
        } elseif ($originalException instanceof BadResponseException) {
            return $responseMessage
                ? "Remote error message: \"{$responseMessage}\""
                : null;
        } elseif (!is_null($responseMessage)) {
            return "Remote error message: \"{$responseMessage}\"";
        }
        return "Unknown error";
    }

    /**
     * Get the http response status.
     *
     * @param ResponseInterface|null $response The response object returned by the remote Adapt installation.
     * @return string|null
     */
    private static function interpretRemoteMessage(?ResponseInterface $response): ?string
    {
        if (!$response) {
            return null;
        }

        // don't bother with a message if it's a 404 - it's pretty self-explanatory
        if ($response->getStatusCode() == 404) {
            return null;
        }

        $responseMessage = $response->getBody()->getContents();
        return mb_strlen($responseMessage) > 200
            ? mb_substr($responseMessage, 0, 200) . '…'
            : $responseMessage;
    }



    /**
     * Generate the exception title to log.
     *
     * @return string
     */
    public function generateTitleForLog(): string
    {
        return 'The Remote Build Failed';
    }

    /**
     * Build the lines to log.
     *
     * @return string[]
     */
    public function generateLinesForLog(): array
    {
        // don't include the url if the connection couldn't be made
        // as the url is included in the message
        $e = $this->getPrevious();
        $url = (!$e instanceof ConnectException)
            ? $this->remoteBuildUrl . ($this->responseStatusCode ? " ($this->responseStatusCode)" : '')
            : null;

        if (!$this->messageForLog) {
            return array_filter([$this->getMessage()]);
        }

        return array_filter([
            $this->messageForLog,
            $url,
            $this->renderedResponseMessage,
        ]);
    }
}
