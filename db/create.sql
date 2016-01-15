drop table if exists posts;

create table posts (
    id integer not null primary key auto_increment,
    content varchar(2000) not null,
    created DATETIME not null,
    author varchar(100) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;