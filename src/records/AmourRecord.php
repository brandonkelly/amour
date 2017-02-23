<?php
/**
 * Amour plugin for Craft CMS 3.x
 *
 * could be everything
 *
 * @link      www.kreisvier.ch
 * @copyright Copyright (c) 2017 Pascal Ujak
 */

namespace k4\amour\records;

use k4\amour\Amour;

use Craft;
use craft\db\ActiveRecord;

/**
 * AmourRecord Record
 *
 * Active record models (or “records”) are like models, except with a
 * database-facing layer built on top. On top of all the things that models
 * can do, records can:
 *
 * - Define database table schemas
 * - Represent rows in the database
 * - Find, alter, and delete rows
 *
 * Note: Records’ ability to modify the database means that they should never
 * be used to transport data throughout the system. Their instances should be
 * contained to services only, so that services remain the one and only place
 * where system state changes ever occur.
 *
 * When a plugin is installed, Craft will look for any records provided by the
 * plugin, and automatically create the database tables for them.
 *
 * https://craftcms.com/docs/plugins/records
 *
 * @author    Pascal Ujak
 * @package   Amour
 * @since     1.0.0
 */
class AmourRecord extends ActiveRecord
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the database table the model is associated with (sans
     * table prefix). By convention, tables created by plugins should be prefixed
     * with the plugin name and an underscore.
     *
     * @return string
     *
     */
    public function getTableName()
    {
        return '{{%amour_amourrecord}}';
    }
}
