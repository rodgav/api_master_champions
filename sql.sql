create table categorys
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

create table products
(
    id          int primary key auto_increment      not null,
    idCategory  int                                 not null,
    image_url   text                                not null,
    date_create timestamp default current_timestamp not null
);

create table user
(
    id       int primary key auto_increment not null,
    email    varchar(100)                   not null,
    password varchar(100)                   not null
);

alter table products
    add constraint idCategory_categorys foreign key (idCategory) references categorys (id) on update restrict on delete restrict;

delimiter $
create procedure login(_email varchar(100), _password varchar(100))
begin
select id
from user
where email like _email
      and password like _password;
end;
$ delimiter ;

delimiter $
create procedure createCategory(_name varchar(50))
begin
insert into categorys (name) value (_name);
end;
$ delimiter ;

delimiter $
create procedure getCategorys()
begin
select * from categorys;
end;
$ delimiter ;

delimiter $
create procedure createProduct(_idCategory int, _imageUrl text)
begin
insert into products (idCategory, image_url) value (_idCategory, _imageUrl);
end;
$ delimiter ;

delimiter $
create procedure getProducts10()
begin
select id, image_url from products order by date_create desc limit 0,10;
end;
$ delimiter ;

delimiter $
create procedure getProducts(_idCategory int)
begin
select id, image_url from products where idCategory like _idCategory order by date_create;
end;
$ delimiter ;


insert into user (id, email, password) value (1, 'prueba@gmail.com', '12345678');

call login('prueba@gmail.com', '12345678');
insert into categorys (id, name) value (1, 'prueba categoria');
insert into products (id, idCategory, image_url) value (1, 1, 'image url prueba');

select *
from products;
call getCategorys();
call getProducts10();
