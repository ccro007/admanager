<?php
/**
 * Adinfo form input filter
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
 * @subpackage      Form
 * @version         $Id$
 */

namespace Module\Admanager\Form;

use Pi;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class AdinfoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'          => 'adformat',
            'required'      => true,
            'filters'       => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'    => array(
                new StringLength(array(
                    'min'          => '3',
                    'max'          => '50',
                )),
            ),
        ));

        $this->add(array(
            'name'          => 'url',
            'filters'       => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'    => array(
                array(
                    'name'         => 'Regex',
                    'options'      => array(
                        'pattern'       => '/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'content',
            'required'      => true,
            'filters'       => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'    => array(
                new StringLength(array(
                    'min'          => '3',
                    'max'          => '50',
                )),
            ),
        ));

    }
}

