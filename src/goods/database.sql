CREATE TABLE `goods`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `business_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商户ID',
  `cate_cat` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所属分类,拼接cate_id',
  `brand_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '品牌ID',
  `virtual` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0实物产品, 1虚拟产品',
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `valuation` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '参考价',
  `marque` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '型号',
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '条码',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '简要描述',
  `thumb` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图URL',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序权重',
  `unit` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '计量单位，件、个',
  `shipping` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否需要配送',
  `shipping_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '物流模板ID',
  `buy_limit` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购买限制: 0不限制, 1限制',
  `buy_limit_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '限购类型: 0不限制, 1总数, 2按天, 3按周, 4按月',
  `buy_limit_round` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '自定义限购天数, 0则不限制',
  `buy_limit_max` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大限购, 0不限制',
  `buy_limit_min` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '最少起购',
  `stock_reduce` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否扣减库存',
  `stock_visible` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '库存显示 0不显示1显示',
  `sales_visible` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量显示 0不显示1显示',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否热销商品',
  `is_recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐',
  `is_new` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否新品',
  `is_bill` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否开具增值税发票 1是0否',
  `view_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品查看数',
  `star_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞数',
  `collect_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏数',
  `comment_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评价数',
  `transmit_num` int(11) NOT NULL DEFAULT 0 COMMENT '分享数',
  `sale_start_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上架时间',
  `sale_end_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '下架时间',
  `sale_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '销售状态: 0下架, 1上架, 2预售, 3违规(禁售)',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0审核中, 1通过, 2未通过',
  `audit_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核时间',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `virtual`(`virtual`) USING BTREE,
  INDEX `cate`(`cate_cat`) USING BTREE,
  INDEX `brand_id`(`brand_id`) USING BTREE,
  INDEX `view_num`(`view_num`) USING BTREE,
  INDEX `star_num`(`star_num`) USING BTREE,
  INDEX `collect_num`(`collect_num`) USING BTREE,
  INDEX `comment_num`(`comment_num`) USING BTREE,
  INDEX `transmit_num`(`transmit_num`) USING BTREE,
  INDEX `sale_start_time`(`sale_start_time`) USING BTREE,
  INDEX `sale_end_time`(`sale_end_time`) USING BTREE,
  INDEX `sort`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52899 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品主表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_attr`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '属性类型：0:input, 1:redio, 2:checkbox',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '属性名称',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值: 多个值以,分割',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品属性表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_brand`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cate_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名称',
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌联系电话',
  `web` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌网站',
  `logo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌logo的URL',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌描述',
  `sort` tinyint(1) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品品牌表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级分类ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类图片',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `sort` tinyint(1) UNSIGNED NOT NULL DEFAULT 50 COMMENT '权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_detail`  (
  `goods_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品表ID',
  `specs` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规格',
  `attrs` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品属性',
  `covers` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '轮播图库',
  `recommends` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '推荐产品ID列表',
  `seo_title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keyword` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容详情',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品详情表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_models`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `attr` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型关联属性ID以,分割',
  `spec` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型关联规格ID以,分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品模型' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_product`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) UNSIGNED NOT NULL COMMENT '商品id',
  `sku` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规格sku',
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'sku对应图片URL',
  `price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '价格',
  `cost_price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '成本价',
  `market_price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '市场价格',
  `stock` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '剩余库存',
  `sales` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品编码',
  `weight` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '重量(kg)',
  `volume` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '体积(立方米)',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `item`(`goods_id`, `sku`) USING BTREE,
  INDEX `goods_id`(`goods_id`) USING BTREE,
  INDEX `sku`(`sku`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品sku表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_shipping_tmp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板名称',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0按件数 1按重量 2按体积',
  `first` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '首件',
  `first_price` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '首件运费',
  `continue` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '续件',
  `continue_price` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '续件运费',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 10 COMMENT '排序',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:正常,0:禁用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配送模板表' ROW_FORMAT = Dynamic;


CREATE TABLE `goods_spec`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值: 多个值以,分割',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品规格表' ROW_FORMAT = Dynamic;



INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (33, 0, '商品管理', 'goods', 'layui-icon layui-icon-template-1', '', 0, 0, 0, 1, 1697165908, 1697165908);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (34, 33, '分类管理', '/goods/cate', 'layui-icon layui-icon-template-1', '', 0, 1, 0, 1, 1697165936, 1697165936);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (35, 33, '品牌管理', '/goods/brand', 'layui-icon layui-icon-template-1', '', 0, 1, 0, 1, 1697187117, 1697187117);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (36, 39, '属性管理', '/goods/attr', 'layui-icon layui-icon-template-1', '', 0, 1, 0, 1, 1697701999, 1697253840);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (37, 39, '规格管理', '/goods/spec', 'layui-icon layui-icon-template-1', '', 0, 1, 0, 1, 1697702007, 1697263016);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (38, 39, '模型管理', '/goods/models', 'layui-icon layui-icon-template-1', '', 10, 1, 0, 1, 1697701973, 1697270056);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (39, 33, '产品模型', 'model', 'layui-icon layui-icon-template-1', '', 10, 0, 0, 1, 1697701951, 1697701951);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (40, 33, '产品管理', '/goods/product', 'layui-icon layui-icon-template-1', '', 0, 1, 0, 1, 1697703531, 1697703338);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (41, 9, '省份城市', '/sys/om/region', 'layui-icon layui-icon-template-1', '省份城市数据管理', 0, 1, 0, 1, 1700286539, 1700286539);
INSERT INTO `menu`(`id`, `pid`, `title`, `name`, `icon`, `remark`, `sort`, `type`, `openType`, `status`, `update_time`, `create_time`) VALUES (42, 33, '配送模板管理', '/goods/shipping', 'layui-icon layui-icon-template-1', '', 9, 1, 0, 1, 1700450401, 1700450365);




INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (125, 0, '产品管理', 'goods', '', 1, 1700536761, 1700536761);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (126, 125, '产品分类', 'goods_cate', '', 1, 1700538782, 1700538782);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (127, 125, '产品品牌', 'goods_brand', '', 1, 1700538799, 1700538799);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (128, 125, '发货模板', 'goods_shipping', '', 1, 1700538816, 1700538816);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (129, 125, '产品属性', 'goods_attr', '', 1, 1700538851, 1700538851);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (130, 125, '产品规格', 'goods_spec', '', 1, 1700538866, 1700538866);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (131, 125, '产品模型', 'goods_models', '', 1, 1700538892, 1700538892);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (132, 125, '产品管理', 'goods_product', '', 1, 1700539028, 1700539028);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (133, 126, '查看', '/goods/cate', '', 1, 1700545029, 1700545029);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (134, 126, '新增', '/goods/cate/add', '', 1, 1700545041, 1700545041);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (135, 126, '编辑', '/goods/cate/edit', '', 1, 1700545052, 1700545052);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (136, 127, '查看', '/goods/brand', '', 1, 1700545073, 1700545073);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (137, 127, '新增', '/goods/brand/add', '', 1, 1700545084, 1700545084);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (138, 127, '编辑', '/goods/brand/edit', '', 1, 1700545093, 1700545093);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (139, 128, '查看', '/goods/shipping', '', 1, 1700545110, 1700545110);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (140, 128, '新增', '/goods/shipping/add', '', 1, 1700545123, 1700545123);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (141, 128, '编辑', '/goods/shipping/edit', '', 1, 1700545131, 1700545131);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (142, 131, '查看', '/goods/models', '', 1, 1700545156, 1700545156);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (143, 131, '新增', '/goods/models/add', '', 1, 1700545162, 1700545162);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (144, 131, '编辑', '/goods/models/edit', '', 1, 1700545170, 1700545170);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (145, 131, '详情', '/goods/models/getInfo', '', 1, 1700545191, 1700545191);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (146, 129, '查看', '/goods/attr', '', 1, 1700545210, 1700545210);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (147, 129, '新增', '/goods/attr/add', '', 1, 1700545216, 1700545216);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (148, 129, '编辑', '/goods/attr/edit', '', 1, 1700545227, 1700545227);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (149, 130, '查看', '/goods/spec', '', 1, 1700545447, 1700545243);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (150, 130, '新增', '/goods/spec/add', '', 1, 1700545456, 1700545253);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (151, 130, '新增', '/goods/spec/edit', '', 1, 1700545463, 1700545294);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (152, 132, '查看', '/goods/product', '', 1, 1700545322, 1700545322);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (153, 132, '预览', '/goods/product/preview', '', 1, 1700545329, 1700545329);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (154, 132, '新增', '/goods/product/add', '', 1, 1700545339, 1700545339);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (155, 132, '编辑', '/goods/product/edit', '', 1, 1700545346, 1700545346);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (156, 132, '出库', '/goods/product/audit', '', 1, 1700545361, 1700545361);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (157, 132, '库存管理', '/goods/product/inventory', '', 1, 1700545392, 1700545392);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (158, 132, '运营管理', '/goods/product/marketing', '', 1, 1700545404, 1700545404);
INSERT INTO `auth_rule`(`id`, `pid`, `title`, `name`, `remark`, `status`, `update_time`, `create_time`) VALUES (159, 132, '上下架', '/goods/product/saleStatus', '', 1, 1700545432, 1700545432);
