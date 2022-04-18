CREATE DATABASE IF NOT EXISTS VSJM;


CREATE TABLE item  (
item_ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
item_Name varchar(75) NOT NULL,
item_unit varchar(50) NOT NULL,
item_Brand varchar(50) NOT NULL,
item_Category  varchar(50) NOT NULL,
item_Status   TINYINT NOT NULL DEFAULT '1'
);



CREATE TABLE supplier (
supplier_ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
supplier_Name varchar(75) NOT NULL,
supplier_ContactPerson varchar(75) NOT NULL,
supplier_ContactNum varchar(11) NOT NULL,
supplier_Address varchar(100) NOT NULL,
supplier_Status TINYINT NOT NULL DEFAULT '1'
); 


CREATE TABLE supplier_Transactions(
transaction_ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
supplier_ID int NOT NULL,
transaction_Date datetime NOT NULL,
transaction_Status TINYINT,
transaction_TotalPrice float(53) NOT NULL,
FOREIGN KEY(supplier_ID) REFERENCES supplier(supplier_ID) ON UPDATE CASCADE
);


CREATE TABLE supplier_item(
	supplier_ID int NOT NULL,
	item_ID int NOT NULL,
	supplierItem_CostPrice float(53) NOT NULL,
	supplierItem_Status  TINYINT NOT NULL DEFAULT '1',
PRIMARY KEY(supplier_ID, item_ID),
FOREIGN KEY (supplier_ID) REFERENCES supplier(supplier_ID) ON UPDATE CASCADE,
FOREIGN KEY (item_ID) REFERENCES item(item_ID) ON UPDATE CASCADE
);


CREATE TABLE transaction_items(
	transaction_ID int NOT NULL , 
	item_ID int NOT NULL,
	transactionItems_Quantity int NOT NULL,
	transactionItems_CostPrice float(53) NOT NULL,
transactionItems_TotalPrice float(53) NOT NULL,
PRIMARY KEY(transaction_ID,item_ID),
FOREIGN KEY (transaction_ID) REFERENCES supplier_Transactions(transaction_ID) ON UPDATE CASCADE,
FOREIGN KEY (item_ID) REFERENCES item(item_ID) ON UPDATE CASCADE
);


CREATE TABLE branch(
	branch_ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
branch_Name varchar(75) NOT NULL,
branch_Address varchar(100) NOT NULL
);


CREATE TABLE inventory (
	branch_ID int NOT NULL,
	item_ID int NOT NULL,
	item_Stock int NOT NULL,
	item_RetailPrice float(53) NOT NULL,
	Item_markup float(53) NOT NULL,
	in_pending TINYINT,
	inventoryItem_Status  TINYINT NOT NULL DEFAULT '1',
	PRIMARY KEY (branch_ID,item_ID),
	FOREIGN KEY(branch_ID) REFERENCES branch(branch_ID) ON UPDATE CASCADE,
	FOREIGN KEY(item_ID) REFERENCES item(item_ID) ON UPDATE CASCADE
);


CREATE TABLE orders(
order_ID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
order_Date datetime NOT NULL,
order_Total float(53) NOT NULL
);


CREATE TABLE order_items(
	item_ID int NOT NULL,
order_ID int NOT NULL PRIMARY KEY,
orderItems_Quantity int NOT NULL,
orderItems_TotalPrice float(53) NOT NULL,
FOREIGN KEY(item_ID) REFERENCES item(item_ID) ON UPDATE CASCADE,
FOREIGN KEY(order_ID) REFERENCES orders(order_ID) ON UPDATE CASCADE
);

CREATE TABLE cart (
	cart_ID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	itemID int(11) NOT NULL,
	itemName varchar(100) NOT NULL,
	itemPrice varchar(50) NOT NULL,
	quantity varchar(10) NOT NULL,
	itemTotalP varchar(100) NOT NULL
);

INSERT INTO branch (
	branch_Name, branch_Address
) VALUES (
	'VICAS', ' Block 6 Lot 1 Congressional Road, Raibow Village 5 Bagumbong Caloocan'
);


