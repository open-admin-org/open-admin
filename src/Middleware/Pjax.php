<?php

namespace OpenAdmin\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Facades\Admin;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class Pjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$request->pjax() || $response->isRedirection() || Admin::guard()->guest()) {
            return $response;
        }

        if (!$response->isSuccessful()) {
            return $this->handleErrorResponse($response);
        }

        try {
            $this->filterResponse($response, $request->header('X-PJAX-CONTAINER'))
                ->setUriHeader($response, $request);
        } catch (\Exception $exception) {
        }

        return $response;
    }

    /**
     * Send a response through this middleware.
     *
     * @param Response $response
     */
    public static function respond(Response $response)
    {
        $next = function () use ($response) {
            return $response;
        };

        (new static())->handle(Request::capture(), $next)->send();

        exit;
    }

    /**
     * Handle Response with exceptions.
     *
     * @param Response $response
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleErrorResponse(Response $response)
    {
        $exception = $response->exception;

        $error = new MessageBag([
            'type'    => get_class($exception),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ]);

        return back()->withInput()->withErrors($error, 'exception');
    }

    /**
     * Prepare the PJAX-specific response content.
     *
     * @param Response $response
     * @param string   $container
     *
     * @return $this
     */
    protected function filterResponse(Response $response, $container)
    {
        $input = $response->getContent();

        $title = $this->makeFromBetween($input, '<title>', '</title>');
        $title = !empty($title) ? '<title>'.$title.'</title>' : '';

        $content = $this->makeFromBetween($input, '<!--start-pjax-container-->', '<!--end-pjax-container-->');
        $content = $this->decodeUtf8HtmlEntities($content);

        /*
        if (empty($content)) {
            // try dom-crwawler
            // this is much slower though
            $crawler = new Crawler($input);
            $title = $this->makeTitle($crawler);
            $content = $this->fetchContents($crawler, $container);
        }
        */

        if (empty($content)) {
            abort(422);
        }

        $response->setContent(
            $title.$content
        );

        return $this;
    }

    /**
     * Prepare an HTML title tag.
     *
     * @param Crawler $crawler
     *
     * @return string
     */
    protected function makeTitle($crawler)
    {
        $pageTitle = $crawler->filter('head > title')->html();

        return "<title>{$pageTitle}</title>";
    }

    /**
     * Prepare an HTML title tag.
     *
     * @param string $input
     *
     * @return string
     */
    protected function makeFromBetween($input, $start, $end)
    {
        $str = '';
        if (Str::contains($input, $start)) {
            $str = Str::between($input, $start, $end);
        }

        return $str;
    }

    /**
     * Fetch the PJAX-specific HTML from the response.
     *
     * @param Crawler $crawler
     * @param string  $container
     *
     * @return string
     */
    protected function fetchContents($crawler, $container)
    {
        $content = $crawler->filter($container);

        if (!$content->count()) {
            abort(422);
        }

        return $this->decodeUtf8HtmlEntities($content->html());
    }

    /**
     * Decode utf-8 characters to html entities.
     *
     * @param string $html
     *
     * @return string
     */
    protected function decodeUtf8HtmlEntities($html)
    {
        return preg_replace_callback('/(&#[0-9]+;)/', function ($html) {
            return mb_convert_encoding($html[1], 'UTF-8', 'HTML-ENTITIES');
        }, $html);
    }

    /**
     * Set the PJAX-URL header to the current uri.
     *
     * @param Response $response
     * @param Request  $request
     */
    protected function setUriHeader(Response $response, Request $request)
    {
        $response->header(
            'X-PJAX-URL',
            $request->getRequestUri()
        );
    }
}
