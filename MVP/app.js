const fs = require("fs");
const express  = require( "express" );
const mongoose = require( "mongoose" );

const session = require("express-session")
const passport = require("passport")
const passportLocalMongoose = require("passport-local-mongoose")
require("dotenv").config();

const app = express(); 
const port = 3000; 

app.use( express.urlencoded( { extended: true} ) ); 
app.use(express.static(__dirname+'/public'));

app.use(session({
    secret: process.env.SECRET,
    resave: false,
    saveUninitialized: false
}));

app.use (passport.initialize());
app.use (passport.session());

app.set( "view engine", "ejs" );

mongoose.connect( 'mongodb://localhost:27017/ShareNote', 
                 { useNewUrlParser: true, useUnifiedTopology: true });


const userSchema = new mongoose.Schema ({
    username: String,
    password: String
})

userSchema.plugin(passportLocalMongoose);

const User = new mongoose.model("User", userSchema);


const folderSchema = new mongoose.Schema({
  _id: Number,
  name: String,
  creator: String,
});

const Folder = mongoose.model ("Folder", folderSchema);

const noteSchema = new mongoose.Schema({
  _id: Number,
  title: String,
  location: String,
  content: String,
  creator: String
});

const Note = mongoose.model ("Note", noteSchema);



passport.use(User.createStrategy());

passport.serializeUser(User.serializeUser());
passport.deserializeUser(User.deserializeUser());

// Simple server operation
app.listen ( port, () => {
    // template literal
    console.log ( `Server is running on http://localhost:${port}` );
});

app.post( "/register", (req, res) => {
    console.log( "User " + req.body.username + " is attempting to register" );
    User.register({ username : req.body.username }, 
                    req.body.password, 
                    ( err, user ) => {
        if ( err ) {
        console.log( err );
            res.redirect( "/" );
        } else {
            passport.authenticate( "local" )( req, res, () => {
                res.redirect(307,"/mainViewer" );
            });
        }
    });
});

app.post( "/login", ( req, res ) => {
    console.log( "User " + req.body.username + " is attempting to log in" );
    let dataRecieved = req.body;
    console.log(dataRecieved);
    const user = new User ({
        username: req.body.username,
        password: req.body.password
    });
    req.login ( user, ( err ) => {
        if ( err ) {
            console.log( err );
        } else {
            passport.authenticate( "local" )( req, res, () => {
                res.redirect(307,"/mainViewer" ); 
            });
        }
    });
});

app.get("/", (req, res) =>{
  res.render("index");
  console.log("A user requested the root route");
} );
  

app.post( "/mainViewer", async( req, res ) => {
    console.log("A user is accessing the main viewer route using post, and...");
    if ( req.isAuthenticated() ){
        try {
            console.log( "was authorized");
            // console.log("and found");
            //console.log( tasks );
            // const tasks = await Task.find();
            const folders = await Folder.find()
            res.render( "mainViewer", {folders:folders, username: req.body.username });
        } catch ( error ) {
            console.log( error );
            console.log("HERE");
        }
    } else {
        console.log( "was not authorized." );
        res.redirect( "/" );
    }
});

app.post( "/folderViewer", async( req, res ) => {
  console.log("A user is accessing the single folder viewer route using post, and...");
  if ( req.isAuthenticated() ){
      try {
          console.log( "was authorized");
          // console.log("and found");
          //console.log( tasks );
          // const tasks = await Task.find();
          const folders = await Folder.find();
          const notes = await Note.find();
          res.render( "folderView", {folders:folders, notes:notes, location:req.body.folderName, username: req.body.username });
      } catch ( error ) {
          console.log( error );
      }
  } else {
      console.log( "was not authorized." );
      res.redirect( "/" );
  }
});

app.post( "/noteEditor", async( req, res ) => {
  console.log("A user is accessing the note editor route using post, and...");
  if ( req.isAuthenticated() ){
      try {
          console.log( "was authorized");
          // console.log("and found");
          //console.log( tasks );
          // const tasks = await Task.find();
          const folders = await Folder.find();
          const notes = await Note.find();
          const users = await Note.find();
          res.render("noteEditor", {username:req.body.username, location:req.body.location, noteName:req.body.noteName, users:users, folders:folders, notes:notes});
      } catch ( error ) {
          console.log( error );
      }
  } else {
      console.log( "was not authorized." );
      res.redirect( "/" );
  }
});

app.post("/saveNote",async(req,res)=>{
  console.log("A user hit the button for saving a note");



  console.log(req.body);

  try
  {
      //mostly the same changes as lab 7, but looking through database with trys and catches instead
      await Note.updateOne( { _id: parseInt(req.body.id)}, { $set: { content: req.body.noteContent } });                    
      console.log("Finished editing");
      res.redirect(307,"/mainviewer");
  } 
  catch (error) 
  {
    console.log("Not finished editing, there was an error");
    console.log(error);
    res.redirect(307,"/mainViewer");
  }
});






app.post("/returnViewer",(req,res)=>{
  console.log("A user hit the button for returning to the main page");
  res.redirect(307,"/mainViewer");
});



app.get( "/logout", ( req, res ) => {
    console.log( "A user is logging out" );
    req.logout();
    res.redirect("/");
});


app.post("/newFolder",async(req,res)=>{
  console.log("A user hit the new Folder button");

  console.log(req.body);
  
  if(req.body.newFolderName === '')
  {
    console.log("Empty text in new folder name, no change made");
    res.redirect(307,"/mainViewer"); //don't change anything, and reload the page
  }
  else
  {
    //using count to get a new id, instead of the length of an array
    let newID = await Folder.count();
    newID = newID + '';
    console.log("The new ID is" + newID);
    const folder = new Folder({
        _id: newID,
        name: req.body.newFolderName,
        creator: req.body.username 
    });
  folder.save();
  res.redirect( 307,"/mainViewer" );
  }
});


app.post("/newNote",async(req,res)=>{
  console.log("A user hit the new note button");

  console.log(req.body);

  if(req.body.newFolderName === '')
  {
    console.log("Empty text in new note name, no change made");
    res.redirect(307,"/mainViewer"); //don't change anything, and reload the page
  }
  else
  {
    //using count to get a new id, instead of the length of an array
    let newID = await Note.count();
    console.log("The new ID is " + newID);
    const note = new Note({
        _id: newID,
        title: req.body.newNoteName,
        location: req.body.location,
        content: "Empty for now",
        creator: req.body.username,
    });
  note.save();
  res.redirect( 307,"/mainViewer" );
  }
});




app.post( "/addtask", async( req, res ) => {
  console.log( "User " + req.body.username + " is adding the task:" );
  console.log( req.body );

  if(dataRecieved.newTaskText === '')
  {
    console.log("Empty text in new task, no change made");
    res.redirect(307,"/todo"); //don't change anything, and reload the page
  }
  else
  {
    //using count to get a new id, instead of the length of an array
    let newID = await Task.count();
    newID = newID + '';
    console.log("The new ID is" + newID);
    const task = new Task({
        _id: newID,
        text: req.body.newTaskText,
        state:"Unclaimed",
        creator: req.body.username,
        isTaskClaimed: false,
        claimingUser: "NULL",
        isTaskDone: false,
        isTaskCleared: false
    });

  task.save();

  res.redirect( 307,"/todo" );
  }
});








