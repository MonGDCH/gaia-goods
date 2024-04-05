<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use Throwable;
use mon\log\Logger;
use mon\thinkOrm\Dao;
use mon\util\Instance;
use app\admin\dao\AdminLogDao;
use plugins\goods\validate\GoodsValidate;

/**
 * 产品sku Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ProductDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods_product';

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
     * 添加产品规格
     *
     * @param array $data
     * @param integer $goods_id
     * @param integer $adminID
     * @return integer
     */
    public function add(array $data, int $goods_id, int $adminID): int
    {
        $check = $this->validate()->data($data)->scope('product')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return 0;
        }

        $goodsInfo = GoodsDao::instance()->where('id', $goods_id)->get();
        if (!$goodsInfo) {
            $this->error = '产品不存在';
            return 0;
        }
        $data['goods_id'] = $goods_id;

        $this->startTrans();
        try {
            $product_id = $this->allowField(['goods_id', 'sku', 'img', 'price', 'cost_price', 'market_price', 'stock', 'code', 'weight', 'volume'])->save($data, true, true);
            if (!$product_id) {
                $this->rollback();
                $this->error = '新增产品sku商品失败';
                return 0;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '新增产品sku商品',
                    'content' => '新增产品[' . $goods_id . ']sku商品：' . $product_id,
                    'sid' => $product_id
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return 0;
                }
            }

            $this->commit();
            return $product_id;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '新增产品sku商品异常';
            Logger::instance()->channel()->error('Add product exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return 0;
        }
    }

    /**
     * 修改产品
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function edit(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('edit_product')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        $info = $this->where('id', $data['idx'])->get();
        if (!$info) {
            $this->error = '商品不存在';
            return false;
        }
        $this->startTrans();
        try {
            $field = ['img', 'price', 'cost_price', 'market_price', 'stock', 'code', 'weight', 'volume'];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '修改商品信息失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '修改商品信息',
                    'content' => '修改商品信息：' . $info['id'],
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
            $this->error = '编辑商品信息异常';
            Logger::instance()->channel()->error('Edit product exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }

    /**
     * 批量更新
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function batchEdit(array $data, int $adminID)
    {
        $ids = [];
        foreach ($data as $k => $v) {
            $line = $k + 1;
            $check = $this->validate()->data($v)->scope('edit_product')->check();
            if (!$check) {
                $this->error = "[{$line}] " . $this->validate()->getError();
                return false;
            }
            $ids[] = $v['idx'];
        }
        $ids = implode(',', array_unique($ids));

        // 生成更新语句
        $field = ['img', 'price', 'cost_price', 'market_price', 'stock', 'code', 'weight', 'volume'];
        $sql = "UPDATE `goods_product` SET ";
        foreach ($field as $v) {
            $str = "`{$v}` = CASE ";
            foreach ($data as $line) {
                $txt = "WHEN `id` = '{$line['idx']}' THEN '{$line[$v]}' ";
                $str .= $txt;
            }
            $str .= "ELSE `{$v}` ";
            $sql .= $str . ' END, ';
        }
        $now = time();
        $sql .= " `update_time` = {$now} WHERE `id` IN ({$ids})";

        $this->startTrans();
        try {
            $save = $this->execute($sql);
            if (!$save) {
                $this->rollback();
                $this->error = '更新商品信息失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '批量修改商品信息',
                    'content' => '批量修改商品信息：' . $ids,
                    'sid' => 0
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
            $this->error = '编辑商品信息异常';
            Logger::instance()->channel()->error('Edit products exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }

    /**
     * 批量新增产品
     *
     * @param array $data
     * @param integer $goods_id
     * @return boolean
     */
    public function batchAdd(array $data, int $goods_id): bool
    {
        $now = time();
        $saveData = [];
        foreach ($data as $k => $val) {
            $line = $k + 1;
            $check = $this->validate()->data($val)->scope('product')->check();
            if (!$check) {
                $this->error = "[{$line}] " . $this->validate()->getError();
                return false;
            }
            // 验证产品规格
            $sku = json_decode($val['sku'], true);
            foreach ($sku as $i => $v) {
                if (empty($v['t']) || empty($v['v']) || $v['i'] != $i) {
                    $this->error = "[{$line}] sku规格参数异常";
                    return false;
                }
            }
            $saveData[] = [
                'goods_id' => $goods_id,
                'sku' => $val['sku'],
                'img' => $val['img'],
                'price' => $val['price'],
                'cost_price' => $val['cost_price'],
                'market_price' => $val['market_price'],
                'stock' => $val['stock'],
                'weight' => $val['weight'],
                'volume' => $val['volume'],
                'code' => $val['code'],
                'update_time' => $now,
                'create_time' => $now,
            ];
        }
        if (empty($saveData)) {
            $this->error = '产品sku详情不能为空';
            return false;
        }

        $save = $this->insertAll($saveData);
        if (!$save) {
            $this->error = '保存产品规格详情失败';
            return false;
        }

        return true;
    }

    /**
     * 重新设置产品商品
     *
     * @param array $data
     * @param integer $goods_id
     * @return boolean
     */
    public function resetProduct(array $data, int $goods_id): bool
    {
        $this->startTrans();
        try {
            $remove = $this->where('goods_id', $goods_id)->delete();
            if (!$remove) {
                $this->rollback();
                $this->error = '清除sku商品信息失败';
                return false;
            }
            $add = $this->batchAdd($data, $goods_id);
            if (!$add) {
                $this->rollback();
                return false;
            }

            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '批量新增产品sku商品异常';
            Logger::instance()->channel()->error('Add products exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return false;
        }
    }
}
