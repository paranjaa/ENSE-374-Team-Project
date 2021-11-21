const fs = require("fs");



const express = require ("express");
const app = express(); 
app.use(express.static(__dirname+'/public'));
app.set("view engine", "ejs");

/*
let rawUsers = fs.readFileSync('users.json');
let fomattedUsers = JSON.parse(rawUsers);

let rawTasks = fs.readFileSync('tasks.json');
let fomattedTasks = JSON.parse(rawTasks);
*/

let usersReference = require('./users.json');
const { raw } = require("body-parser");


// a common localhost test port
const port = 3000; 

app.use(express.urlencoded({ extended: true}));


// Simple server operation
app.listen (port, () => {
    // template literal
    console.log (`Server is running on http://localhost:${port}`);
});

app.get("/", (req, res) =>{
  res.render("index");
  console.log("A user requested the root route");
} );  

app.post("/viewer", (req, res) =>{
  console.log(req.body);
  res.render("viewer", {username: req.body.username, users:users, tasks:tasks});
  console.log("A user requested the file viwer route");
} );  


app.post("/editor", (req, res) =>{
  console.log(req.body);
  res.render("editor", {username: req.body.username});
  console.log("A user requested the file viwer route");
} );  



app.post("/login", (req, res) => {
  //print the recieved data and copy it to a variable
  console.log(req.body);
  let dataRecieved = req.body;

  //make a local copy of users.json and parse it
  let rawdata = fs.readFileSync('users.json');
  let users = JSON.parse(rawdata);

  //set valid as false by default, since two conditions need to be true for them to login
  let valid = false;

  //run through all of the copy of users
  for(i = 0; i< users.array.length; i++)
  {
    //if there's a user in there that matches, check the password, if that also matches, only then change valid
    if(users.array[i].username === dataRecieved.username)
    {
      if(users.array[i].password === dataRecieved.password)
      {
        valid = true;
      }
    }
  }
  

  //if it did happen, print a message and go to it
  if(valid === true)
  {
    console.log("Logged in with valid info, loading the viewer list");
    res.redirect(307,"/viewer"); //and send the user to the list
  }

  //if not, print a different message and reload the current page
  else
  {
    console.log("No user found with that info, loading at index");
    res.redirect("/"); 
  }

} );


app.get("/logout",(req,res)=>{
  //just go back to the index
  console.log("A user hit the logout button");
  res.redirect("/");
});


app.post("/register", (req, res) => {
  console.log("A user hit the register button");
  //again, put the data in a variable and print it
  let dataRecieved = req.body;
  console.log(dataRecieved);

  //make a copy of the list of users
  let rawdata = fs.readFileSync('users.json');
  let users = JSON.parse(rawdata);

  let valid = true;

  //if authorization isn't that static value (maybe != instead?)
  if(dataRecieved.authorization !== "todo2021")
  {
    valid = false; //change valid and print a message
    console.log("Incorrect Authorization");
  }

  //after that, check if there's a user with that info, change valid print a message if there is
  for(i = 0; i< users.array.length; i++)
  {
    if(users.array[i].username == dataRecieved.username)
    {
      valid = false;
      console.log("User with that username already exists");
    }
  }

  //after the two tests, either add the new user and load the todo list, or stay at the login
  if(valid === true)
  {
    users.array.push({username: dataRecieved.username, password: dataRecieved.password});
    console.log("Added new user")
    fs.writeFileSync ( __dirname + "/users.json", JSON.stringify( users ), "utf8", 
    ( err ) => {
    if ( err )
    {
      console.log( err );
      return;
    }
    });
    res.redirect(307,"/viewer");
  }
  else
  {
    console.log("Invalid registration, reloading index");
    res.redirect("/"); 
  }
});

app.post("/newNote",(req,res)=>{
  console.log("A user hit the new note button");
  res.redirect(307,"/editor");
});


app.post("/returnViewer",(req,res)=>{
  console.log("A user hit the button for returning to the file viewer");
  res.redirect(307,"/viewer");
});






app.post("/addtask",(req,res)=>{
  //as usual, get the data, and make a copy of the relevant JSON
  console.log("User hit the Add task button")
  let dataRecieved = req.body;
  console.log(dataRecieved);
  let rawdata = fs.readFileSync('tasks.json');
  let tasks = JSON.parse(rawdata);


  //to get where the new ID is supposed to go, loop to the end of the array
  let newID = 0;
  for(i = 0; i< tasks.array.length; i++)
  {
    newID++;
  }

  //make a new task with the right values in the parameters, including the fresh ID and the info gotten from the form
  let newTask = 
  {
    _id: newID,
    text: dataRecieved.newTaskText,
    state: "Unclaimed",
    creator: dataRecieved.username,
    isTaskClaimed: false,
    claimingUser: "NULL",
    isTaskDone: false,
    isTaskCleared: false
  }

  //add the new task onto the copy, and use that to update the JSON
  tasks.array.push(newTask);
  fs.writeFileSync ( __dirname + "/tasks.json", JSON.stringify( tasks ), "utf8", 
  ( err ) => {
  if ( err )
  {
    console.log( err );
    return;
  }
  });
  //then reload the page, which will look different hopefully
  res.redirect(307,"/viewer");
});


app.post("/claim",(req,res)=>{

  console.log("User hit the claim button");
  let dataRecieved = req.body;
  let rawdata = fs.readFileSync('tasks.json');
  let tasks = JSON.parse(rawdata);

  //console.log(tasks);

  for(i = 0; i< tasks.array.length; i++)
  {
    if(tasks.array[i]._id == dataRecieved.id)
    {
      tasks.array[i].state = "Claimed";
      tasks.array[i].isTaskClaimed = true;
      tasks.array[i].claimingUser = dataRecieved.username;
    }
  }

  fs.writeFileSync ( __dirname + "/tasks.json", JSON.stringify( tasks ), "utf8", 
  ( err ) => {
  if ( err )
  {
    console.log( err );
    return;
  }
  });
  console.log("Finished claiming");
  res.redirect(307,"/viwer");
});

app.post("/abandonorcomplete",(req,res)=>{
  let dataRecieved = req.body;
  console.log(dataRecieved);
  console.log("A user hit the abandon or complete button");
  let rawdata = fs.readFileSync('tasks.json');
  let tasks = JSON.parse(rawdata);

  if(dataRecieved.completeCheckbox === "on")
  {
    console.log("They checked the box");
    for(i = 0; i< tasks.array.length; i++)
    {
      if(tasks.array[i]._id == dataRecieved.id)
      {
        tasks.array[i].state = "Finished";
        tasks.array[i].isTaskDone = true;
      }
    }
  }
  else
  {
    console.log("They didn't check the box")
    for(i = 0; i < tasks.array.length; i++)
    {
      if(tasks.array[i]._id === dataRecieved.id)
      {
        tasks.array[i].state = "Unclaimed";
        tasks.array[i].isTaskClaimed = false;
        tasks.array[i].claimingUser = "NULL";
      }
    }
  }
  fs.writeFileSync ( __dirname + "/tasks.json", JSON.stringify( tasks ), "utf8", 
  ( err ) => {
  if ( err )
  {
    console.log( err );
    return;
  }
  });
  console.log("Finished abandoning or claiming");

  //code for abandoning or completing the task for the user
  res.redirect(307,"/viewer");
});


app.post("/unfinish",(req,res)=>{
  console.log("A user hit the unfinish button");
  let dataRecieved = req.body;
  console.log(dataRecieved);
  let rawdata = fs.readFileSync('tasks.json');
  let tasks = JSON.parse(rawdata);
  for(i = 0; i< tasks.array.length; i++)
  {
    if(tasks.array[i]._id === dataRecieved.id)
    {
        tasks.array[i].state = "Claimed";
        tasks.array[i].isTaskDone = false;
    }
  }
  fs.writeFileSync ( __dirname + "/tasks.json", JSON.stringify( tasks ), "utf8", 
  ( err ) => {
  if ( err )
  {
    console.log( err );
    return;
  }
  });
  //code for finishing/unfinishing the for the user
  res.redirect(307,"/viewer");
});


app.post("/purge",(req,res)=>{
  console.log("A user hit the purge tasks button");
  let rawdata = fs.readFileSync('tasks.json');
  let tasks = JSON.parse(rawdata);
  for(i = 0; i< tasks.array.length; i++)
  {
    if(tasks.array[i].isTaskDone === true)
    {
        tasks.array[i].isTaskCleared = true;
    }
  }
  fs.writeFileSync ( __dirname + "/tasks.json", JSON.stringify( tasks ), "utf8", 
  ( err ) => {
  if ( err )
  {
    console.log( err );
    return;
  }
  });
  //code for finishing/unfinishing the for the user
  res.redirect(307,"/viewer");
});


function saveAll()
{
  
  fs.writeFileSync ( __dirname + "/tasks.json", 
                   JSON.stringify( tasks ), 
                   "utf8", 
                   ( err ) => {
    if ( err )
    {
      console.log( err );
      return;
    }
  });
  
  fs.writeFileSync ( __dirname + "/users.json", 
                  JSON.stringify( users ), 
                  "utf8", 
                  ( err ) => {
  if ( err ) {
      console.log( err );
      return;
  }
  });
}

function readAll()
{
  let rawdata = fs.readFileSync('tasks.json');
  tasks = JSON.parse(rawdata);
  console.log(tasks);
  rawdata = fs.readFileSync('users.json');
  users = JSON.parse(rawdata);
  console.log(users);
}

readAll();






app.use(express.static("public"))

