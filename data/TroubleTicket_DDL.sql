-- Assumes database already created and in use
-- Written in SQLite so *potential conflicts* with MySQL

CREATE TABLE Tech(
    name VARCHAR(32),
    eid INT PRIMARY KEY
    );

CREATE TABLE Admin(
    name VARCHAR(32),
    eid INT PRIMARY KEY
    );

CREATE TABLE User(
    name VARCHAR(32),
    uid INT PRIMARY KEY, 
    type CHAR(3), 
    email VARCHAR(32)
    );

CREATE TABLE Ticket(
    tid INT PRIMARY KEY,
    title VARCHAR(32),
    dateCreated DATETIME,
    status CHAR(3),
    priority INT,
    description TEXT,
    uid INT,
    eid_t INT,
    FOREIGN KEY (uid) REFERENCES user(uid),
    FOREIGN KEY (eid_t) REFERENCES tech(eid)
    );

CREATE TABLE assigns(
    tid INT,
    dateCreated DATETIME,
    dateDue DATETIME,
    eid_a INT,
    eid_t INT,
    FOREIGN KEY (eid_a) REFERENCES Admin(eid),
    FOREIGN KEY (eid_t) REFERENCES Tech(eid)
    );
