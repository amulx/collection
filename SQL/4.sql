--分组查询
INSERT into stu (sname,sgender,sage,sscore,saddress) values('xin','女',23,99,'ganzhou')
select IFNULL(sgender,'保密'),COUNT(*) from stu group by sgender;

CREATE table student(
	id int UNSIGNED AUTO_INCREMENT,
	name varchar(30),
	gender enum('男','女') default '男',
	dept varchar(50),
	score tinyint UNSIGNED default 0,
	PRIMARY key(id)
)engine=INNODB default charset=utf8;

insert into student values(null,'张三','男','计算机科学',80);
insert into student values(null,'张三丰','男','英语',30);
insert into student values(null,'李四','男','网络',20);
insert into student values(null,'王五','女','英语',80);
insert into student values(null,'赵六','女','英语',30);
insert into student values(null,'张三尔','男','计算机科学',90);
	
SELECT * from student;

select dept,count(*),avg(score),max(score) from student group by dept;
SELECT gender,count(*) from student group by gender;
SELECT dept ,COUNT(*),avg(score),max(score) from student group by dept having count(*)>5 order by COUNT(*) desc;
--SELECT * from 表名 where 条件 group by 分组 having 分组条件 order by 排序 limit 限制行数;
--查询时消除重复值
select distinct dept from student;
SELECT name,gender,score from student where gender='男' 
union
SELECT name,gender,score from student where gender='女' ;

--多表查询
SELECT s.id,s.name,s.age,t.name from student s,teacher t where s.tid = t.id;

--左连接查询
SELECT s.id,s.name,s.age,t.name from student s join teacher t on s.tid = t.id;
SELECT s.id,s.name,s.age,t.name from student s left join teacher t on s.tid = t.id;

--笛卡尔积
select * from student ,teacher;
