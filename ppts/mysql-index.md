title: MySQL索引
speaker: Harry Sun
url: https://github.com/CraryPrimitiveMan/team-sharing
transition: slide
files: /css/ppt.css

[slide]

# MySQL索引
###### <small style="font-size:18px;margin-left:200px;">-- 基于 MySQL 5.5 版本</small>
----
<small>演讲者：[@Harry Sun](https://github.com/CraryPrimitiveMan)</small>
[slide]

## 课程目标
----
* 了解 MySQL 索引的类型及其使用
* 了解 MySQL 索引的数据结构
* 了解索引的不足和使用时的注意事项
* 学习使用 MySQL 索引进行数据库性能调优

[slide]

## 索引是什么？
----
* 索引是对数据库表中一列或多列的值进行排序的一种结构，使用索引可快速访问数据库表中的特定信息。 {:&.moveIn}

[slide]

## MySQL 索引的类型（存储的数据结构）
----
* B-Tree索引
* 哈希索引（Memory 引擎支持）
* 空间数据索引（MyISAM 引擎支持）
* 全文索引（MyISAM 引擎支持）
* 其他索引类型，第三方存储引擎使用不同类型的数据结构来存储索引

[slide]

## 使用 MySQL 索引
----
* 查看某个表的索引
 ```sql
SHOW INDEX FROM `table_name`;
SHOW KEYS FROM `table_name`;
```

* 创建索引
```sql
CREATE INDEX `index_name` ON `table_name` (column_list);
CREATE UNIQUE INDEX `index_name` ON `table_name` (column_list);
ALTER TABLE `table_name` ADD INDEX `index_name` (column_list);
ALTER TABLE `table_name` ADD UNIQUE (column_list);
ALTER TABLE `table_name` ADD PRIMARY KEY (column_list);
```

* 删除索引
```sql
DROP INDEX `index_name` ON `talbe_name`;
ALTER TABLE `table_name` DROP INDEX `index_name`;
ALTER TABLE `table_name` DROP PRIMARY KEY;
```

[slide]

## 索引的数据结构是什么？
----
* 目前大部分数据库系统及文件系统都采用B-Tree或其变种B+Tree作为索引结构。 {:&.moveIn}

[slide]

## B-Tree数据结构 [参考](https://en.wikipedia.org/wiki/B-tree)
----
![B-Tree](/images/mysql/B-tree.svg)

[slide]

## B+Tree数据结构 [参考](https://en.wikipedia.org/wiki/B%2B_tree)
----
![B+Tree](/images/mysql/Bplustree.png)

[slide]

## 索引的效果

```sql
SELECT * FROM `t_user` WHERE name='harry1';
SELECT * FROM `t_user` WHERE name='harry2000000';
SELECT * FROM `t_user` WHERE name='harry1' LIMIT 1;
SELECT * FROM `t_user` WHERE name='harry2000000' LIMIT 1;
# create index
CREATE INDEX `name` ON `t_user` (name);
```

[slide]

## B-tree 索引的情景
----
+ 全值匹配
+ 匹配最左前缀
+ 匹配列前缀
+ 匹配范围值
+ 精确匹配某一列并范围匹配另一列
+ 只访问索引的查询

[slide]

## B-tree 索引的限制
----
+ 不是按照索引的最左列开始查找，则无法使用索引
+ 不能跳过索引中的列
+ 如果查询中有某个列的范围查询，则其右边所有列都无法使用索引优化查找

[slide]

## 表和索引的大小 [参考](http://dev.mysql.com/doc/refman/5.5/en/information-schema.html)

```sql
SELECT CONCAT(ROUND(SUM(DATA_LENGTH)/(1024*1024), 2), 'MB') AS data
FROM information_schema.TABLES
WHERE TABLE_SCHEMA='test_index';

SELECT CONCAT(ROUND(SUM(DATA_LENGTH)/(1024*1024), 2), 'MB') AS data
FROM information_schema.TABLES
WHERE TABLE_SCHEMA='test_index' AND TABLE_NAME='t_user';

SELECT CONCAT(ROUND(SUM(INDEX_LENGTH)/(1024*1024), 2), 'MB') AS 'Total Index Size'
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'test_index' and TABLE_NAME='t_user';
```

[slide]

## EXPLAIN 的使用
----
```sql
EXPLAIN EXTENDED SELECT * FROM `t_user` WHERE name='harry1';
```

| id | select_type | table  | type | possible_keys | key  | key_len | ref   | rows | filtered | Extra       |
|----|-------------|--------|------|---------------|------|---------|-------|------|----------|-------------|
|  1 | SIMPLE      | t_user | ref  | name          | name | 48      | const |    1 |   100.00 | Using where |

[slide]

## EXPLAIN 返回参数的解释 [参考](http://dev.mysql.com/doc/refman/5.5/en/explain-output.html)
----
* id ： 标识 SELECT 所属的行
* select_type : 显示对应行是简单还是复杂 SELECT
* table : 显示对应行正在访问哪个表，是表名或者表的别名
* type : 访问类型 从最好到最差的连接类型为 NULL, const/system, eq_ref, ref, range, index, ALL
* possible_keys : 可以使用哪些索引
* key : MySQL 决定使用哪些索引
* key_len : MySQL 在索引里使用的字节数
* ref : key 列记录的索引中查找值所用的列或者常量
* rows : 估计为了找到所需的行而要读取的行数
* filtered : 使用 EXPLAIN EXTENDED 时出现，显示的是针对表里符合某个条件的记录数的百分比的一个悲观估算，只有在 type 为 range, index, ALL 时是有效的
* Extra : 额外的信息

[slide]

## 高性能索引
----
+ 独立的列

    ```sql
    SELECT id, name FROM child WHERE id + 1 = 5;
    ```

+ 前缀索引和索引的选择性

    对于 BLOB、TEXT 或很长的 VARCHAR 类型的列，必须使用前缀索引
    ```sql
    ALTER TABLE child ADD KEY (current_class_name(3));
    ```
   MySQL 无法使用前缀索引做 ORDER BY 和 GROUP BY，也无法使用前缀索引做覆盖扫描。

+ 选择合适的索引列顺序

    ```sql
    SELECT * FROM child WHERE current_class_id = 13 AND sex = 'male';
    ```
    应该创建(current_class_id, sex)顺序的索引还是(sex, current_class_id)顺序的索引?

+ 聚簇索引

    ![InnoDB and MYISAM](/images/mysql/InnoDB_MYISAM_index.png)

    InnoDB 表中按主键顺序插入


    | 表名 | 行数 | 时间(秒)  | 索引大小(MB) |
    |---------------|---------|------|-------|
    | userinfo      | 1000000 | 137  | 345   |
    | userinfo_uuid | 1000000 | 180  | 544   |
    | userinfo      | 3000000 | 1233 | 1036  |
    | userinfo_uuid | 3000000 | 4525 | 1707  |


+ 覆盖索引

    直接使用索引来获取列的数据，就不需要读取数据行。
    ```sql
    SELECT id FROM child WHERE id < 5;
    ```
    Extra: Using index

+ 使用索引扫描来做排序

    type: index

    假设存在(current_class_id, type, sex)索引，下面的例子形成了索引的最左前缀

    ```sql
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type DESC;
SELECT * FROM child WHERE current_class_id > 30 ORDER BY current_class_id, type;
    ```

    下面是一些不能使用索引做排序查询的例子
    ```sql
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type DESC, sex ASC;
SELECT * FROM child WHERE current_class_id = 30 ORDER BY type, name;
SELECT * FROM child WHERE current_class_id = 30 ORDER BY sex;
SELECT * FROM child WHERE current_class_id > 30 ORDER BY type, sex;
SELECT * FROM child WHERE current_class_id = 30 AND type IN('reservation','onboard') ORDER BY sex;
    ```

+ 冗余和重复索引

    MySQL 允许在相同列上创建多个索引，但这会影响查询性能，而且表中的索引越多，INSERT、UPDATE、DELETE操作速度越慢。

+ 未使用的索引

    删除未使用的索引

[slide]

## hopeland 中索引优化的例子
----

```sql
SELECT `id` FROM `camp_sign`
WHERE
    (`camp_sign`.`kindergarten_id`=1)
    AND (`camp_sign`.`is_deleted`=0)
GROUP BY
    `child_id`,
    `camp_id`,
    `week_number`
HAVING
    sum(is_sign) = 0;
```

[slide]

## hopeland 中索引优化的例子
----

```sql
SELECT `camp_sign`.`child_id`,
    `camp_sign`.`camp_id`,
    group_concat(camp_sign.week_number order by camp_sign.week_number ASC) absent_week
FROM `camp_sign`
LEFT JOIN `child` ON (
    `camp_sign`.`child_id` = `child`.`id`)
    AND ((`child`.`is_deleted`=0)
    AND (`child`.`kindergarten_id`=1))
LEFT JOIN `camp_application` ON (
    `camp_sign`.`child_id` = `camp_application`.`child_id`
    AND `camp_sign`.`camp_id` = `camp_application`.`camp_id`)
    AND ((`camp_application`.`is_deleted`=0)
    AND (`camp_application`.`kindergarten_id`=1))
LEFT JOIN `camp` ON (
    `camp_sign`.`camp_id` = `camp`.`id`)
    AND ((`camp`.`is_deleted`=0)
    AND (`camp`.`kindergarten_id`=1))
WHERE
(((((`camp_sign`.`kindergarten_id`=1)
AND (`camp_sign`.`is_deleted`=0))
AND (`camp_application`.`is_refunded`=0))
AND (`camp`.`end_date` < 1450886400))
AND (`camp_sign`.`id` IN (
    SELECT `id` FROM `camp_sign`
    WHERE
        (`camp_sign`.`kindergarten_id`=1)
        AND (`camp_sign`.`is_deleted`=0)
    GROUP BY
        `child_id`,
        `camp_id`,
        `week_number`
    HAVING
        sum(is_sign)=0)))
AND ((`child`.`name` LIKE '%a%') OR (`child`.`child_no` LIKE '%a%'))
GROUP BY `child_id`, `camp_id`
```

[slide]

## 对 hopeland 的建议

* 开启慢查询，优化较慢的 SQL 语句
* 关联表加主键
* 添加索引相关的机制，可以是一个脚本，根据配置文件扫描表中的索引去创建/修改/删除。

[slide]

## 推荐书籍

* 《SQL必知必会》或者《MySQL必知必会》-- SQL 的基础知识
* 《高性能MySQL》 -- 内容涵盖mysql 架构和历史，基准测试和性能剖析，数据库软硬件性能优化，复制、备份和恢复，高可用与高可扩展性，以及云端的 mysql 和 mysql 相关工具等方面的内容。
* 《MySQL技术内幕:InnoDB存储引擎(第2版)》 -- 从源代码的角度深度解析了InnoDB的体系结构、实现原理、工作机制

[slide]

## SQL 练手作业
## Question&Answer
