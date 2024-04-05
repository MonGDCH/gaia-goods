<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use mon\thinkOrm\Dao;
use mon\util\Instance;
use plugins\goods\validate\GoodsValidate;

/**
 * 产品详情Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class GoodsDetailDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods_detail';

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
     * 新增
     *
     * @param array $data
     * @return integer
     */
    public function add(array $data): int
    {
        $check = $this->validate()->data($data)->scope('detail')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return 0;
        }
        if (isset($data['attrs']) && !empty($data['attrs'])) {
            $attrs = [];
            $goodsAttr = json_decode($data['attrs'], true);
            if (!$goodsAttr) {
                $this->error = '商品属性数据格式错误';
                return 0;
            }
            foreach ($goodsAttr as $v) {
                if (!isset($v['title']) || empty(trim($v['title']))) {
                    $this->error = '商品属性数据格式错误[title]';
                    return 0;
                }
                if (!isset($v['value'])) {
                    $this->error = '商品属性数据格式错误[value]';
                    return 0;
                }
                $attrs[] = $v;
            }
            $data['attrs'] = json_encode($attrs, JSON_UNESCAPED_UNICODE);
        }

        $data['goods_id'] = $data['idx'];
        $data['covers'] = (isset($data['imgs']) && empty($data['imgs'])) ? '' : $data['imgs'];
        $field = ['goods_id', 'content', 'attrs', 'specs', 'covers', 'seo_title', 'seo_keyword', 'seo_desc', 'recommends'];
        $detail_id = $this->allowField($field)->save($data, true, true);
        if (!$detail_id) {
            $this->error = '保存详情失败';
            return 0;
        }

        return $detail_id;
    }

    /**
     * 更新
     *
     * @param array $data
     * @return boolean
     */
    public function edit(array $data): bool
    {
        $check = $this->validate()->data($data)->scope('detail')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }
        if (isset($data['attrs']) && !empty($data['attrs'])) {
            $attrs = [];
            $goodsAttr = json_decode($data['attrs'], true);
            if (!$goodsAttr) {
                $this->error = '商品属性数据格式错误';
                return false;
            }
            foreach ($goodsAttr as $v) {
                if (!isset($v['title']) || empty(trim($v['title']))) {
                    $this->error = '商品属性数据格式错误[title]';
                    return false;
                }
                if (!isset($v['value'])) {
                    $this->error = '商品属性数据格式错误[value]';
                    return false;
                }
                $attrs[] = $v;
            }
            $data['attrs'] = json_encode($attrs, JSON_UNESCAPED_UNICODE);
        }
        $data['covers'] = (isset($data['imgs']) && empty($data['imgs'])) ? '' : $data['imgs'];
        $field = ['content', 'attrs', 'specs', 'covers', 'seo_title', 'seo_keyword', 'seo_desc', 'recommends'];
        $saveID = $this->allowField($field)->where('goods_id', $data['idx'])->save($data);
        if (!$saveID) {
            $this->error = '保存详情失败';
            return false;
        }

        return true;
    }
}
