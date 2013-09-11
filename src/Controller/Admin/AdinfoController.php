<?php
/**
 * Adinfo controller
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
 * @subpackage      Controller
 * @version         $Id$
 */

namespace Module\Admanager\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Admanager\Form\AdinfoForm;
use Module\Admanager\Form\AdinfoFilter;
use Module\Admanager\Model\Adinfo;
use Module\Admanager\Service;
use Pi\Paginator\Paginator;

/**
 * Feature list:
 *  1. List of channel
 *  2. Add/Edit a channel
 *  3. Delete a channel
 */
class AdinfoController extends ActionController
{
    protected $channelColumns = array(
        'id', 'name', 'protal_id', 'time_create', 'time_update', 'user_update',
    );

    protected  $displayColumns = array(
                            array('column'=>'protal_id', 'name'=> 'Protal', 'width'=>'15'),
                            array('column'=>'name', 'name'=> 'Adinfo name', 'width'=>'45'),
                            array('column'=>'time_create', 'name'=> 'Added Date', 'width'=>'15'),
                            array('column'=>'time_update', 'name'=> 'Last Modified','width'=>'15'),
    );

    /**
     *List of channels
     */
    public function indexAction()
    {
        $module = $this->getModule();
        $page   = Service::getParam($this, 'page', 1);
        $limit  = Service::getParam($this, 'limit', 20);
        $offset = ($limit && $page) ? $limit * ($page - 1) : null;
        $orderby   = Service::getParam($this, 'orderby', 'protal_id');
        $order = Service::getParam($this, 'order', 'DESC');
        $schName = Service::getParam($this, 'schName', '');
        $schUrl = Service::getParam($this, 'schUrl', '');

        /* get all channels */
        $where = array();
        if (!empty($schName)) {
            $where['name like ?'] = sprintf('%%%s%%',$schName);
        }
        $modelAdinfo = $this->getModel('channel');
        $channels = $modelAdinfo->getSearchRows($where, $limit, $offset, $this->channelColumns, sprintf('%s %s', $orderby, $order));

        /* get protals info */
        $protal_ids = array();
        foreach($channels as $channel) {
            if (!in_array($channel['protal_id'], $protal_ids)) {
                array_push(&$protal_ids, $channel['protal_id']);
            }
        }
        $modelProtal = $this->getModel('protal');
        $protals = $modelProtal->getRows($protal_ids, array('id','name'));

        /* Total count */
        $totalCount = $modelAdinfo->getSearchRowsCount($where);
        /* gen paginator */
        $paginator = Paginator::factory($totalCount);
        $urlOptions = array(
            'pageParam' => 'page',
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $module,
                'controller'   => $this->getEvent()->getRouteMatch()->getParam('controller'),
                'action'        => $this->getEvent()->getRouteMatch()->getParam('action'),
                'orderby'       => $orderby,
                'order'         => $order,
            )),
        );
        if (!empty($schName)) {
            $urlOptions['params']['schName'] = $schName;
        }
        if (!empty($schUrl)) {
            $urlOptions['params']['schUrl'] = $schUrl;
        }
        $paginator->setItemCountPerPage($limit)
            ->setCurrentPageNumber($page)
            ->setUrlOptions($urlOptions);
        /* end gen paginator */
        /* table header */
        $tableHeader = '';
        foreach ($this->displayColumns as $col) {
            extract($col);
            $urlParams = array(
                'action'    => 'index',
                'orderby'   => $column,
                'order'     => ($column == $orderby) ? (('desc' == strtolower($order)) ? 'ASC' : 'DESC') : 'DESC',
            );
            if (!empty($schName)) {
                $urlParams['schName'] = $schName;
            }
            if (!empty($schUrl)) {
                $urlParams['schUrl'] = $schUrl;
            }
            $tableHeader .= sprintf("<th width=\"%d%%\"><a href=\"%s\">%s</a></th>" . PHP_EOL,
                                        $width,
                                        $this->url('', $urlParams),
                                        __($name) . (($column == $orderby) ? (('desc' == strtolower($order)) ? '▼' : '▲') : '')
                                    );
        }
        /* end table header */
        $assign = array(
            'channels'      => $channels,
            'protals'       => $protals,
            'paginator'    => $paginator,
            'tableHeader'  => $tableHeader,
            'orderby'       => $orderby,
            'order'         => $order,
            'schName'       => $schName,
            'schUrl'        => $schUrl,
        );
        $this->view()->assign($assign);
        $this->view()->setTemplate('channel-list');
    }

    /**
     * Add a channel
     */
    public function addAction()
    {
        $module = $this->getModule();
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new AdinfoForm('channel');
            $form->setInputFilter(new AdinfoFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->channelColumns)) {
                        unset($values[$key]);
                    }
                }
                $values['time_create'] = $values['time_update'] = time();
                unset($values['id']);
                $row = $this->getModel('channel')->createRow($values);
                $row->save();
                if ($row->id) {
                    $message = __('Adinfo data saved successfully.');
                    //$this->view()->setTemplate(false);
                    $this->redirect()->toRoute('', array('action' => 'index'));
                    return;
                } else {
                    $message = __('Adinfo data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            $form = new AdinfoForm('channel');
            $form->setAttribute('action', $this->url('', array('action' => 'add')));
            $form->setData(array(
                'module'    => $module,
                'section'   => 'front',
            ));
            $message = '';
        }


        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a channel'));
        $this->view()->assign('message', $message);
        $this->view()->setTemplate('channel-edit');
    }

    /**
     * Edit a channel
     */
    public function editAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $id = $data['id'];
            $row = $this->getModel('channel')->find($id);
            $form = new AdinfoForm('channel');
            $form->setInputFilter(new AdinfoFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->channelColumns)) {
                        unset($values[$key]);
                    }
                }
                $values['time_update'] = time();
                $row->assign($values);
                $row->save();
                //$message = __('Adinfo data saved successfully.');
                $this->redirect()->toRoute('', array('action' => 'index'));
                return;
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            $id = $this->params('id');
            $row = $this->getModel('channel')->find($id);
            $data = $row->toArray();
            $dateFormat = Pi::config('date_format', 'intl');
            $extdata = array(
                        'time_create'=>date($dateFormat, $data['time_create']),
                        'time_update'=>date($dateFormat, $data['time_update'])
                        );
            $form = new AdinfoForm('channel');
            $form->setData($data);
            $form->setAttribute('action', $this->url('', array('action' => 'edit')));
            $message = '';
        }
        if (!empty($extdata)) {
            $this->view()->assign('extdata', $extdata);
        }
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Adinfo edit'));
        $this->view()->assign('message', $message);
        $this->view()->setTemplate('channel-edit');
    }

    /**
     * deleting a channel
     *
     */
    public function deleteAction()
    {
        $id = Service::getParam($this, 'id', '');
        $id = is_array($id)?$id:(array)$id;
        if (count($id)) {
            $this->getModel('channel')->delete(array('id'=>$id));
        }
        $this->redirect()->toRoute('', array('action' => 'index'));
        //return 1;
    }
}
