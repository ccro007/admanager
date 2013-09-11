<?php
/**
 * Protal controller
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
use Module\Admanager\Form\ProtalForm;
use Module\Admanager\Form\ProtalFilter;
use Module\Admanager\Model\Protal;
use Module\Admanager\Service;
use Pi\Paginator\Paginator;

/**
 * Feature list:
 *  1. List of protal
 *  2. Add/Edit a protal
 *  3. Delete a protal
 */
class ProtalController extends ActionController
{
    protected $protalColumns = array(
        'id', 'name', 'url', 'time_create', 'time_update', 'user_update',
    );

    protected  $displayColumns = array(
                            array('column'=>'name', 'name'=> 'Protal name', 'width'=>'15'),
                            array('column'=>'url', 'name'=> 'Protal url', 'width'=>'45'),
                            array('column'=>'time_create', 'name'=> 'Added Date', 'width'=>'15'),
                            array('column'=>'time_update', 'name'=> 'Last Modified','width'=>'15'),
    );

    /**
     *List of protals
     */
    public function indexAction()
    {
        $module = $this->getModule();
        $page   = Service::getParam($this, 'page', 1);
        $limit  = Service::getParam($this, 'limit', 20);
        $offset = ($limit && $page) ? $limit * ($page - 1) : null;
        $orderby   = Service::getParam($this, 'orderby', 'name');
        $order = Service::getParam($this, 'order', 'DESC');
        $schName = Service::getParam($this, 'schName', '');
        $schUrl = Service::getParam($this, 'schUrl', '');

        $modelProtal = $this->getModel('protal');

        $where = array();
        if (!empty($schName)) {
            $where['name like ?'] = sprintf('%%%s%%',$schName);
        }
        if (!empty($schUrl)) {
            $where['url like ?'] = sprintf('%%%s%%',$schUrl);
        }

        $protals = $modelProtal->getSearchRows($where, $limit, $offset, $this->protalColumns, sprintf('%s %s', $orderby, $order));
        /* Total count */
        $totalCount = $modelProtal->getSearchRowsCount($where);
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
            'protals'      => $protals,
            'paginator'    => $paginator,
            'tableHeader'  => $tableHeader,
            'orderby'       => $orderby,
            'order'         => $order,
            'schName'       => $schName,
            'schUrl'        => $schUrl,
        );
        $this->view()->assign($assign);
        $this->view()->setTemplate('protal-list');
    }

    /**
     * Add a protal
     */
    public function addAction()
    {
        $module = $this->getModule();
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new ProtalForm('protal');
            $form->setInputFilter(new ProtalFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->protalColumns)) {
                        unset($values[$key]);
                    }
                }
                $values['time_create'] = $values['time_update'] = time();
                unset($values['id']);
                $row = $this->getModel('protal')->createRow($values);
                $row->save();
                if ($row->id) {
                    $message = __('Protal data saved successfully.');
                    //$this->view()->setTemplate(false);
                    $this->redirect()->toRoute('', array('action' => 'index'));
                    return;
                } else {
                    $message = __('Protal data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            $form = new ProtalForm('protal');
            $form->setAttribute('action', $this->url('', array('action' => 'add')));
            $form->setData(array(
                'module'    => $module,
                'section'   => 'front',
            ));
            $message = '';
        }


        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a protal'));
        $this->view()->assign('message', $message);
        $this->view()->setTemplate('protal-edit');
    }

    /**
     * Edit a protal
     */
    public function editAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $id = $data['id'];
            $row = $this->getModel('protal')->find($id);
            $form = new ProtalForm('protal');
            $form->setInputFilter(new ProtalFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->protalColumns)) {
                        unset($values[$key]);
                    }
                }
                $values['time_update'] = time();
                $row->assign($values);
                $row->save();
                //$message = __('Protal data saved successfully.');
                $this->redirect()->toRoute('', array('action' => 'index'));
                return;
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            $id = $this->params('id');
            $data = $this->getModel('protal')->getOneRow($id);
            $extdata = array(
                        'time_create' => $data['time_create'],
                        'time_update' => $data['time_update'],
                        );
            $form = new ProtalForm('protal');
            $form->setData($data);
            $form->setAttribute('action', $this->url('', array('action' => 'edit')));
            $message = '';
        }
        if (!empty($extdata)) {
            $this->view()->assign('extdata', $extdata);
        }
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Protal edit'));
        $this->view()->assign('message', $message);
        $this->view()->setTemplate('protal-edit');
    }

    /**
     * deleting a protal
     *
     */
    public function deleteAction()
    {
        $id = Service::getParam($this, 'id', '');
        $id = is_array($id)?$id:(array)$id;
        if (count($id)) {
            $this->getModel('protal')->delete(array('id'=>$id));
        }
        $this->redirect()->toRoute('', array('action' => 'index'));
        //return 1;
    }
}
