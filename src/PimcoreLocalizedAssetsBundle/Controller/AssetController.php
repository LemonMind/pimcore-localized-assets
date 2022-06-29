<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Controller;

use Exception;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     requirements={"extension"="jpg|png|jpeg|webp|svg|tif|tiff|bmp|gif|eps"},
 *     methods={"GET","OPTIONS"},
 * )
 */
class AssetController extends FrontendController
{
    /**
     * @Route("/{path}", requirements={"path"="^(?!/admin)(.+?)\.((?:css|js)(?:\.map)?|jpe?g|gif|png|svgz?|eps|exe|gz|zip|mp\d|ogg|ogv|webm|pdf|docx?|xlsx?|pptx?)$"})
     *
     * @throws NotFoundHttpException
     */
    public function singleAction(Request $request): Response
    {
        $path = $request->getPathInfo();

        try {
            $asset = Asset::getByPath($path);
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }

        if ($asset instanceof Asset) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($asset->getData());
            $response->setPublic();
            $response->setMaxAge(60 * 60 * 24 * 14);
            $response->headers->set('Content-Type', $asset->getMimeType());

            return $response;
        }

        throw new NotFoundHttpException();
    }
}
