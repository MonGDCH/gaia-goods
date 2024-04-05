<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use Throwable;
use mon\log\Logger;
use mon\thinkOrm\Dao;
use RuntimeException;
use mon\util\Instance;
use plugins\admin\dao\AdminLogDao;
use plugins\goods\contract\GoodsEmun;
use plugins\goods\validate\GoodsValidate;

/**
 * 产品Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class GoodsDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods';

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
    protected $validate = GoodsValidate::class;

    /**
     * 库存警戒值
     *
     * @var integer
     */
    protected $cost_warning_limit = 100;

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
            Logger::instance()->channel()->info('Add goods');
            $field = [
                'cate_cat', 'brand_id', 'virtual', 'name', 'marque', 'barcode', 'remark', 'thumb', 'sort', 'unit', 'shipping', 'shipping_id',
                'buy_limit', 'buy_limit_type', 'buy_limit_round', 'buy_limit_max', 'buy_limit_min', 'stock_reduce', 'stock_visible', 'sales_visible',
                'is_hot', 'is_recommend', 'is_new', 'is_bill', 'view_num', 'star_num', 'collect_num', 'comment_num', 'transmit_num',
                'sale_start_time', 'sale_end_time', 'sale_status', 'valuation'
            ];
            $goods_id = $this->allowField($field)->save($data, true, true);
            if (!$goods_id) {
                $this->rollback();
                $this->error = '新增产品失败';
                return 0;
            }

            // 保存详情
            $data['idx'] = $goods_id;
            $saveDetail = GoodsDetailDao::instance()->add($data);
            if (!$saveDetail) {
                $this->rollback();
                $this->error = GoodsDetailDao::instance()->getError();
                return 0;
            }

            // 添加产品
            $saveProduct = ProductDao::instance()->batchAdd(json_decode($data['sku'], true), $goods_id);
            if (!$saveProduct) {
                $this->rollback();
                $this->error = ProductDao::instance()->getError();
                return 0;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '新增产品',
                    'content' => '新增产品：' . $data['name'],
                    'sid' => $goods_id
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return 0;
                }
            }

            $this->commit();
            return $goods_id;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '新增产品异常';
            Logger::instance()->channel()->error('Add goods exception, msg => ' . $e->getMessage(), ['trace' => true]);
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
            $this->error = '产品不存在';
            return false;
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('Edit goods');
            $field = [
                'cate_cat', 'brand_id', 'virtual', 'name', 'marque', 'barcode', 'remark', 'thumb', 'sort', 'unit', 'shipping', 'shipping_id',
                'buy_limit', 'buy_limit_type', 'buy_limit_round', 'buy_limit_max', 'buy_limit_min', 'stock_reduce', 'stock_visible', 'sales_visible',
                'is_hot', 'is_recommend', 'is_new', 'is_bill', 'view_num', 'star_num', 'collect_num', 'comment_num', 'transmit_num',
                'sale_start_time', 'sale_end_time', 'sale_status', 'valuation', 'status'
            ];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '保存产品失败';
                return false;
            }

            // 保存详情
            $saveDetail = GoodsDetailDao::instance()->edit($data);
            if (!$saveDetail) {
                $this->rollback();
                $this->error = GoodsDetailDao::instance()->getError();
                return false;
            }

            // 保存产品sku商品
            $saveProduct = ProductDao::instance()->resetProduct(json_decode($data['sku'], true), $info['id']);
            if (!$saveProduct) {
                $this->rollback();
                $this->error = ProductDao::instance()->getError();
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '保存产品',
                    'content' => '保存产品：' . $data['name'],
                    'sid' => $data['idx']
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
            $this->error = '保存产品异常';
            Logger::instance()->channel()->error('Edit goods exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }


    /**
     * 修改营销信息
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function marketing(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('marketing')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        $info = $this->where('id', $data['idx'])->get();
        if (!$info) {
            $this->error = '产品不存在';
            return false;
        }
        $this->startTrans();
        try {
            $field = [
                'cate_cat', 'name', 'thumb', 'valuation', 'sort', 'shipping', 'shipping_id',
                'is_hot', 'is_new', 'is_recommend', 'sale_start_time', 'sale_end_time'
            ];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '修改营销信息失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '修改产品营销信息',
                    'content' => '修改产品营销信息：' . $info['id'],
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
            $this->error = '编辑产品营销信息异常';
            Logger::instance()->channel()->error('Edit goods marketing exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }

    /**
     * 审核产品
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function audit(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('audit')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        // 获取用户信息
        $info = $this->where(['id' => $data['idx']])->get();
        if (!$info) {
            $this->error = '产品不存在';
            return false;
        }

        if ($data['status'] == $info['status']) {
            $this->error = '审核的状态与原状态一致';
            return false;
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('audit goods status');
            $saveData = [
                'status' => $data['status'],
                'audit_time' => time()
            ];
            // 处理出库并上架
            if ($data['status'] == GoodsEmun::GOODS_STATUS['pass'] && isset($data['sale_status']) && $data['sale_status'] == GoodsEmun::GOODS_SALE_STATUS['sale']) {
                $saveData['sale_status'] = GoodsEmun::GOODS_SALE_STATUS['sale'];
            }
            $save = $this->where('id', $info['id'])->save($saveData);
            if (!$save) {
                $this->rollback();
                $this->error = '审核产品失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '产品出库',
                    'content' => '修改产品出库状态为: ' . $data['status'] . ', 产品ID: ' . $data['idx'],
                    'sid' => $data['idx']
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();
                    return false;
                }
            }

            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '审核产品异常';
            Logger::instance()->channel()->error('audit goods status exception, msg => ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 修改销售状态
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function toggle(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('toggle')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        // 获取用户信息
        $info = $this->where(['id' => $data['idx']])->get();
        if (!$info) {
            $this->error = '产品不存在';
            return false;
        }

        if ($info['status'] != GoodsEmun::GOODS_STATUS['pass']) {
            $this->error = '产品未出库';
            return false;
        }

        if ($info['sale_status'] == $data['sale_status']) {
            $this->error = '修改的状态与原状态一致';
            return false;
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('modify goods sale status');
            $save = $this->where('id', $info['id'])->save(['sale_status' => $data['sale_status']]);
            if (!$save) {
                $this->rollback();
                $this->error = '修改产品销售状态失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '修改产品销售状态',
                    'content' => '修改产品状态为: ' . $data['sale_status'] . ', 产品ID: ' . $data['idx'],
                    'sid' => $data['idx']
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();
                    return false;
                }
            }

            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '修改产品销售状态异常';
            Logger::instance()->channel()->error('modify goods sale status exception, msg => ' . $e->getMessage());
            return false;
        }
    }


    /**
     * 获取产品信息
     *
     * @param array $where  查询条件
     * @return array
     */
    public function getInfo(array $where = []): array
    {
        $field = [
            'a.id', 'a.cate_cat', 'a.brand_id', 'a.virtual', 'a.name', 'a.valuation', 'a.marque', 'a.barcode', 'a.remark', 'a.thumb', 'a.sort', 'a.unit',
            'a.shipping', 'a.shipping_id', 'a.buy_limit', 'a.buy_limit_type', 'a.buy_limit_round', 'a.buy_limit_max', 'a.buy_limit_min', 'a.stock_reduce',
            'a.stock_visible', 'a.sales_visible', 'a.is_hot', 'a.is_recommend', 'a.is_new', 'a.view_num', 'a.star_num', 'a.collect_num', 'a.comment_num',
            'a.transmit_num', 'a.sale_start_time', 'a.sale_end_time', 'a.sale_status', 'a.status', 'a.cate_cat', 'a.audit_time', 'a.create_time', 'a.update_time',
            'b.specs', 'b.attrs', 'b.covers', 'b.recommends', 'b.content', 'b.seo_title', 'b.seo_keyword', 'b.seo_desc'
        ];
        $info = $this->alias('a')->join(GoodsDetailDao::instance()->getTable() . ' b', 'a.id = b.goods_id')->field($field)->where($where)->get();
        if (!$info) {
            $this->error = '产品不存在';
            return [];
        }
        // 处理产品分类
        $info['cate_list'] = [];
        if (!empty($info['cate_cat'])) {
            $info['cate_list'] = CateDao::instance()->where('id', 'IN', array_unique(explode(',', $info['cate_cat'])))->column('name');
        }
        // 处理关联的推广产品信息
        $info['recommend_goods'] = [];
        if (!empty($info['recommends'])) {
            $field = ['id', 'name', 'thumb', 'valuation', 'is_hot', 'is_recommend', 'is_new'];
            $info['recommend_goods'] = $this->field($field)->where('id', 'IN', array_unique(explode(',', $info['recommends'])))->all();
        }
        // 规格产品信息
        $info['product'] = [];
        $prodict = ProductDao::instance()->where('goods_id', $info['id'])->order('sort DESC, id ASC')->all();
        // 处理produce中的json数据
        foreach ($prodict as &$item) {
            $item['sku'] = json_decode($item['sku'], true);
            $info['product'][] = $item;
        }

        return $info;
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
        $total = $this->scope('count', $data)->count();

        // 产品类型
        $cateTitleList = CateDao::instance()->column('name', 'id');
        foreach ($list as &$item) {
            // 产品类型描述
            $item['virtual_txt'] = GoodsEmun::VIRTUAL_TYPE_TITLE[$item['virtual']] ?? '未知';
            // 产品分类描述
            $cates = [];
            foreach (array_unique(explode(',', $item['cate_cat'])) as $v) {
                $cates[] = $cateTitleList[$v] ?? '未知';
            }
            $item['cate_txt'] = implode(', ', $cates);
        }

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
     * @return \mon\thinkOrm\extend\Query
     */
    protected function scopeList($query, $option)
    {
        $field = 'a.id, a.cate_cat, a.name, a.sort, a.status, a.sale_status, a.thumb, a.unit, a.shipping, a.virtual, a.buy_limit,
                a.is_hot, a.is_recommend, a.is_new, a.star_num, a.collect_num, a.comment_num, a.sale_start_time, a.sale_end_time,
                SUM( IF ( b.stock, b.stock, 0 ) ) AS stock_total, SUM( IF ( b.sales, b.sales, 0 ) ) AS sales_total, 
                a.create_time, a.update_time, a.valuation';
        $query->alias('a')->join(ProductDao::instance()->getTable() . ' b', 'a.id = b.goods_id', 'left')->field($field)->group('a.id');

        // 解析查询条件
        $query = $this->parseQueryWhere($query, $option);

        // 排序字段，默认 sort
        $order = 'id';
        if (isset($option['order']) && in_array($option['order'], [
            'id', 'valuation', 'sort', 'update_time', 'create_time', 'sale_status',
            'view_num', 'star_num', 'collect_num', 'comment_num', 'transmit_num',
            'sales_total', 'stock_total', 'status'
        ])) {
            $order = $option['order'];
        }
        // 排序类型，默认 DESC
        $sort = 'DESC';
        if (isset($option['sort']) && in_array(strtoupper($option['sort']), ['ASC', 'DESC'])) {
            $sort = strtoupper($option['sort']);
        }

        return $query->order($order, $sort);
    }

    /**
     * 查询记录数场景，搭配查询列表场景使用
     *
     * @param \mon\thinkOrm\extend\Query $query
     * @param array $option
     * @return \mon\thinkOrm\extend\Query
     */
    protected function scopeCount($query, $option)
    {
        // 查询库存告警记录特殊处理
        if (isset($option['scene']) && $option['scene'] == '2') {
            $sql = $this->scopeList($query, $option)->fetchSql()->select();
            return $this->table("($sql) AS t");
        }

        $query->alias('a');
        return $this->parseQueryWhere($query, $option);
    }

    /**
     * 查询列表场景
     *
     * @param \mon\thinkOrm\extend\Query $query
     * @param array $option
     * @return \mon\thinkOrm\extend\Query
     */
    protected function parseQueryWhere($query, $option)
    {
        // 场景查询
        if (isset($option['scene'])) {
            switch ($option['scene']) {
                case GoodsEmun::GOODS_SECNE_TYPE['warehouse']:
                    // 仓库中
                    $query->where('a.status', '<>', 1);
                    break;
                case  GoodsEmun::GOODS_SECNE_TYPE['sale']:
                    // 销售中
                    $now = time();
                    $query->where('a.sale_start_time', '<=', $now)->where('a.sale_end_time', '>=', $now)->where('a.sale_status', 1)->where('a.status', 1);
                    break;
                case  GoodsEmun::GOODS_SECNE_TYPE['alert']:
                    // 库存预警
                    $now = time();
                    $query->where('a.sale_start_time', '<=', $now)->where('a.sale_end_time', '>=', $now)->where('a.sale_status', 1)->where('a.status', 1);
                    $query->having("stock_total < {$this->cost_warning_limit}");
                    break;
                case  GoodsEmun::GOODS_SECNE_TYPE['pre_sale']:
                    // 预售中
                    $now = time();
                    $query->where('a.status', 1)->where('a.sale_start_time', '>', $now)->where('a.sale_status', 1);
                    break;
                case  GoodsEmun::GOODS_SECNE_TYPE['not_sale']:
                    // 未上架
                    $now = time();
                    $query->where('a.status', 1);
                    $query->whereRaw("(a.`sale_end_time` < {$now} AND a.`sale_status` = 1) OR a.`sale_status` = 0");
                    break;
                case  GoodsEmun::GOODS_SECNE_TYPE['all']:
                    // 所有
                    break;
                default:
                    throw new RuntimeException('[scene]参数错误');
            }
        }

        // ID 搜索
        if (isset($option['idx']) && $this->validate()->int($option['idx'])) {
            $query->where('a.id', intval($option['idx']));
        }
        // 分类搜索
        if (isset($option['cate']) && $this->validate()->int($option['cate'])) {
            $query->whereFindInSet('a.cate_cat', $option['cate']);
        }
        // status搜索
        if (isset($option['status']) && $this->validate()->int($option['status'])) {
            $query->where('a.status', intval($option['status']));
        }
        // sale_status搜索
        if (isset($option['sale_status']) && $this->validate()->int($option['sale_status'])) {
            $query->where('a.sale_status', intval($option['sale_status']));
        }
        // 品牌
        if (isset($option['brand_id']) && $this->validate()->int($option['brand_id'])) {
            $query->where('a.brand_id', intval($option['brand_id']));
        }
        // 商户
        if (isset($option['business_id']) && $this->validate()->id($option['business_id'])) {
            $query->where('a.business_id', intval($option['business_id']));
        }
        // 热销
        if (isset($option['is_hot']) && $this->validate()->int($option['is_hot'])) {
            $query->where('a.is_hot', intval($option['is_hot']));
        }
        // 推荐
        if (isset($option['is_recommend']) && $this->validate()->int($option['is_recommend'])) {
            $query->where('a.is_recommend', intval($option['is_recommend']));
        }
        // 新品
        if (isset($option['is_new']) && $this->validate()->int($option['is_new'])) {
            $query->where('a.is_new', intval($option['is_new']));
        }
        // 开具发票
        if (isset($option['is_bill']) && $this->validate()->int($option['is_bill'])) {
            $query->where('a.is_bill', intval($option['is_bill']));
        }
        // 限购
        if (isset($option['buy_limit']) && $this->validate()->int($option['buy_limit'])) {
            $query->where('a.buy_limit', intval($option['buy_limit']));
        }
        // 按名
        if (isset($option['name']) && is_string($option['name']) && !empty($option['name'])) {
            $query->whereLike('a.name', '%' . trim($option['name']) . '%');
        }
        // 上下架时间搜索
        if (isset($option['sale_time']) && $this->validate()->int($option['sale_time'])) {
            $query->where('a.sale_start_time', '>=', intval($option['sale_time']));
        }
        if (isset($option['sale_time']) && $this->validate()->int($option['sale_time'])) {
            $query->where('a.sale_end_time', '<=', intval($option['sale_time']));
        }
        // 时间搜索
        if (isset($option['start_time']) && $this->validate()->int($option['start_time'])) {
            $query->where('a.update_time', '>=', intval($option['start_time']));
        }
        if (isset($option['end_time']) && $this->validate()->int($option['end_time'])) {
            $query->where('a.update_time', '<=', intval($option['end_time']));
        }

        return $query;
    }
}
