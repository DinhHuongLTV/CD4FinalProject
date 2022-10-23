create database if not exists quanlysinhvien;

use quanlysinhvien;

create table if not exists students (
	id int(11) auto_increment,
    name varchar(255),
    age int(11),
    avatar varchar(255),
    description varchar(255),
    created_at timestamp default current_timestamp,
    primary key(id)
);

select * from students;

