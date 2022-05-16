pimcore.registerNS("pimcore.plugin.PimcoreLocalizedAssetsBundle");

pimcore.plugin.PimcoreLocalizedAssetsBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.PimcoreLocalizedAssetsBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },
});

let PimcoreLocalizedAssetsBundlePlugin = new pimcore.plugin.PimcoreLocalizedAssetsBundle();
