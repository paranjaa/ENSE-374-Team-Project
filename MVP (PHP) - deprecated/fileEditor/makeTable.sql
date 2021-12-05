CREATE TABLE ShareNoteNotes(
    note_id INT NOT NULL AUTO_INCREMENT, 
    folder_id INT NOT NULL, 
    noteName VARCHAR(255) NOT NULL,
    note TEXT NOT NULL,
    shareEnabled INT NOT NULL,
    PRIMARY KEY (note_id),
    FOREIGN KEY (folder_id) REFERENCES ShareNoteFolders(folder_id)
);