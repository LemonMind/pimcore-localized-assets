<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Services;

use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ForwardCompatibility\Result;
use Pimcore\Db;
use Pimcore\Model\Asset;

class AssetService
{
    public static function getByMetaName(string $path): Asset|null
    {
        $queryBuilder = Db::getConnection()->createQueryBuilder();
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $dirname = pathinfo($path, PATHINFO_DIRNAME);

        try {
            $possibleAssetIds = $queryBuilder->select('id')
                ->from('assets')
                ->where($queryBuilder->expr()->like('path', '"%' . $dirname . '%"'))
                ->execute();

            if ($possibleAssetIds instanceof Result) {
                $possibleAssetIds = $possibleAssetIds->fetchAllAssociative();
            }
        } catch (Exception|DriverException $e) {
            return null;
        }

        if (empty($possibleAssetIds) || !is_array($possibleAssetIds)) {
            return null;
        }

        $ids = [];

        foreach ($possibleAssetIds as $id) {
            if (array_key_exists('id', $id)) {
                $ids[] = $id['id'];
            }
        }

        $queryBuilder->resetQueryParts(['select', 'from', 'where']);

        try {
            $asset = $queryBuilder->select('cid')
                ->from('assets_metadata')
                ->where($queryBuilder->expr()->eq('name', '"localized_asset_name"'))
                ->andWhere($queryBuilder->expr()->eq('type', '"input"'))
                ->andWhere($queryBuilder->expr()->eq('data', '"' . $filename . '"'))
                ->andWhere($queryBuilder->expr()->in('cid', $ids))
                ->execute();

            if ($asset instanceof Result) {
                $asset = $asset->fetchAllAssociative();
            }
        } catch (Exception|DriverException $e) {
            return null;
        }

        if (
            is_array($asset) &&
            isset($asset[0]) &&
            array_key_exists('cid', $asset[0])
        ) {
            return Asset::getById($asset[0]['cid']);
        }

        return null;
    }
}
