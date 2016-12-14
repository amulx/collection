/*
什么是外键?
	两个表或多个表   外部关联关系
数据的有效性，两个表之间有关联关系

什么事主键？pk
功能 限制本列字段不能为空，不能重复
一个表只能有一个主键
唯一约束  键  索引

外键和主键有什么区别
*/
--  索引  本质相当于书籍的目录，作用加快数据检索速度  排序 缺点：降低了插入速度和修改速度  占用磁盘空间
alter table student add age int UNSIGNED;
create index agei on student(age desc);//desc降序 asc升序
alter table student drop index agei;
select * from student where age > 18;

--插入语句  insert
DROP table student;
DROP table teacher;
CREATE table stu(
	sid int UNSIGNED not null AUTO_INCREMENT,
	sname VARCHAR(10),
	sgender enum('男','女'),
	sage tinyint UNSIGNED default 18,
	sscore tinyint UNSIGNED,
	saddress varchar(200),
	PRIMARY key(sid)
)engine=INNODB default charset=utf8;
insert into stu values(null,'lisi','男',18,90,'henan');
insert into stu(sname,sgender,sage,sscore,saddress) SELECT sname,sgender,sage,sscore,saddress from stu;
replace stu values(8012,'lili','',28,50,'wenhualu');//先删除后插入