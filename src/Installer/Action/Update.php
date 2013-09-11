<?php
/**
 * Admanager module update class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) http://www.eefocus.com
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Chengcheng Luo <chengcheng@eefocus.com>
 * @since           1.0
 * @package         Module\Admanager
 */

namespace Module\Admanager\Installer\Action;
use Pi;
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Zend\EventManager\Event;
use Pi\Application\Installer\SqlSchema;

class Update extends BasicUpdate
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', array($this, 'updateSchema'));
        parent::attachDefaultListeners();
        return $this;
    }

    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');
        if (version_compare($moduleVersion, '1.0.3-beta.1', '>=')) {
            return true;
        }

        /* Add table of supplier , used in version  1.0.1-beta.1 */
        /*
        $sql = <<<'EOD'
CREATE TABLE `{supplier}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `name`            varchar(64)                     NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT '',
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);
EOD;
        SqlSchema::setType($this->module);
        $sqlHandler = new SqlSchema;
        try {
            $sqlHandler->queryContent($sql);
        } catch (\Exception $exception) {
            $result = $e->getParam('result');
            $result['db'] = array(
                'status'    => false,
                'message'   => 'SQL schema query failed: ' . $exception->getMessage(),
            );
            $e->setParam('result', $result);
            return false;
        }
        */

        /* Add table of supplier , used in version  1.0.2-beta.1 */
        /*
        $sql = <<<'EOD'
CREATE TABLE `{channel}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `protal_id`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `name`            varchar(64)                     NOT NULL DEFAULT '',
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);
EOD;
        SqlSchema::setType($this->module);
        $sqlHandler = new SqlSchema;
        try {
            $sqlHandler->queryContent($sql);
        } catch (\Exception $exception) {
            $result = $e->getParam('result');
            $result['db'] = array(
                'status'    => false,
                'message'   => 'SQL schema query failed: ' . $exception->getMessage(),
            );
            $e->setParam('result', $result);
            return false;
        }
        */

        /* Add table of adinfo, used in version  1.0.3-beta.1 */
        $sql = <<<'EOD'
CREATE TABLE `{adinfo}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `protal_id`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `channel_id`      int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `adformat`        varchar(64)                     NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT '',
  `supplier_id`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `content`         text,
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);
EOD;
        SqlSchema::setType($this->module);
        $sqlHandler = new SqlSchema;
        try {
            $sqlHandler->queryContent($sql);
        } catch (\Exception $exception) {
            $result = $e->getParam('result');
            $result['db'] = array(
                'status'    => false,
                'message'   => 'SQL schema query failed: ' . $exception->getMessage(),
            );
            $e->setParam('result', $result);
            return false;
        }

    }
}