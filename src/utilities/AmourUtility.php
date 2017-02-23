<?php
/**
 * Amour plugin for Craft CMS 3.x
 *
 * could be everything
 *
 * @link      www.kreisvier.ch
 * @copyright Copyright (c) 2017 Pascal Ujak
 */

namespace k4\amour\utilities;

use k4\amour\Amour;
use k4\amour\assetbundles\amourutilityutility\AmourUtilityUtilityAsset;

use Craft;
use craft\base\Utility;

/**
 * Amour Utility
 *
 * Utility is the base class for classes representing Control Panel utilities.
 *
 * https://craftcms.com/docs/plugins/utilities
 *
 * @author    Pascal Ujak
 * @package   Amour
 * @since     1.0.0
 */
class AmourUtility extends Utility
{
    // Static
    // =========================================================================

    /**
     * Returns the display name of this utility.
     *
     * @return string The display name of this utility.
     */
    public static function displayName(): string
    {
        return Craft::t('amour', 'AmourUtility');
    }

    /**
     * Returns the utility’s unique identifier.
     *
     * The ID should be in `kebab-case`, as it will be visible in the URL (`admin/utilities/the-handle`).
     *
     * @return string
     */
    public static function id(): string
    {
        return 'amour-amour-utility';
    }

    /**
     * Returns the path to the utility's SVG icon.
     *
     * @return string|null The path to the utility SVG icon
     */
    public static function iconPath()
    {
        return Craft::getAlias("@k4/amour/assetbundles/amourutilityutility/dist/img/AmourUtility-icon.svg");
    }

    /**
     * Returns the number that should be shown in the utility’s nav item badge.
     *
     * If `0` is returned, no badge will be shown
     *
     * @return int
     */
    public static function badgeCount(): int
    {
        return 0;
    }

    /**
     * Returns the utility's content HTML.
     *
     * @return string
     */
    public static function contentHtml(): string
    {
        Craft::$app->getView()->registerAssetBundle(AmourUtilityUtilityAsset::class);

        $someVar = 'Have a nice day!';
        return Craft::$app->getView()->renderTemplate(
            'amour'
            . DIRECTORY_SEPARATOR
            . '_components'
            . DIRECTORY_SEPARATOR
            . 'utilities'
            . DIRECTORY_SEPARATOR
            . 'AmourUtility_content',
            [
                'someVar' => $someVar
            ]
        );
    }
}
