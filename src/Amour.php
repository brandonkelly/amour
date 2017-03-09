<?php
/**
 * Amour plugin for Craft CMS 3.x
 *
 * could be everything
 *
 * @link      www.kreisvier.ch
 * @copyright Copyright (c) 2017 Pascal Ujak
 */

namespace k4\amour;

//use k4\amour\variables\AmourVariable;
use k4\amour\twigextensions\AmourTwigExtension;
//use k4\amour\models\Settings;
//use k4\amour\utilities\AmourUtility as AmourUtilityUtility;
//use k4\amour\widgets\AmourWidget as AmourWidgetWidget;

use Craft;
use craft\base\Plugin;
//use craft\services\Plugins;
//use craft\events\PluginEvent;
//use craft\console\Application as ConsoleApplication;
//use craft\web\UrlManager;
//use craft\services\Elements;
//use craft\services\Fields;
//use craft\services\Utilities;
//use craft\services\Dashboard;
//use craft\events\RegisterComponentTypesEvent;
//use craft\events\RegisterUrlRulesEvent;
//use craft\events\ModelEvent;
//use craft\fields\Matrix;
use yii\base\Event;
use craft\web\View;

//use craft\db\Query;
//use craft\controllers\UsersController;
//use craft\events\RegisterUserActionsEvent;


use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
//use Yii;
//use yii\db\ActiveRecord;



/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Pascal Ujak
 * @package   Amour
 * @since     1.0.0
 */
class Amour extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Amour::$plugin
     *
     * @var static
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Amour::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */


    public function init()
    {
        parent::init();
        self::$plugin = $this;


        // Add in our Twig extensions
        Craft::$app->view->twig->addExtension(new AmourTwigExtension());

        // Add in our console commands
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'k4\amour\console\controllers';
        }


        Event::on(View::class, View::EVENT_END_BODY, function(Event $event) {

            $bodyAdmin = '';
            $jquery = '<script src="/cpresources/d8f763c7/jquery.js"></script>';
            $additionalCSS = '<style>body.notAdmin div[id^="fields-admin"]{display:none}</style>';

            $additionalJS = <<<END
            <script>$(document).ready(
                function(){
                    $("body.notAdmin div[id^='fields-admin'] .last .btngroup").remove();
                    $("body.notAdmin div[id^='fields-admin']").hide();

                    var entryTypeSelector = $("#entryType");
                    if (entryTypeSelector.length) {
                        if(entryTypeSelector.find(":selected").text().indexOf("admin")== 0){
                            //hide other values 
                            entryTypeSelector.find(":not(:selected)").hide();

                        }else{
                            //hide all admin values
                            entryTypeSelector.find(":contains('admin')").hide();
                        };
                    };

                });</script>


END;
            $user = Craft::$app->user->getIsGuest();

            // Check if User is Admin
            if ($user == false ) {

                if (Craft::$app->user->identity->admin) {
                    $bodyAdmin = '<script>$("body").addClass("isAdmin");</script>';
                }

                else {
                    $bodyAdmin = '<script>$("body").addClass("notAdmin");</script>';
                }

            }



            $userSession = Craft::$app->user->checkPermission('k4PowerAdminSettings');

            //if User has not k4poweradminSettings privileg
            if ($userSession == false) {
                $additionalCSS = $additionalCSS . '<style> body.notAdmin .re-html{display:none}</style>';
            }

            echo $jquery;
            echo $bodyAdmin;
            echo $additionalJS;
            echo $additionalCSS;

            //            Craft::$app->view->renderTemplate('amour/test.html', $additionalJS);

        }); 


        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions['k4 PowerAdmin'] = [
                'k4PowerAdminSettings' => ['label' => 'Show HTML in WYSIWYG Editor']
            ];
        });






        /**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info('Amour ' . Craft::t('amour', 'plugin loaded'), __METHOD__);
    }

    /**
     * Returns the component definition that should be registered on the
     * [[\craft\web\twig\variables\CraftVariable]] instance for this plugin’s handle.
     *
     * @return mixed|null The component definition to be registered.
     * It can be any of the formats supported by [[\yii\di\ServiceLocator::set()]].
     */
    public function defineTemplateComponent()
    {
        return AmourVariable::class;
    }

    // Protected Methods
    // =========================================================================


    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }




    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'amour'
            . DIRECTORY_SEPARATOR
            . 'settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
