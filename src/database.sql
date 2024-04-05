CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `business_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商户ID',
  `cate_cat` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所属分类,拼接cate_id',
  `brand_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `virtual` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0实物产品, 1虚拟产品',
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `valuation` decimal(10, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '参考价',
  `marque` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '型号',
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '条码',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '简要描述',
  `thumb` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图URL',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT '50' COMMENT '排序权重',
  `unit` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '计量单位，件、个',
  `shipping` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否需要配送',
  `shipping_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '物流模板ID',
  `buy_limit` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '购买限制: 0不限制, 1限制',
  `buy_limit_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '限购类型: 0不限制, 1总数, 2按天, 3按周, 4按月',
  `buy_limit_round` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '自定义限购天数, 0则不限制',
  `buy_limit_max` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最大限购, 0不限制',
  `buy_limit_min` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '最少起购',
  `stock_reduce` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否扣减库存',
  `stock_visible` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '库存显示 0不显示1显示',
  `sales_visible` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量显示 0不显示1显示',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否热销商品',
  `is_recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_new` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_bill` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否开具增值税发票 1是0否',
  `view_num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品查看数',
  `star_num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞数',
  `collect_num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数',
  `comment_num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评价数',
  `transmit_num` int(11) NOT NULL DEFAULT '0' COMMENT '分享数',
  `sale_start_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上架时间',
  `sale_end_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下架时间',
  `sale_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '销售状态: 0下架, 1上架, 2预售, 3违规(禁售)',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0审核中, 1通过, 2未通过',
  `audit_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '审核时间',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `virtual`(`virtual`) USING BTREE,
  KEY `cate`(`cate_cat`) USING BTREE,
  KEY `brand_id`(`brand_id`) USING BTREE,
  KEY `view_num`(`view_num`) USING BTREE,
  KEY `star_num`(`star_num`) USING BTREE,
  KEY `collect_num`(`collect_num`) USING BTREE,
  KEY `comment_num`(`comment_num`) USING BTREE,
  KEY `transmit_num`(`transmit_num`) USING BTREE,
  KEY `sale_start_time`(`sale_start_time`) USING BTREE,
  KEY `sale_end_time`(`sale_end_time`) USING BTREE,
  KEY `sort`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52899 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品主表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_attr` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '属性类型：0:input, 1:redio, 2:checkbox',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '属性名称',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值: 多个值以,分割',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品属性表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_brand` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cate_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类ID',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名称',
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌联系电话',
  `web` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌网站',
  `logo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌logo的URL',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌描述',
  `sort` tinyint(1) UNSIGNED NOT NULL DEFAULT '50' COMMENT '权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品品牌表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_cate` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级分类ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类图片',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `sort` tinyint(1) UNSIGNED NOT NULL DEFAULT '50' COMMENT '权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_detail` (
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

CREATE TABLE IF NOT EXISTS `goods_models` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `attr` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型关联属性ID以,分割',
  `spec` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型关联规格ID以,分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品模型' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_product` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) UNSIGNED NOT NULL COMMENT '商品id',
  `sku` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规格sku',
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'sku对应图片URL',
  `price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '价格',
  `cost_price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `market_price` decimal(8, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `stock` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '剩余库存',
  `sales` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品编码',
  `weight` decimal(8, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '重量(kg)',
  `volume` decimal(8, 2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '体积(立方米)',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `item`(`goods_id`, `sku`) USING BTREE,
  KEY `goods_id`(`goods_id`) USING BTREE,
  KEY `sku`(`sku`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品sku表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_shipping_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板名称',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0按件数 1按重量 2按体积',
  `first` decimal(12, 2) NOT NULL DEFAULT '0.00' COMMENT '首件',
  `first_price` decimal(12, 2) NOT NULL DEFAULT '0.00' COMMENT '首件运费',
  `continue` decimal(12, 2) NOT NULL DEFAULT '0.00' COMMENT '续件',
  `continue_price` decimal(12, 2) NOT NULL DEFAULT '0.00' COMMENT '续件运费',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' 0 COMMENT '排序',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1:正常,0:禁用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配送模板表' ROW_FORMAT = Dynamic;

CREATE TABLE IF NOT EXISTS `goods_spec` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值: 多个值以,分割',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态: 0禁用1启用',
  `update_time` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品规格表' ROW_FORMAT = Dynamic;