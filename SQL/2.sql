use udb12349
SELECT concat(mname,"/",cname,"/",aname) url from udb_privilege;udb_admin;
--查看表引擎
SHOW engines;
use db;
/*建立学生表是，建立的唯一约束，建立主键约束*/
create table student(
	id int unsigned AUTO_INCREMENT,
	name varchar(30) not null unique,
	age TINYINT UNSIGNED,
	tid int UNSIGNED,
	PRIMARY key(id)
)engine=INNODB default charset=utf8;
desc student;
insert into student values(null,'张三',28);
insert into student values(null,'张三',22);
drop table student;
alter table student add constraint uk unique (name);
alter table `db`.`student` drop index `name`;
alter table `db`.`student` drop index uk;
create table teacher(
	id int UNSIGNED AUTO_INCREMENT PRIMARY key,
	name varchar(30) not null
)engine=inndb default charset=utf8;
--增加外键
alter table student add constraint fk foreign key (tid) REFERENCES teacher(id);
alter table student add constraint fk foreign key (tid) REFERENCES teacher(id) on DELETE set null;
alter table student add constraint fk foreign key (tid) REFERENCES teacher(id) on DELETE cascade;
alter table student add constraint fk foreign key (tid) REFERENCES teacher(id) on DELETE set null on update cascade;
--删除外键
alter table student drop foreign key fk;
ALTER table student DROP index fk;
INSERT into teacher values(null,'李老师'),(null,'赵老师');
insert into student values(null,'学生',18,3);
--删除
DELETE from teacher where id=3;
update student set tid=3 where id in(3,4);
SELECT * from teacher;
DROP table student;
DROP table teacher;
create	table teacher(
	id int UNSIGNED AUTO_INCREMENT PRIMARY key,
	name varchar(30) not null
)engine=innodb default charset=utf8;

CREATE table student(
	id int UNSIGNED AUTO_INCREMENT,
	name varchar(30) not null,
	age tinyint UNSIGNED,
	tid int UNSIGNED,
	constraint fk foreign key(tid) references teacher(id) on DELETE set null,
	PRIMARY key (id)
)engine=INNODB default charset=utf8;
INSERT into teacher values(null,'李老师'),(null,'赵老师');
insert into student values(null,'学生',18,2);
DELETE from teacher where id = 2;