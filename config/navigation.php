<?php
/**
 * Admanager module navigation config
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Chengcheng Luo <chengcheng@eefocus.com>
 * @since           3.0
 * @package         Module\Admanager
 * @version         $Id$
 */

return array(
    //'translate' => 'navigation',
    'front'   =>     false,
    'admin'   => array(
        'supplier'     => array(
            'label'         => __('Supplier Manager'),
            'route'         => 'admin',
            'controller'    => 'supplier',
            'action'        => 'index',
        ),
        'protal'     => array(
            'label'         => __('Protal Manager'),
            'route'         => 'admin',
            'controller'    => 'protal',
            'action'        => 'index',
        ),
        'channel'     => array(
            'label'         => __('Channel Manager'),
            'route'         => 'admin',
            'controller'    => 'channel',
            'action'        => 'index',
        ),
        'adinfo'     => array(
            'label'         => __('Ad Manager'),
            'route'         => 'admin',
            'controller'    => 'adinfo',
            'action'        => 'index',
        ),
    ),
);
