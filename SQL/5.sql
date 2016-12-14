--删除所有记录  无条件是删除所有
DELETE from student;
delete from student where id in(3,6,9,10);
truncate table student;//清空数据表  只留表结构
delete from student where 1=1;  //删除时会触发删除触发器
--修改数据
update stu set sage=22;
UPDATE student set age=age+1,tid=2 where id in (2,4);
update student set name='ssss' where id=3;

--常用函数
SELECT LENGTH('china中国'),char_length('china中国');
SELECT * from student where name like '___';
SELECT * from student where CHAR_LENGTH(name)=3;
create table member(
	id int UNSIGNED not null AUTO_INCREMENT,
	account varchar(30) not null unique,
	pass varchar(32),
	regtime datetime,
	PRIMARY key(id)
)engine=INNODB default charset=utf8;
insert into member values (null,'aaa','1111','2015-12-25');
insert into member values (null,'aa1a','1111','2015-12-11');
insert into member values (null,'aa3a','1111','2016-5-20');
insert into member values (null,'aa12a','1111','2016-5-18');

SELECT count(*) from member where regtime BETWEEN date_add(now(),interval -7 day) and now();//七天内的注册用户

SELECT datediff('2012-02-28','2016-05-20') ;

SELECT account,DATE_FORMAT(regtime,'%Y年%m月%d日') from member;

SELECT UNIX_TIMESTAMP();//1463735299//获取当前时间戳
SELECT FROM_UNIXTIME(1463735299);将时间戳转换成日期格式
SELECT 'hello'+'ok';
--字符串合并
SELECT concat('hello','php','123');
SELECT concat('年龄：',sage,'岁') from stu;
SELECT concat_ws('-','java','php','mysql');
SELECT REPEAT('*',2);
SELECT space(2);
SELECT LENGTH(space(100));