<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle;

use Exception;
use Pimcore\Tool;
use Pimcore\Model\Metadata\Predefined;
use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;

class Installer extends SettingsStoreAwareInstaller
{
    /**
     * @throws Exception
     */
    public function install(): void
    {
        $validLanguages = Tool::getValidLanguages();

        foreach ($validLanguages as $validLanguage) {
            $predefinedMeta = Predefined::getByName('localized_asset_name', $validLanguage);

            if (!is_null($predefinedMeta)) {
                continue;
            }

            $predefinedMeta = new Predefined();
            $predefinedMeta->setName('localized_asset_name');
            $predefinedMeta->setType('input');
            $predefinedMeta->setLanguage($validLanguage);
            $predefinedMeta->setGroup('Localized Assets Bundle');
            $predefinedMeta->save();
        }

        $this->markInstalled();
    }

    public function uninstall(): void
    {
        $this->markUninstalled();
    }
}
