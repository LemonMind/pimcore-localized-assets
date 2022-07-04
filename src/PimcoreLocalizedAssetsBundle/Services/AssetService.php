<?php

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Services;

use Pimcore\Db;
use Pimcore\Model\Asset;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Driver\Exception as DriverException;

class AssetService
{
    public static function getByMetaName(string $path): Asset|null
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
        } catch (Exception|DriverException $e) {
            return null;
        }

        if (!empty($asset) && array_key_exists('cid', $asset[0])) {
            return Asset::getById($asset[0]['cid']);
        }

        return null;
    }
}
