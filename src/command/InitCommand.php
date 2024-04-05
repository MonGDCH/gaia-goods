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
        ['name' => 'goods', 'title' => '商品管理', 'icon' => 'layui-icon layui-icon-app', 'chilid' => [
            ['name' => 'model', 'title' => '产品模型', 'icon' => 'layui-icon layui-icon-template-1', 'chilid' => [
                ['name' => '/goods/models', 'title' => '模型管理', 'icon' => 'layui-icon layui-icon-template-1'],
                ['name' => '/goods/attr', 'title' => '属性管理', 'icon' => 'layui-icon layui-icon-template-1'],
                ['name' => '/goods/spec', 'title' => '规格管理', 'icon' => 'layui-icon layui-icon-template-1'],
            ]],
            ['name' => '/goods/cate', 'title' => '产品分类', 'icon' => 'layui-icon layui-icon-cols'],
            ['name' => '/goods/brand', 'title' => '产品品牌', 'icon' => 'layui-icon layui-icon-note'],
            ['name' => '/goods/product', 'title' => '产品管理', 'icon' => 'layui-icon layui-icon-gift'],
            ['name' => '/goods/shipping', 'title' => '配送模板', 'icon' => 'layui-icon layui-icon-template'],
        ]],
    ];

    /**
     * 权限
     *
     * @var array
     */
    protected $rule = [
        ['name' => 'goods', 'title' => '内容管理', 'chilid' => [
            ['name' => 'ad', 'title' => '区块广告', 'chilid' => [
                ['name' => '/goods/ad', 'title' => '查看'],
                ['name' => '/goods/ad/add', 'title' => '新增'],
                ['name' => '/goods/ad/edit', 'title' => '编辑'],
                ['name' => 'adAssets', 'title' => '区块资源', 'chilid' => [
                    ['name' => '/goods/ad/assets', 'title' => '查看'],
                    ['name' => '/goods/ad/assets/add', 'title' => '新增'],
                    ['name' => '/goods/ad/assets/edit', 'title' => '编辑'],
                ]],
            ]],
            ['name' => 'cate', 'title' => '栏目分类', 'chilid' => [
                ['name' => '/goods/cate', 'title' => '查看'],
                ['name' => '/goods/cate/add', 'title' => '新增'],
                ['name' => '/goods/cate/edit', 'title' => '编辑'],
            ]],
            ['name' => 'article', 'title' => '内容管理', 'chilid' => [
                ['name' => '/goods/article', 'title' => '查看'],
                ['name' => '/goods/article/add', 'title' => '新增'],
                ['name' => '/goods/article/edit', 'title' => '编辑'],
                ['name' => '/goods/article/detail', 'title' => '查看详情'],
                ['name' => '/goods/article/preview', 'title' => '预览'],
                ['name' => '/goods/article/interact', 'title' => '修改互动信息'],
                ['name' => '/goods/article/displays', 'title' => '修改展示信息'],
                ['name' => '/goods/article/toggle', 'title' => '修改状态'],
                ['name' => 'articleTag', 'title' => '内容标签', 'chilid' => [
                    ['name' => '/goods/article/tag', 'title' => '查看'],
                    ['name' => '/goods/article/tag/add', 'title' => '新增'],
                    ['name' => '/goods/article/tag/edit', 'title' => '编辑'],
                ]],
            ]],
            ['name' => 'page', 'title' => '独立页面', 'chilid' => [
                ['name' => '/goods/page', 'title' => '查看'],
                ['name' => '/goods/page/add', 'title' => '新增'],
                ['name' => '/goods/page/edit', 'title' => '编辑'],
                ['name' => '/goods/page/preview', 'title' => '预览'],
                ['name' => '/goods/page/interact', 'title' => '修改互动信息'],
                ['name' => '/goods/page/displays', 'title' => '修改展示信息'],
                ['name' => '/goods/page/toggle', 'title' => '修改状态'],
            ]],
            ['name' => 'comment', 'title' => '用户评论', 'chilid' => [
                ['name' => '/goods/comment', 'title' => '查看'],
                ['name' => '/goods/comment/add', 'title' => '新增'],
                ['name' => '/goods/comment/edit', 'title' => '编辑'],
                ['name' => '/goods/comment/preview', 'title' => '预览'],
                ['name' => '/goods/comment/interact', 'title' => '修改互动信息'],
                ['name' => '/goods/comment/displays', 'title' => '修改展示信息'],
                ['name' => '/goods/comment/toggle', 'title' => '修改状态'],
            ]]
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
            if ($i % 5 == 0) {
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
