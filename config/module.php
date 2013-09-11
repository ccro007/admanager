<?php
/**
 * Demo module config
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
 * @author          Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 * @since           3.0
 * @package         Module\Demo
 * @version         $Id$
 */

/**
 * Application manifest
 */
return array(
    // Module meta
    'meta'  => array(
        // Module title, required
        'title'         => 'EEfocus Ad Manager',
        // Description, for admin, optional
        'description'   => 'Ad system for EEfocus.',
        // Version number, required
        'version'       => '1.0.3-beta.1',
        // Distribution license, required
        'license'       => 'New BSD',
        // Logo image, for admin, optional
        'logo'          => 'image/logo.png',
        // Readme file, for admin, optional
        'readme'        => 'docs/readme.txt',
        // Direct download link, available for wget, optional
        //'download'      => 'http://dl.xoopsengine.org/module/demo',
        // Demo site link, optional
        'demo'          => 'http://demo.xoopsengine.org/demo',

        // Module is ready for clone? Default as false
        'clonable'      => true,
    ),
    // Author information
    'author'    => array(
        // Author full name, required
        'name'      => 'ccro',
        // Email address, optional
        'email'     => 'chengcheng@eefocus.com',
        // Website link, optional
        'website'   => 'http://www.xoopsengine.org',
        // Credits and aknowledgement, optional
        'credits'   => 'Zend Framework Team; Pi Engine Team; EEFOCUS Team.'
    ),
    // Module dependency: list of module directory names, optional
    'dependency'    => array(
    ),
    // Maintenance actions
    'maintenance'   => array(
        // Class for module maintenace
        // Methods for action event:
        //  preInstall, install, postInstall;
        //  preUninstall, uninstall, postUninstall;
        //  preUpdate, update, postUpdate
        //'class' => 'Module\\Demo\\Maintenance',

        // resource
        'resource' => array(
            // Database meta
            'database'  => array(
                // SQL schema/data file
                'sqlfile'   => 'sql/mysql.sql',
                // Tables to be removed during uninstall, optional - the table list will be generated automatically upon installation
                'schema'    => array(
                    'protal'            => 'table',
                    'supplier'          => 'table',
                    'channel'           => 'table',
                    'adinfo'         => 'table',
                )
            ),
            // ACL specs
            //'acl'       => 'acl.php',
            // Bootstrap, priority
            'bootstrap' => 1,
            // Navigation definition
            'navigation'    => 'navigation.php',
        )
    )
);
