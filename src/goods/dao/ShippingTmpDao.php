<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use Throwable;
use mon\log\Logger;
use mon\thinkOrm\Dao;
use mon\util\Instance;
use app\admin\dao\AdminLogDao;
use plugins\goods\validate\ShippingTmpValidate;

/**
 * 配送模板Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ShippingTmpDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods_shipping_tmp';

    /**
     * 自动写入时间戳
     *
     * @var boolean
     */
    protected $autoWriteTimestamp = true;

    /**
     * 验证器
     *
     * @var string
     */
    protected $validate = ShippingTmpValidate::class;

    /**
     * 新增
     *
     * @param array $data
     * @param integer $adminID
     * @return integer
     */
    public function add(array $data, int $adminID): int
    {
        $check = $this->validate()->data($data)->scope('add')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return 0;
        }
        $this->startTrans();
        try {
            $field = ['type', 'name', 'remark', 'first', 'first_price', 'continue', 'continue_price', 'sort', 'status'];
            $tmp_id = $this->allowField($field)->save($data, true, true);
            if (!$tmp_id) {
                $this->rollback();
                $this->error = '新增配送模板失败';
                return 0;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '新增配送模板',
                    'content' => '新增配送模板：' . $tmp_id,
                    'sid' => $tmp_id
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return 0;
                }
            }

            $this->commit();
            return $tmp_id;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '新增配送模板异常';
            Logger::instance()->channel()->error('Add shipping tmp exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return 0;
        }
    }

    /**
     * 更新
     *
     * @param array $data
     * @return boolean
     */
    public function edit(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('edit')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        $info = $this->where('id', $data['idx'])->get();
        if (!$info) {
            $this->error = '模板不存在';
            return false;
        }

        $this->startTrans();
        try {
            $field = ['type', 'name', 'remark', 'first', 'first_price', 'continue', 'continue_price', 'sort', 'status'];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '编辑配送模板失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '编辑配送模板',
                    'content' => '编辑配送模板：' . $info['id'],
                    'sid' => $info['id']
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return false;
                }
            }

            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '编辑配送模板异常';
            Logger::instance()->channel()->error('Edit shipping tmp exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }

    /**
     * 查询列表
     *
     * @param array $data 请求参数
     * @return array
     */
    public function getList(array $data)
    {
        $limit = isset($data['limit']) ? intval($data['limit']) : 10;
        $page = isset($data['page']) && is_numeric($data['page']) ? intval($data['page']) : 1;
        // 查询
        $list = $this->scope('list', $data)->page($page, $limit)->all();
        $total = $this->scope('list', $data)->count('id');

        return [
            'list'      => $list,
            'count'     => $total,
            'pageSize'  => $limit,
            'page'      => $page
        ];
    }

    /**
     * 查询列表场景
     *
     * @param \mon\thinkOrm\extend\Query $query
     * @param array $option
     * @return mixed
     */
    protected function scopeList($query, $option)
    {
        // 类型搜索
        if (isset($option['type']) && $this->validate()->int($option['type'])) {
            $query->where('type', intval($option['type']));
        }
        // 状态搜索
        if (isset($option['status']) && $this->validate()->int($option['status'])) {
            $query->where('status', intval($option['status']));
        }
        // 按名
        if (isset($option['name']) && is_string($option['name']) && !empty($option['name'])) {
            $query->whereLike('name', trim($option['name']) . '%');
        }
        // 时间搜索
        if (isset($option['start_time']) && $this->validate()->int($option['start_time'])) {
            $query->where('update_time', '>=', intval($option['start_time']));
        }
        if (isset($option['end_time']) && $this->validate()->int($option['end_time'])) {
            $query->where('update_time', '<=', intval($option['end_time']));
        }

        // 排序字段，默认id
        $order = 'id';
        if (isset($option['order']) && in_array($option['order'], ['id', 'create_time', 'update_time'])) {
            $order = $option['order'];
        }
        // 排序类型，默认 ASC
        $sort = 'ASC';
        if (isset($option['sort']) && in_array(strtoupper($option['sort']), ['ASC', 'DESC'])) {
            $sort = $option['sort'];
        }

        return $query->order($order, $sort);
    }
}
