# Installation Instructions:



(Presumably need node installed)
Get the ZIP off of the github
Extract it, and open InstallationVersion folder - run terminal from within that folder
In the terminal put in the following commands:
npm init
npm i express ejs mongoose
npm i passport passport-local passport-local-mongoose express-session dotenv
That should get everything installed

- ### On Windows continue following these instructions:
In a seperate terminal, enter the following command:
mongod

Then in another terminal, enter the following command:
nodemon


- ### On Mac continue following these instructions:
In a seperate terminal, enter the following command (this is to run MongoDB (i.e. the mongod process) as a macOS service):
brew services start mongodb-community@5.0

Then in another terminal, enter the following command:
nodemon

