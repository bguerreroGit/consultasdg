<?php

namespace Facade\FlareClient\Context;

use Throwable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RequestContext implements ContextInterface
{
    /** @var \Symfony\Component\HttpFoundation\Request|null */
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? Request::createFromGlobals();
    }

    public function getRequest(): array
    {
        return [
            'url' => $this->request->getUri(),
            'ip' => $this->request->getClientIp(),
            'method' => $this->request->getMethod(),
            'useragent' => $this->request->headers->get('User-Agent'),
        ];
    }

    private function getFiles(): array
    {
        if (is_null($this->request->files)) {
            return [];
        }

        return $this->mapFiles($this->request->files->all());
    }

    protected function mapFiles(array $files)
    {
        return array_map(function ($file) {
            if (is_array($file)) {
                return $this->mapFiles($file);
            }

            if (! $file instanceof UploadedFile) {
                return;
            }

            return [
                'pathname' => $file->getPathname(),
                'size' => $file->getSize(),
                'mimeType' => $file->getMimeType(),
            ];
        }, $files);
    }

    public function getSession(): array
    {
        $session = $this->request->getSession();

        return $session ? $this->getValidSessionData($session) : [];
    }

    /**
     * @param SessionInterface $session
     * @return array
     */
    protected function getValidSessionData($session): array
    {
        try {
            json_encode($session->all());
        } catch (Throwable $e) {
            return [];
        }

        return $session->all();
    }

    public function getCookies(): array
    {
        return $this->request->cookies->all();
    }

    public function getHeaders(): array
    {
        return $this->request->headers->all();
    }

    public function getRequestData(): array
    {
        return [
            'queryString' => $this->request->query->all(),
            'body' => $this->request->request->all(),
            'files' => $this->getFiles(),
        ];
    }

    public function toArray(): array
    {
        return [
            'request' => $this->getRequest(),
            'request_data' => $this->getRequestData(),
            'headers' => $this->getHeaders(),
            'cookies' => $this->getCookies(),
            'session' => $this->getSession(),
        ];
    }
}
