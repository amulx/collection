--修改数据库字符编码
alter	database `db` character set utf8;
--使用表
use db;
--删除表
DROP table stu;
--建立表
CREATE table stu(
	id int UNSIGNED auto_increment,
	name varchar(30),
	gender enum('男','女'),
	address varchar(50),
	PRIMARY key (id)
)engine=MYISAM default charset=utf8;
/*
CREATE TABLE `stu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `gender` enum('男','女') DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
*/
--查看建表脚本
show create table stu;
--插入数据
INSERT into stu values (null,'李四','男','广州');
--查看表数据
select * from stu;
--快速建立表直接插入数据  但此表没有相关的约束
CREATE table `aa` SELECT id ,name from stu;
--显示表结构
desc aa;
--修改表名
rename table stu to amu;
alter table aa RENAME ab;
alter table aa rename to ccc;
--修改列名和类型
alter table stu change name sname varchar(50);//注意大小只能由大改成小的 、类型兼容性
--增加主键约束(主键、外键、唯一) pk PRIMARY key 一个表只能一个主键
alter table stu add constraint pk PRIMARY key(tname);
alter table stu add constraint primary key(tname);
--移除主键约束
alter table stu drop primary key;
--复合主键
create table amu(
	tname varchar(30),
	tage tinyint,
	PRIMARY key(tname,tage)  -- 复合主键
)engine=MYISAM defualt charset= utf8;
--为表增加一列 删除列
desc teacher;
alter table teacher add tid int UNSIGNED AUTO_INCREMENT PRIMARY key;
alter table teacher drop column tid;
alter table teacher add tid int unsigned AUTO_INCREMENT PRIMARY key FIRST;
alter table teacher add taddress varchar(30) not null after tname;
desc select * from user where id= 5\G