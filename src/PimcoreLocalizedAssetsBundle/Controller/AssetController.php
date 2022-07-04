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
use Pimcore\Db;

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
     */
    public function singleAction(Request $request): Response
    {
        $path = $request->getPathInfo();

        $asset = $this->getByMetaName($path);

        if (null === $asset) {
            $asset = Asset::getByPath($path);
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

    private function getByMetaName(string $path): Asset|null
    {
        $pathInfo = pathinfo($path);
        $queryBuilder = Db::getConnection()->createQueryBuilder();

        try {
            $asset = $queryBuilder->select('cid')
                ->from('assets_metadata')
                ->where('name = "localized_asset_name"')
                ->andWhere('type = "input"')
                ->andWhere('data = "' . $pathInfo['filename'] . '"')
                ->execute()->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Driver\Exception|\Doctrine\DBAL\Exception $e) {
            return null;
        }

        if (!empty($asset) && array_key_exists('cid', $asset[0])) {
            return Asset::getById($asset[0]['cid']);
        }

        return null;
    }
}
