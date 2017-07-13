var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);
var fs = require('fs');
var bodyParser = require('body-parser');
var mysql=require('mysql');
var readline = require('readline');
var async=require('async');

var competitionStarted = false;
var competitionOver = false;

var winner="";
//There should be a check with the sql server to see if there has already been some kind of addition in case the server goes down.

var competitionStartDate;

var portNum = 666;

var currentUsers = 0;

var sqlConnection_read = mysql.createConnection({
   host:"localhost",
   user:"WebRead",
   password:"jfuqndinfspf872248",
   database:"websitetesting"
});
var sqlConnection_write = mysql.createConnection({
   host:"localhost",
   user:"WebWrite",
   password:'3294shdfsAf*"5sdf*',
   database:"websitetesting"
});
sqlConnection_read.connect(function(err){
   if(err) throw err;
});
sqlConnection_write.connect(function(err){
   if(err) throw err;
});
var competitionInfo;

app.use(bodyParser.urlencoded({extended:false}));
server.listen(portNum);
console.log("Competition server online!");

var competitionInfo = {
    id:0,
    title:"",
    topic:"",
    difficulty:"",
    description:"",
    jackpot:0,
    answer:0,
    author:0
}

function GetTimeUntilComp(){
  var totalMilis = Math.abs(competitionStartDate-Date.now());
  var minutes = Math.floor(totalMilis/60000);
  var seconds = Math.floor((totalMilis%60000)/1000).toFixed(0);
  io.sockets.emit('startDate',{title:competitionTitle,topic:competitionTopic,compDifficulty:competitionDifficulty,timeToStart:minutes + ":" + (seconds < 10 ? '0':'') + seconds,jackpot:jackPotTotal});
  if(minutes==0&&seconds==0){
    console.log("Let the games begin");
    clearInterval(repeatUntil);
    io.sockets.emit('startGame',{title:competitionTitle,compTopic:competitionTopic,compDescription:competitionDescription});
    competitionStarted=true;
    clearInterval(repeatUntil);
  }
}

function GetCompetitionInfo(competitionID,callback){
    var competitionInfo = {
        id:competitionID,
        title:"",
        topic:"",
        difficulty:"",
        description:"",
        jackpot:0,
        answer:0,
        author:0
    }
    var testVariable;
    console.log("Connected to TSDB, retrieving competition info...");
    var query = "SELECT * FROM competitions WHERE PuzzleID="+competitionID+";";
    sqlConnection_read.query(query,function(err,result){
       if(err) {console.log("There has been an error connecting to TSDB, here is debug output:\n");throw err;}
       competitionInfo.title = result[0].Puzzle_Title;
       competitionInfo.topic = result[0].Puzzle_Topic;
       competitionInfo.difficulty = result[0].Puzzle_Difficulty;
       competitionInfo.description=result[0].puzzle_description;
       competitionInfo.answer=result[0].Puzzle_Answer;
       competitionInfo.jackpot=result[0].Puzzle_Jackpot;
       competitionInfo.author=result[0].Puzzle_Author;
       callback(competitionInfo);
    });
}
function GetLatestCompetitionID(callback){
    console.log("Connted to TSDB, getting latest competition ID...");
    var query="SELECT * FROM compschedule;";
    var bestResult=null;
    sqlConnection_read.query(query,function(err,result){
        if(err){console.log("There has been an error connecting to TSDB, here is debug output:\n");throw err;}
        callback(result);
    });
}
function GetUserID(username,callback){
    var userID;
    console.log("Connected to TSDB, getting user id of " + username);
    var query = "SELECT UID FROM AccountInfo WHERE Account_Username='"+username+"';";
    sqlConnection_read.query(query,function(err,result){
       if(err) throw err;
       console.log("Their user ID is:",result[0].UID);
       callback(result[0].UID);
    });
}
function GetUserPoints(userID,callback){
    var points = {currentPoints:0,totalWinnings:0};
    console.log("Getting points of userID: " + userID);
    var query1="SELECT Account_Points FROM AccountInfo WHERE UID="+userID+";";
    var query2="SELECT Rank_TotalWinnings FROM ranking_solo WHERE UID="+userID+";";
    sqlConnection_read.query(query1,function(err,result){
       if(err) throw err;
       points.currentPoints=result[0].Account_Points;
    });
    sqlConnection_read.query(query2,function(err,result){
       if(err) throw err;
       points.totalWinnings=result[0].Rank_TotalWinnings;
    });
    callback(points);
}
function UpdateUserPoints(userID,pointAmount){
    //This function needs to update the points within the ranking table and the accountinfo table.
    GetUserPoints(userID,function(data){
        console.log("Connected to TSDB, putting " + pointAmount + " points on UserID " + userID);
        //var query1 = "UPDATE AccountInfo SET Account_Points=Account_Points+"+pointAmount+" WHERE UID="+userID+";";
        //var query2 = "UPDATE ranking_solo SET Rank_TotalWinnings=Rank_TotalWinnings"+pointAmount+" WHERE UID="+userID+";";
        var accountPoints = data.currentPoints+pointAmount;
        var winnings = data.totalWinnings+pointAmount;
        var query1 = "UPDATE AccountInfo SET Account_Points="+accountPoints+" WHERE UID="+userID+";";
        var query2 = "UPDATE ranking_solo SET Rank_TotalWinnings="+winnings+" WHERE UID="+userID+";";
        sqlConnection_write.query(query1,function(err,result){
           if(err) throw err;
        });
        sqlConnection_write.query(query2,function(err,result){
           if(err) throw err;
        });
    });
}

var repeatUntil = setInterval(GetTimeUntilComp,1000);

var socketDictionary = {};//This willa hold the sockets and names of people who connect.

var competitionTitle;
var competitionTopic;
var competitionDifficulty;
var competitionDescription;
var competitionAnswer;
var jackPotTotal;
var competitionAuthor;
GetLatestCompetitionID(function(result){
    var allResults = result;
    console.log("Got results. Now checking closest one.");
    var closestDate = null;
    var closestID=null;
    for(var c=0;c<result.length;c++){
        console.log("On c="+c.toString());
        var currentDate = Date.now();
        var resultDate = result[c].TimeStart;
        if((Math.abs(currentDate-resultDate)) < (Math.abs(currentDate-closestDate))){
            closestDate=resultDate;
            closestID=result[c].PuzzleID;
            console.log("Set new closest result.");
        }else{
            console.log("lolno");
        }
    }
    console.log("The closest ID was " + closestID.toString());
    competitionStartDate=closestDate;
    console.log("Change date.");
    console.log("Now getting comp info...");
    GetCompetitionInfo(closestID,function(data){
       competitionInfo=data;
       competitionTitle=competitionInfo.title;
       competitionTopic=competitionInfo.topic;
       competitionDifficulty=competitionInfo.difficulty;
       competitionDescription=competitionInfo.description;
       competitionAnswer=competitionInfo.answer;
       jackPotTotal=competitionInfo.jackpot;
       competitionAuthor=competitionInfo.author;

       console.log("Title:"+competitionTitle);
       console.log("Topic:"+competitionTopic);
       console.log("Difficulty:"+competitionDifficulty);
       console.log("Description:"+competitionDescription);
       console.log("Answer:"+competitionAnswer);
       console.log("Jackpot:"+jackPotTotal);
       console.log("Author UID:"+competitionAuthor);

    });
});

io.sockets.on('connection',function(socket){
    currentUsers+=1;
    console.log("Someone has connected!");
    if(!competitionStarted){
        var totalMillis = Math.abs(competitionStartDate-Date.now());
        var minutes = Math.floor(totalMillis/60000);
        var seconds = Math.floor((totalMillis%60000)/1000).toFixed(0);
        socket.emit('startDate',{title:competitionTitle,topic:competitionTopic,compDifficulty:competitionDifficulty,timeToStart:minutes+":"+(seconds<10?'0':'')+seconds,jackpot:jackPotTotal});
        console.log("Sent timer info!")
    }else if(competitionOver){
        socket.emit('gameOver',{name:winner,answer:competitionAnswer,jackpot:jackPotTotal});
    }else{
        socket.emit('startGame',{title:competitionTitle,compTopic:competitionTopic,compDifficulty:competitionDifficulty,compDescription:competitionDescription});
    }
    io.sockets.emit('counterUpdate',{players:currentUsers});

    socket.on('socketName',function(data){
       console.log("Got socket name!");
       socketDictionary[socket]=data.name;
       console.log(socketDictionary[socket]);
    });

    socket.on('disconnect',function(data){
        console.log("Someone disconnected! D:");
        delete socketDictionary[socket];
        currentUsers-=1;
        io.sockets.emit('counterUpdate',{players:currentUsers});
    });

    socket.on('answer',function(data){
       console.log(socketDictionary[socket] + " has submitted an answer of "+data.answer);
       //Prevent exploits with the following if statement. This prevents anyone submitting an answer before or after the competition.
       if(!competitionOver && competitionStarted){
        if(data.answer==competitionAnswer){
            //Update mysql database to update winner's earnings
            winner=socketDictionary[socket];
            console.log(winner + " has won the competition!");
            io.sockets.emit('gameOver',{name:winner,answer:competitionAnswer,jackpot:jackPotTotal});
            competitionOver=true;
            //Now we are going to update the points of the user
            GetUserID(winner,function(data){
               console.log("Got the user ID as " + data);
               UpdateUserPoints(data,jackPotTotal);
               console.log("Updated points.");
            });

        } else{
            console.log("Answer was wrong!");
            socket.emit('wrongAnswer',{});
        }
    }});

});
