# ScanBook-Project
Contains files for ScanBook webpage

ScanBook webpage provides user a way to search any book by it's 13 digit ISBN. 
The webpage makes use of 'Google Book API' to search for specified ISBN.
scanbook.html takes user input and saerches for specified ISBN.
The book_info.php parses the JSON data returned by API to extract relevant information & display it on webpage.
User can ADD/Update the books in their personal Book List.
Currently BOOK LIST is saved in MYSQL database named 'scanbook' in table 'book_info'

'book_info' structure:

CREATE TABLE book_info
(
isbn varchar(13) NOT NULL PRIMARY KEY,
title varchar(100) NOT NULL,
author varchar(100) NOT NULL,
page_count varchar(15),
read_status varchar(11),
notes varchar(150),
img_url varchar(150),
);

Future scope: Add loging page for different users, session management, creating User table and associate it wiht book_info table.
