# 查看某个表的索引
SHOW INDEX FROM `table_name`;
SHOW KEYS FROM `table_name`;

# 创建索引
CREATE INDEX `index_name` ON `table_name` (column_list);
CREATE UNIQUE INDEX `index_name` ON `table_name` (column_list);
ALTER TABLE `table_name` ADD INDEX `index_name` (column_list);
ALTER TABLE `table_name` ADD UNIQUE (column_list);
ALTER TABLE `table_name` ADD PRIMARY KEY (column_list);

# 删除索引
DROP INDEX `index_name` ON `talbe_name`;
ALTER TABLE `table_name` DROP INDEX `index_name`;
ALTER TABLE `table_name` DROP PRIMARY KEY;

# 查看数据库大小
SELECT CONCAT(ROUND(SUM(DATA_LENGTH)/(1024*1024), 2), 'MB') AS data
FROM information_schema.TABLES
WHERE TABLE_SCHEMA='test_index';

# 查看表大小，不含索引
SELECT CONCAT(ROUND(SUM(DATA_LENGTH)/(1024*1024), 2), 'MB') AS data
FROM information_schema.TABLES
WHERE TABLE_SCHEMA='test_index' AND TABLE_NAME='t_user';

# 查看索引
SELECT CONCAT(ROUND(SUM(INDEX_LENGTH)/(1024*1024), 2), 'MB') AS 'Total Index Size'
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'test_index' and TABLE_NAME='t_user';

# 独立的列
SELECT id, name FROM child WHERE id + 1 = 5;
SELECT id, name FROM child WHERE id = 4;


# 前缀索引和索引的选择性
# 不一定准确
SELECT
    COUNT(DISTINCT LEFT(current_class_name, 1))/COUNT(*) AS sel1,
    COUNT(DISTINCT LEFT(current_class_name, 2))/COUNT(*) AS sel2,
    COUNT(DISTINCT LEFT(current_class_name, 3))/COUNT(*) AS sel3,
    COUNT(DISTINCT LEFT(current_class_name, 4))/COUNT(*) AS sel4,
    COUNT(DISTINCT LEFT(current_class_name, 5))/COUNT(*) AS sel5,
    COUNT(DISTINCT LEFT(current_class_name, 6))/COUNT(*) AS sel6
FROM child;

ALTER TABLE child ADD KEY (current_class_name(3));

SELECT id, name FROM child WHERE current_class_name = 'Sprint4';


# 选择合适的索引列顺序
SELECT * FROM child WHERE current_class_id = 13 AND sex = 'male';
SELECT SUM(current_class_id = 13), SUM(sex = 'male') FROM child;
SELECT SUM(sex = 'male') FROM child WHERE current_class_id = 13;

SELECT
    COUNT(DISTINCT current_class_id)/COUNT(*) AS class_id_selectivity,
    COUNT(DISTINCT sex)/COUNT(*) AS sex_selectivity,
    COUNT(*)
FROM child;

ALTER TABLE child ADD KEY (current_class_id, sex);


# 使用索引扫描来做排序
ALTER TABLE child ADD KEY (current_class_id, type, sex);
# 可用索引排序
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type DESC;
SELECT * FROM child WHERE current_class_id > 30 ORDER BY current_class_id, type;
# 不可用索引排序
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type DESC, sex ASC;
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type, name;
SELECT * FROM child WHERE current_class_id = 30 ORDER BY sex;
SELECT * FROM child WHERE current_class_id > 30 ORDER BY type, sex;
SELECT * FROM child WHERE current_class_id = 30 AND type IN('reservation','onboard') ORDER BY sex;


# 创建班级名称索引
CREATE INDEX `current_class_id` ON `child` (current_class_id);
DROP INDEX `current_class_id` ON `child`;
CREATE INDEX `created_at` ON `child` (created_at);
DROP INDEX `created_at` ON `child`;

# Example
## explain extended
## show warnings
CREATE INDEX child_index ON child (`kindergarten_id`, `is_deleted`, `created_at`);
DROP INDEX child_index ON child;
CREATE INDEX child_index ON child (`kindergarten_id`, `created_at`);
DROP INDEX child_index ON child;
CREATE INDEX child_index ON child (`created_at`);
DROP INDEX child_index ON child;
CREATE INDEX child_index ON child (`kindergarten_id`, `is_deleted`);
DROP INDEX child_index ON child;


CREATE INDEX sign_group ON camp_sign (`child_id`, `camp_id`, `week_number`);
DROP INDEX sign_group ON camp_sign;
CREATE INDEX sign_group ON camp_sign (`child_id`, `camp_id`, `week_number`, `kindergarten_id`, `is_deleted`);

CREATE INDEX end_date ON camp (end_date);
DROP INDEX end_date ON camp;
