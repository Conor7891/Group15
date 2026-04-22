/*
Database Setup: Add a table called colors to your database. Each row in the table represents one color. The table must have three columns:
id — unique, not null, auto-assigned
name — unique, not null
hex_value — unique, not null

*/

create table colors (
    id INT UNIQUE NOT NULL AUTO_INCREMENT,
    name VARCHAR(10) NOT NULL UNIQUE,
    hex_value char(7) NOT NULL UNIQUE,
    PRIMARY KEY (id)
) AUTO_INCREMENT = 1;

insert into colors (name, hex_value) values
('Red', '#FF0000'),
('Orange', '#FFA500'),
('Yellow', '#FFFF00'),
('Green', '#008000'),
('Blue', '#0000FF'),
('Purple', '#800080'),
('Grey', '#808080'),
('Brown', '#964B00'),
('Black', '#000000'),
('Teal', '#008080');