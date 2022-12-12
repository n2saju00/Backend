DROP TABLE PRODUCT;
DROP TABLE CATEGORY;
DROP TABLE ORDERS;
DROP TABLE ORDER_ITEMS;
DROP TABLE USER;
DROP TABLE ADMIN;


CREATE TABLE ORDERS (
	orderId varchar PRIMARY KEY,
	customerId varchar,
	orderStatus varchar,
	orderDate datetime,
	shippedDate datetime
);

CREATE TABLE PRODUCT (
	productId integer PRIMARY KEY,
	categoryId integer,
	productName varchar,
	price float,
	description varchar,
	timeStamp datetime,
	active boolean
);

CREATE TABLE CATEGORY (
	categoryId integer PRIMARY KEY,
	categoryName varchar
);

CREATE TABLE ORDER_ITEMS (
	id INTEGER PRIMARY KEY,
	orderId varchar,
	productId integer
);

CREATE TABLE USER (
	customerId integer PRIMARY KEY,
	username varchar,
	password varchar,
	fname varchar,
	lname varchar,
	phone varchar,
	email varchar,
	address varchar,
	city varchar,
	stateProvince varchar,
	postalcode varchar
);

CREATE TABLE ADMIN (
	username varchar PRIMARY KEY,
	level integer
);

INSERT INTO CATEGORY VALUES('1','kategoria1');
INSERT INTO CATEGORY VALUES('2','kategoria2');
INSERT INTO CATEGORY VALUES('3','kategoria3');
INSERT INTO CATEGORY VALUES('4','kategoria4');

INSERT INTO PRODUCT VALUES('1','2','tuote1','20.50','tuotteen 1 kuvaus', datetime(), true);
INSERT INTO PRODUCT VALUES('2','3','tuote2','15.25','tuotteen 2 kuvaus', datetime(), true);
INSERT INTO PRODUCT VALUES('42','3','tuote42','15.25','tuotteen 42 kuvaus', datetime(), true);
INSERT INTO PRODUCT VALUES('3','2','tuote3','18.00','tuotteen 3 kuvaus', datetime(), false);
INSERT INTO PRODUCT VALUES('4','1','tuote4','11.10','tuotteen 4 kuvaus', datetime(), true);


INSERT INTO USER(customerId,username,password,fname,lname,phone,email,address,city,stateProvince,postalcode) VALUES('1','admin','$2y$10$tqh9JXyfZAZd1YVxr6ieFO2kw.iy7Hw4CeaDJe.bk3UAvqoyv9Ooa','testiF','testiL','1234567890','testtest@testi.com','testiA','testiC','NULL','12345');
INSERT INTO ORDERS(orderId,customerId,orderStatus,orderDate,shippedDate) VALUES('U1O8007002','1','shipped','2022-12-05 18:22:33','2022-12-05 18:22:33');
INSERT INTO ADMIN(username,level) VALUES('admin','3');