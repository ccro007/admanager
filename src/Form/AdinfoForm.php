<?php
/**
 * Adinfo form
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
use Pi\Form\Form as BaseForm;

class AdinfoForm extends BaseForm
{
    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProtalFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        $this->add(array(
            'name'          => 'adformat',
            'options'       => array(
                'label' => __('adformat'),
            ),
            'attributes'    => array(
                'type'  => 'text',
            )
        ));

        $this->add(array(
            'name'          => 'url',
            'options'       => array(
                'label' => __('Protal url'),
            ),
            'attributes'    => array(
                'type'  => 'text',
                'class' => 'input-xxlarge',
            )
        ));

        $this->add(array(
            'name'          => 'content',
            'options'       => array(
                'label' => __('content'),
            ),
            'attributes'    => array(
                'type'  => 'text',
            )
        ));

        $this->add(array(
            'name'          => 'id',
            'attributes'    => array(
                'type'  => 'hidden',
                'value' => 0,
            )
        ));

        $this->add(array(
            'name'          => 'submit',
            'type'          => 'submit',
            'attributes'    => array(
                'value' => __('Submit'),
            )
        ));
    }
}

