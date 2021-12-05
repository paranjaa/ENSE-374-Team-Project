# Installation Instructions:



- The following instructions rely on the user having Node.js + npm and MongoDB installed on their system first.

- Download the .zip file off the main page (https://github.com/paranjaa/ENSE-374-Team-Project), and open the MVP folder - run terminal from within that folder.

- In the terminal, run the following commands:
  - `npm init`
  - `npm i express ejs mongoose`
  - `npm i passport passport-local passport-local-mongoose express-session dotenv`
---
### On **Windows** continue following these instructions:

- In a seperate terminal, enter the following command:
  - `mongod`

- Then in another terminal, enter the following command:
  - `nodemon`
  - User should see a message saying `[nodemon] starting node app.js`, followed by `Server is running on http://localhost:3000`. In this case the user would then paste `http://localhost:3000` into a Google Chrome browser window to access the ShareNote MVP. 

---
### On **Mac** continue following these instructions:

- In a seperate terminal, enter the following command (this is to run MongoDB, i.e. the mongod process, as a macOS service):
  - `brew services start mongodb-community@5.0`

- Then in another terminal, enter the following command:
  - `nodemon`
  - User should see a message saying `[nodemon] starting node app.js`, followed by `Server is running on http://localhost:3000`. In this case the user would then paste `http://localhost:3000` into a Google Chrome browser window to access the ShareNote MVP.
