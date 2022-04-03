const express = require("express");
const socket = require("socket.io");
var bodyParser = require('body-parser');
var mysql      = require('mysql');
// App setup
const PORT = 7000;
const app = express();
app.use(bodyParser.urlencoded({extended : true}));
app.use(bodyParser.json());

var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : 'Anhkhoa@321',
    database : 'offer_project'
  });
   
  //connection.connect();

// Static files
app.use(express.static("public"));

// Socket setup
const server = app.listen(PORT, function () {
  console.log(`Listening on port ${PORT}`);
  console.log(`http://localhost:${PORT}`);
});
const io = require("socket.io")(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    allowedHeaders: ["username"],
    credentials: false
  }
});


app.post("/reward",function(request, response) {
  
  io.emit("signal reward", request.body);
  response.send("ok");
});

const setmsg = (auth_id, username, messages) =>{

  
   
  connection.query("INSERT INTO chat (`auth_id`, `username`, `messages`) values ('"+auth_id+"','"+username+"','"+messages+"');", function (error, results, fields) {
    //if (error) throw error;
    console.log('The solution is: ', results[0].solution);
  });
   
  //connection.end();
}

const activeUsers = new Set();

io.on("connection", function (socket) {
  console.log("Made socket connection");

  socket.on("new user", function (data) {
    socket.userId = data;
    activeUsers.add(data);
    io.emit("new user", [...activeUsers]);
  });

  socket.on("disconnect", () => {
    activeUsers.delete(socket.userId);
    io.emit("user disconnected", socket.userId);
  });

  socket.on("chat message", function (data) {
    console.log(data);
    setmsg(data.id, data.username, data.message);
    io.emit("chat message", data);
  });
  
  socket.on("typing", function (data) {
    socket.broadcast.emit("typing", data);
  });
});