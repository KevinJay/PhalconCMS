### V1.0.2
##### 数据库脚本更新
* ALTER TABLE menu ADD COLUMN `belongs_module` VARCHAR(20) NOT NULL DEFAULT 'frontend' COMMENT '菜单所属模块' AFTER `status`
* ALTER TABLE menu ADD COLUMN menu_icon VARCHAR(50) NOT NULL DEFAULT '' COMMENT '菜单图标' AFTER `menu_url`