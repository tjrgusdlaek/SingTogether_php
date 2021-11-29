'use strict';

var os = require('os');
var nodeStatic = require('node-static');
var https = require('https');
var socketIO = require('socket.io');
var fs = require('fs');
var options = {
  key: fs.readFileSync('key.pem'),
  cert: fs.readFileSync('cert.pem')
};

var fileServer = new(nodeStatic.Server)();
var app = https.createServer(options, function(req, res) {
  fileServer.serve(req, res);
}).listen(8080);

var io = socketIO.listen(app);

//emit은 주는것 on은 받는것 


//소켓에 연결한다
io.sockets.on('connection', function(socket) {

  //클라이언트의 메세지를 기록하는 기능 . 
  function log() {
    var array = ['Message from server:'];
    array.push.apply(array, arguments);
    socket.emit('log', array);
    console.log('chao', array);
  }

  socket.on('message', function(message) {
    // for a real app, would be room-only (not broadcast)
    // socket.broadcast.emit('message', message);
    
    //io.sockets.sockets[to] 뜻은 to: 누구에게 보내겠냐는 뜻

    var to = message['to'];
    log('from:' + socket.id + " to:" + to, message);
    io.sockets.sockets[to].emit('message', message);
  });

  ////시청자가 들어왔을 때 
  socket.on('getViewer', function(message) {
    var roomName = message['roomName'];
    var viewer = message['viewer'];
    console.log(viewer);
    io.to(roomName).emit('getViewer', message);
  });

  /////스트리머가 방송을 종료했을 때 
  socket.on('liveStreamingFinish', function(message) {
    io.to(message).emit('liveStreamingFinish', message);
  });

    /////시청자가 나갔을 때 
    socket.on('outViewer', function(message) {
      var roomName = message['roomName'];
      io.to(roomName).emit('outViewer', message);
    });

  ////방송 안에서 채팅을 했을 때 
  socket.on('chattingMessage', function(message) {

  var roomName = message['roomName'];
  var nickName = message['nickName'];
  var inputText = message['inputText'];
  io.to(roomName).emit('chattingMessage', message);
  });
  ////시청자 강제퇴장 
  socket.on('viewerOutOfHere', function(message) {

    var to = message['socketId'];
      io.sockets.sockets[to].emit('viewerOutOfHere',message)
          console.log(message);
          // console.log(io.sockets);
      // io.to(message).emit('viewerOutOfHere', message);
    });

  socket.on('addViewerList', function(message) {

  var roomName = message['roomName'];
  var nickName = message['nickName'];
  var inputText = message['inputText'];
  io.to(roomName).emit('addViewerList', message);
  });
  

  //socket.on == 클라이언트로부터  cerate or join 이라는 메세지를 받았을 때 일어나는 메소드
  socket.on('create or join', function(room) {
    log('Received request to create or join room ' + room);

    ///처음 방장이 방을 만들었을 때 
    var clientsInRoom = io.sockets.adapter.rooms[room];
    var numClients = clientsInRoom ? Object.keys(clientsInRoom.sockets).length : 0;
    log('Room ' + room + ' now has ' + numClients + ' client(s)');

    /// 클라이언트 숫자가 0 이면 방을 만든다 
    if (numClients === 0) {

      //join : 어느 방에 들어가겠냐는 뜻
      socket.join(room);
      log('Client ID ' + socket.id + ' created room ' + room);
      socket.emit('created', room, socket.id);

    } else {
      log('Client ID ' + socket.id + ' joined room ' + room);
      io.sockets.in(room).emit('join', room, socket.id);
      socket.join(room);
      socket.emit('joined', room, socket.id);
      io.sockets.in(room).emit('ready');
    }
  });
  

  socket.on('ipaddr', function() {
    var ifaces = os.networkInterfaces();
    for (var dev in ifaces) {
      ifaces[dev].forEach(function(details) {
        if (details.family === 'IPv4' && details.address !== '127.0.0.1') {
          socket.emit('ipaddr', details.address);
        }
      });
    }
  });

  socket.on('bye', function(message){
    //  clientsInRoom = io.sockets.adapter.rooms[message.room];
    //  numClients = clientsInRoom ? Object.keys(clientsInRoom.sockets).length : 0;
    //  numClient = numClients-1 ;

    // socket.emit('bye', message);


    // console.log(io.sockets);
    // var clientsInRoom = io.sockets.sockets;
    // console.log(clientsInRoom);


    // console.log(numClients);
    // console.log(Server);
  
    socket.disconnect(true);
 
    // delete io.sockets.eio[Server.clients[message.from]];
    // io.sockets.sockets[message.from].disconnect();
    // // delete io.sockets.Server.clients[message.from];
    // delete io.sockets.sockets[message.from];
    // delete io.sockets.connected[message.from];
    // delete io.sockets.adapter.rooms[message.from];
    // delete io.sockets.adapter.sids[message.from];
   


    console.log('received bye');
    // console.log(message.from);
    // console.log(clientsInRoom);

    // console.log(numClients);
    // console.log(io.sockets);
    // console.log(io);
    
    // console.log(room);
   
  });

});