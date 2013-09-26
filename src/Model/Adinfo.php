<?php
/**
 * Admanager module channel class
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

namespace Module\Admanager\Model;

use Pi;
use Pi\Application\Model\Model;
use Zend\Db\Sql\Expression;

class Adinfo extends Model
{

    public static function getDefaultColumns()
    {
        return array(
        'id', 'protal_id', 'channel_id',
        'adformat','url','supplier_id','content',
        'ad_date','time_create','time_update',
        'user_update',
        );
    }

    /**
     * Get channels by ids
     *
     * @param $ids Channel ids
     * @param null $columns Columns, null for default
     * @return array
     */
    public function getRows($ids, $columns = null)
    {
        $result = $rows = array();

        if (null === $columns) {
            $columns = self::getDefaultColumns();
        }

        if ($ids) {
            $result = array_flip($ids);

            $rows = $this->select(array('id' => $ids));

            foreach ($rows as $row) {
                $result[$row['id']] = $row;
            }

            $result = array_filter($result, function($var) {
                return is_array($var);
            });
        }

        return $result;
    }

    public function getSearchRows($where = array(),  $limit = null, $offset = null, $columns = null, $order = null)
    {
        $result = $rows = array();

        if (null === $columns) {
            $columns = self::getDefaultColumns();
        }

        if (!in_array('id', $columns)) {
            $columns[] = 'id';
        }

        $order = (null === $order) ? 'time_update DESC' : $order;

        $select = $this->select()
            ->columns($columns);

        if ($where) {
            $select->where($where);
        }

        if ($limit) {
            $select->limit(intval($limit));
        }

        if ($offset) {
            $select->offset(intval($offset));
        }

        if ($order) {
            $select->order($order);
        }

        $rows = $this->selectWith($select)->toArray();

        $dateFormat = Pi::config('date_format', 'intl');
        foreach ($rows as $row) {
            if (!empty($row['time_create'])) {
                $row['time_create'] = date($dateFormat, $row['time_create']);
            }
            if (!empty($row['time_update'])) {
                $row['time_update'] = date($dateFormat, $row['time_update']);
            }
            $result[$row['id']] = $row;
        }

        return $result;
    }

    public function getSearchRowsCount($where = array())
    {
        $result = 0;

        $select = $this->select()
            ->columns(array('total' => new Expression('count(id)')));

        if ($where) {
            $select->where($where);
        }

        $resultset  = $this->selectWith($select);
        $result     = intval($resultset->current()->total);

        return $result;
    }

}
