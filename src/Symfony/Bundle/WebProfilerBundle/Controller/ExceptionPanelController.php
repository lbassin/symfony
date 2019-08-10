<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\WebProfilerBundle\Controller;

use Symfony\Component\ErrorRenderer\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Renders the exception panel.
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 *
 * @internal
 */
class ExceptionPanelController
{
    private $htmlErrorRenderer;
    private $profiler;

    public function __construct(HtmlErrorRenderer $htmlErrorRenderer, ?Profiler $profiler)
    {
        $this->htmlErrorRenderer = $htmlErrorRenderer;
        $this->profiler = $profiler;
    }

    /**
     * Renders the exception panel stacktrace for the given token.
     */
    public function body(string $token): Response
    {
        if (null === $this->profiler) {
            throw new NotFoundHttpException('The profiler must be enabled.');
        }

        $exception = $this->profiler->loadProfile($token)
            ->getCollector('exception')
            ->getException()
        ;

        return new Response($this->htmlErrorRenderer->getBody($exception), 200, ['Content-Type' => 'text/html']);
    }

    /**
     * Renders the exception panel stylesheet.
     */
    public function stylesheet(): Response
    {
        return new Response($this->htmlErrorRenderer->getStylesheet(), 200, ['Content-Type' => 'text/css']);
    }
}
