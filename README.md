# ScanBook-Project
Contains files for ScanBook webpage

ScanBook webpage provides user a way to search any book by it's 13 digit ISBN. 
The webpage makes use of 'Google Book API' to search for specified ISBN.
The book_info.php parses the JSON data returned by API to extract relevant information & display it on webpage.
User can ADD/Update the books in their personal Book List.
Currently BOOK LIST is saved in MYSQL database named 'scanbook' in table 'book_info'
