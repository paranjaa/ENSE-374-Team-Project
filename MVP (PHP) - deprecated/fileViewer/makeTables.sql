CREATE TABLE Folders(
    folder_id INT NOT NULL AUTO_INCREMENT, 
    u_id INT NOT NULL, 
    folderName VARCHAR(30) NOT NULL,
    folderLocation VARCHAR(255) NOT NULL,
    PRIMARY KEY (folder_id),
    FOREIGN KEY (u_id) REFERENCES User(user_id)
);


CREATE TABLE Marks(
    student_ID VARCHAR(30) NOT NULL,
    course_Name VARCHAR(30) NOT NULL,
    marks DOUBLE NOT NULL,
    FOREIGN KEY (student_ID) REFERENCES Student_Info(student_ID),
    FOREIGN KEY (course_Name) REFERENCES Courses(course_Name)
);


mysql> describe Folders;
+----------------+--------------+------+-----+---------+----------------+
| Field          | Type         | Null | Key | Default | Extra          |
+----------------+--------------+------+-----+---------+----------------+
| folder_id      | int(11)      | NO   | PRI | NULL    | auto_increment |
| u_id           | int(11)      | NO   | MUL | NULL    |                |
| folderName     | varchar(30)  | NO   |     | NULL    |                |
| folderLocation | varchar(255) | NO   |     | NULL    |                |
+----------------+--------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

INSERT INTO Folders (folder_id, u_id, folderName, folderLocation)
VALUES (value1, value2, value3, value4);

SELECT * INTO ShareNoteUsers
FROM User;