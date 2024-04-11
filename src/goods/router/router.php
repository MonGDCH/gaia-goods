<?php
/*
|--------------------------------------------------------------------------
| 定义应用请求路由
|--------------------------------------------------------------------------
| 通过Route类进行注册
|
*/

use mon\env\Config;
use mon\http\Route;
use plugins\goods\controller\CateController;
use plugins\goods\controller\AttrController;
use plugins\goods\controller\SpecController;
use plugins\admin\middleware\AuthMiddleware;
use plugins\admin\middleware\LoginMiddleware;
use plugins\goods\controller\BrandController;
use plugins\goods\controller\ModelsController;
use plugins\goods\controller\ProductController;
use plugins\goods\controller\ShippingTmpController;

Route::instance()->group(Config::instance()->get('admin.app.root_path', ''), function (Route $route) {
    $route->group(['path' => '/goods', 'middleware' => LoginMiddleware::class], function (Route $route) {
        // 权限控制
        $route->group(['middleware' => AuthMiddleware::class], function (Route $route) {
            // 分类管理
            $route->group('/cate', function (Route $route) {
                // 查看
                $route->get('', [CateController::class, 'index']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [CateController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [CateController::class, 'edit']);
            });

            // 品牌管理
            $route->group('/brand', function (Route $route) {
                // 查看
                $route->get('', [BrandController::class, 'index']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [BrandController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [BrandController::class, 'edit']);
            });

            // 配送模板
            $route->group('/shipping', function (Route $route) {
                // 查看
                $route->get('', [ShippingTmpController::class, 'index']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [ShippingTmpController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [ShippingTmpController::class, 'edit']);
            });

            // 模型管理
            $route->group('/models', function (Route $route) {
                // 查看
                $route->get('', [ModelsController::class, 'index']);
                // 获取模型信息
                $route->get('/detail', [ModelsController::class, 'detail']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [ModelsController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [ModelsController::class, 'edit']);
                // 绑定
                $route->map(['GET', 'POST'], '/bind', [ModelsController::class, 'bind']);
            });

            // 属性管理
            $route->group('/attr', function (Route $route) {
                // 查看
                $route->get('', [AttrController::class, 'index']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [AttrController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [AttrController::class, 'edit']);
            });

            // 规格管理
            $route->group('/spec', function (Route $route) {
                // 查看
                $route->get('', [SpecController::class, 'index']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [SpecController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [SpecController::class, 'edit']);
            });

            // 产品管理
            $route->group('/product', function (Route $route) {
                // 查看
                $route->get('', [ProductController::class, 'index']);
                // 预览
                $route->get('/preview', [ProductController::class, 'preview']);
                // 新增
                $route->map(['GET', 'POST'], '/add', [ProductController::class, 'add']);
                // 编辑
                $route->map(['GET', 'POST'], '/edit', [ProductController::class, 'edit']);
                // 出库
                $route->map(['GET', 'POST'], '/audit', [ProductController::class, 'audit']);
                // 库存管理
                $route->map(['GET', 'POST'], '/inventory', [ProductController::class, 'inventory']);
                // 运营管理
                $route->map(['GET', 'POST'], '/marketing', [ProductController::class, 'marketing']);
                // 上下架
                $route->post('/saleStatus', [ProductController::class, 'saleStatus']);
            });
        });
    });
});
