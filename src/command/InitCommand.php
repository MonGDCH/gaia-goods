<?php

declare(strict_types=1);

namespace gaia\goods\command;

use Throwable;
use mon\util\Sql;
use ErrorException;
use mon\env\Config;
use think\facade\Db;
use mon\thinkORM\Dao;
use mon\console\Input;
use mon\console\Output;
use mon\console\Command;
use plugins\admin\dao\MenuDao;
use plugins\admin\dao\AuthRuleDao;

/**
 * 数据库初始化
 *
 * @author Mon <98555883@qq.com>
 * @version 1.0.0
 */
class InitCommand extends Command
{
    /**
     * 指令名
     *
     * @var string
     */
    protected static $defaultName = 'goods:init';

    /**
     * 指令描述
     *
     * @var string
     */
    protected static $defaultDescription = 'Initialization goods database';

    /**
     * 指令分组
     *
     * @var string
     */
    protected static $defaultGroup = 'Admin';

    /**
     * 菜单
     *
     * @var array
     */
    protected $menu = [
        ['name' => 'goods', 'title' => '产品管理', 'icon' => 'layui-icon layui-icon-app', 'chilid' => [
            ['name' => 'model', 'title' => '产品模型', 'icon' => 'layui-icon layui-icon-template-1', 'chilid' => [
                ['name' => '/goods/models', 'title' => '模型管理', 'icon' => 'layui-icon layui-icon-template-1'],
                ['name' => '/goods/attr', 'title' => '属性管理', 'icon' => 'layui-icon layui-icon-template-1'],
                ['name' => '/goods/spec', 'title' => '规格管理', 'icon' => 'layui-icon layui-icon-template-1'],
            ]],
            ['name' => '/goods/cate', 'title' => '产品分类', 'icon' => 'layui-icon layui-icon-cols'],
            ['name' => '/goods/brand', 'title' => '产品品牌', 'icon' => 'layui-icon layui-icon-note'],
            ['name' => '/goods/product', 'title' => '产品列表', 'icon' => 'layui-icon layui-icon-gift'],
            ['name' => '/goods/shipping', 'title' => '配送模板', 'icon' => 'layui-icon layui-icon-template'],
        ]],
    ];

    /**
     * 权限
     *
     * @var array
     */
    protected $rule = [
        ['name' => 'goods', 'title' => '产品管理', 'chilid' => [
            ['name' => 'goods_cate', 'title' => '产品分类', 'chilid' => [
                ['name' => '/goods/cate', 'title' => '查看'],
                ['name' => '/goods/cate/add', 'title' => '新增'],
                ['name' => '/goods/cate/edit', 'title' => '编辑']
            ]],
            ['name' => 'goods_brand', 'title' => '产品品牌', 'chilid' => [
                ['name' => '/goods/brand', 'title' => '查看'],
                ['name' => '/goods/brand/add', 'title' => '新增'],
                ['name' => '/goods/brand/edit', 'title' => '编辑']
            ]],
            ['name' => 'goods_shipping', 'title' => '发货模板', 'chilid' => [
                ['name' => '/goods/shipping', 'title' => '查看'],
                ['name' => '/goods/shipping/add', 'title' => '新增'],
                ['name' => '/goods/shipping/edit', 'title' => '编辑']
            ]],
            ['name' => 'goods_attr', 'title' => '产品属性', 'chilid' => [
                ['name' => '/goods/attr', 'title' => '查看'],
                ['name' => '/goods/attr/add', 'title' => '新增'],
                ['name' => '/goods/attr/edit', 'title' => '编辑']
            ]],
            ['name' => 'goods_spec', 'title' => '产品规格', 'chilid' => [
                ['name' => '/goods/spec', 'title' => '查看'],
                ['name' => '/goods/spec/add', 'title' => '新增'],
                ['name' => '/goods/spec/edit', 'title' => '编辑']
            ]],
            ['name' => 'goods_models', 'title' => '产品模型', 'chilid' => [
                ['name' => '/goods/models', 'title' => '查看'],
                ['name' => '/goods/models/add', 'title' => '新增'],
                ['name' => '/goods/models/edit', 'title' => '编辑'],
                ['name' => '/goods/models/detail', 'title' => '详情']
            ]],
            ['name' => 'goods_product', 'title' => '产品列表', 'chilid' => [
                ['name' => '/goods/product', 'title' => '查看'],
                ['name' => '/goods/product/add', 'title' => '新增'],
                ['name' => '/goods/product/edit', 'title' => '编辑'],
                ['name' => '/goods/product/preview', 'title' => '预览'],
                ['name' => '/goods/product/audit', 'title' => '出库'],
                ['name' => '/goods/product/inventory', 'title' => '库存管理'],
                ['name' => '/goods/product/marketing', 'title' => '运营管理'],
                ['name' => '/goods/product/saleStatus', 'title' => '上下架'],
            ]],
        ]]
    ];

    /**
     * 执行指令
     *
     * @param  Input  $in  输入实例
     * @param  Output $out 输出实例
     * @return integer  exit状态码
     */
    public function execute(Input $in, Output $out)
    {
        // 读取sql文件
        $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database.sql';
        $sqls = Sql::instance()->parseFile($file);
        // 执行sql
        Db::setConfig(Config::instance()->get('database', []));
        $out->block('Installation bootstrap');
        $out->spinBegiin();
        foreach ($sqls as $i => $sql) {
            Db::execute($sql);
            if ($i % 3 == 0) {
                $out->spin();
            }
        }

        $out->spin();

        $this->createMenu($this->menu, MenuDao::instance());
        $out->spin();
        $this->createRule($this->rule, AuthRuleDao::instance());

        $out->spinEnd();
        $out->block('Installation done!', 'SUCCESS');
    }

    /**
     * 创建菜单
     *
     * @param array $list   菜单列表
     * @param Dao $dao      菜单Dao操作实例
     * @param integer $pid  父级ID
     * @return void
     */
    public function createMenu(array $list, Dao $dao, int $pid = 0)
    {
        $dao->startTrans();
        try {
            foreach ($list as $item) {
                // 判断是否存在后代
                $hasChild = isset($item['chilid']) && $item['chilid'] ? true : false;
                // 写入记录
                $data = [
                    'pid'   => $pid,
                    'name'  => $item['name'],
                    'title' => $item['title'],
                    'icon'  => $item['icon'],
                    'type'  => $hasChild ? '0' : '1',
                ];
                $menu_id = $dao->save($data, true, true);
                if (!$menu_id) {
                    $dao->rollback();
                    throw new ErrorException('新增菜单失败：' . $item['name']);
                }
                // 判断是否存在后代，存在则递归执行
                if ($hasChild) {
                    $this->createMenu($item['chilid'], $dao, $menu_id);
                }
            }

            $dao->commit();
            return;
        } catch (Throwable $e) {
            $dao->rollback();
            throw $e;
        }
    }

    /**
     * 创建权限
     *
     * @param array $list   权限列表
     * @param Dao $dao      权限Dao操作实例
     * @param integer $pid  父级ID
     * @return void
     */
    public function createRule(array $list, Dao $dao, int $pid = 0)
    {
        $dao->startTrans();
        try {
            foreach ($list as $item) {
                // 判断是否存在后代
                $hasChild = isset($item['chilid']) && $item['chilid'] ? true : false;
                // 写入记录
                $data = [
                    'pid'   => $pid,
                    'name'  => $item['name'],
                    'title' => $item['title'],
                ];
                $rule_id = $dao->save($data, true, true);
                if (!$rule_id) {
                    $dao->rollback();
                    throw new ErrorException('新增权限失败：' . $item['name']);
                }
                // 判断是否存在后代，存在则递归执行
                if ($hasChild) {
                    $this->createRule($item['chilid'], $dao, $rule_id);
                }
            }

            $dao->commit();
            return;
        } catch (Throwable $e) {
            $dao->rollback();
            throw $e;
        }
    }
}
