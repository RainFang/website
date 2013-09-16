/*
Name: Yu-Chieh Fang
Email address: YCFang87@gmail.com
EID: yf365
CSID: yf365
z space link: http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h2/login.php
*/

USE cs105_s13_yf365;

DROP TABLE IF EXISTS userinfo CASCADE;

-- "CASCADE" means drop any constraints that other
-- tables may have on this table when it is deleted 
CREATE TABLE userinfo (
	username	VARCHAR(255),
	pw			VARCHAR(255),
	tv			VARCHAR(255),
	rel			VARCHAR(255),
	job			VARCHAR(255),
	PRIMARY KEY (username));

DROP TABLE IF EXISTS messages CASCADE;
CREATE TABLE messages (
	username 	VARCHAR(255) REFERENCES userinfo,
	message		VARCHAR(255),
	hdate   TIMESTAMP);

DROP TABLE IF EXISTS friends CASCADE;
CREATE TABLE friends(
	username	VARCHAR(255) REFERENCES userinfo,
	friend		VARCHAR(255));

DROP TABLE IF EXISTS photo_users CASCADE;
create table photo_users (
	username VARCHAR(255) REFERENCES userinfo,
	photo_id  VARCHAR(255),
	photo_url VARCHAR(10000));

DROP TABLE IF EXISTS photo_info CASCADE;
create table photo_info (
	photo_id  VARCHAR(255) REFERENCES photo_users,
	username VARCHAR(255),
	x_coor FLOAT(10,4),
	y_coor FLOAT(10,4));
	
INSERT INTO userinfo VALUES
('Rain', 'a', 'MLP', 'robot', 'Killing Humans!');
INSERT INTO userinfo VALUES
('Boris', 'b', 'bad', 'married', 'Circumcisions');
INSERT INTO userinfo VALUES
('Jerry', 'c', 'bad', 'single', 'Looping');
INSERT INTO userinfo VALUES
('Bendy', 'd', 'WD', 'robot', 'Hugging Students!');
INSERT INTO userinfo VALUES
('Jeff', 'e', 'GOT', 'married', 'Killing Interviews!');
INSERT INTO userinfo VALUES
('Jason', 'f', 'bad', 'single', 'Killing Research!');
INSERT INTO userinfo VALUES
('Scott', 'g', 'bad', 'single', 'Killing Heroes!');

INSERT INTO friends VALUES
('Rain', 'Boris');
INSERT INTO friends VALUES
('Rain', 'Jerry');
INSERT INTO friends VALUES
('Rain', 'Bendy');
INSERT INTO friends VALUES
('Rain', 'Jeff');
INSERT INTO friends VALUES
('Rain', 'Jason');
INSERT INTO friends VALUES
('Rain', 'Scott');
INSERT INTO friends VALUES
('Boris', 'Jerry');
INSERT INTO friends VALUES
('Jeff', 'Jason');
INSERT INTO friends VALUES
('Jason', 'Scott');

INSERT INTO photo_users VALUES
('Bendy', 'Bendy', 'http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/Bendy.jpg');
INSERT INTO photo_users VALUES
('Bendy', 'findBendy', 'http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/findBendy.jpg');
INSERT INTO photo_users VALUES
('Rain', 'Rain', 'http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/Rain.jpg');
INSERT INTO photo_users VALUES
('Rain', 'findRain', 'http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/findRain.jpg');