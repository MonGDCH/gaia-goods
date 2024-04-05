<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use Throwable;
use mon\log\Logger;
use mon\thinkOrm\Dao;
use mon\util\Instance;
use app\admin\dao\AdminLogDao;
use plugins\goods\contract\GoodsCateEmun;
use plugins\goods\validate\BrandValidate;

/**
 * 站内信内容Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class BrandDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods_brand';

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
    protected $validate = BrandValidate::class;

    /**
     * 获取品牌数据(key=>value)形式
     *
     * @return array
     */
    public function getBrandData($where = []): array
    {
        $data = $this->where($where)->select();
        $result = [];
        foreach ($data as $item) {
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }

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

        if ($data['cate_id'] != 0) {
            $cateInfo = CateDao::instance()->where('id', $data['cate_id'])->where('status', GoodsCateEmun::CATE_STATUS['enable'])->get();
            if (!$cateInfo) {
                $this->error = '分类不存在或已禁用';
                return 0;
            }
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('Add goods brand');
            $field = ['cate_id', 'name', 'tel', 'web', 'logo', 'remark', 'status', 'sort'];
            $brand_id = $this->allowField($field)->save($data, true, true);
            if (!$brand_id) {
                $this->rollback();
                $this->error = '新增产品品牌失败';
                return 0;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '新增产品品牌',
                    'content' => '新增产品品牌：' . $data['name'],
                    'sid' => $brand_id
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return 0;
                }
            }

            $this->commit();
            return $brand_id;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '新增产品品牌异常';
            Logger::instance()->channel()->error('Add goods brand exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return 0;
        }
    }

    /**
     * 编辑
     *
     * @param array $data
     * @param integer $adminID
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
            $this->error = '产品品牌不存在';
            return false;
        }

        if ($data['cate_id'] != 0) {
            $cateInfo = CateDao::instance()->where('id', $data['cate_id'])->where('status', GoodsCateEmun::CATE_STATUS['enable'])->get();
            if (!$cateInfo) {
                $this->error = '分类不存在或已禁用';
                return false;
            }
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('Edit goods brand info');
            $field = ['cate_id', 'name', 'tel', 'web', 'logo', 'remark', 'status', 'sort'];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '修改产品品牌失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '编辑产品品牌',
                    'content' => '编辑产品品牌：' . $data['name'] . ', ID：' . $info['id'],
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
            $this->error = '编辑产品品牌异常';
            Logger::instance()->channel()->error('Edit goods brand exception, msg => ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 查询列表
     *
     * @param array $data 请求参数
     * @return array
     */
    public function getList(array $data): array
    {
        $limit = isset($data['limit']) ? intval($data['limit']) : 10;
        $page = isset($data['page']) && is_numeric($data['page']) ? intval($data['page']) : 1;
        // 查询
        $list = $this->scope('list', $data)->page($page, $limit)->all();
        $total = $this->scope('list', $data)->count();
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
        $query->alias('a')->leftJoin(CateDao::instance()->getTable() . " b", 'a.cate_id = b.id')->field(['a.*', 'b.name AS cate_name']);
        // 父级分类搜索
        if (isset($option['cate_id']) && $this->validate()->int($option['cate_id'])) {
            $query->where('a.cate_id', intval($option['cate_id']));
        }
        // status搜索
        if (isset($option['status']) && $this->validate()->int($option['status'])) {
            $query->where('a.status', intval($option['status']));
        }
        // 按名
        if (isset($option['name']) && is_string($option['name']) && !empty($option['name'])) {
            $query->whereLike('a.name', trim($option['name']) . '%');
        }
        // 时间搜索
        if (isset($option['start_time']) && $this->validate()->int($option['start_time'])) {
            $query->where('a.update_time', '>=', intval($option['start_time']));
        }
        if (isset($option['end_time']) && $this->validate()->int($option['end_time'])) {
            $query->where('a.update_time', '<=', intval($option['end_time']));
        }

        // 排序字段，默认 sort
        $order = 'a.sort';
        if (isset($option['order']) && in_array($option['order'], ['id', 'sort', 'update_time', 'create_time'])) {
            $order = 'a.' . $option['order'];
        }
        // 排序类型，默认 DESC
        $sort = 'DESC';
        if (isset($option['sort']) && in_array(strtoupper($option['sort']), ['ASC', 'DESC'])) {
            $sort = strtoupper($option['sort']);
        }

        return $query->order($order, $sort);
    }
}
