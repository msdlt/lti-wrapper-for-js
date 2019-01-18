var canvas = null, 
	ctx = false, 
	flag = false,
	prevX = 0,
	currX = 0,
	prevY = 0,
	currY = 0,
	dot_flag = false,
	//distanceSum = 0,
	submitted = false,
	//elapsedTime = 0,
	currTime = 0,
	prevTime = 0,
	timerFuncVar = 0;
	
var scoresOnTheDoors = {
	writingTimeTaken: 0,
	writingLength: 0,
	joiningTimeTaken: 0,
	joiningLength: 0
};

var x = "blue",
	y = 2;

var d = new Date();

function init() {
	canvas = document.getElementById('can');
	ctx = canvas.getContext("2d");
	w = canvas.width;
	h = canvas.height;

	//attach mouse event handlers
	$( "#can" ).on( "mousemove", function( event ) {
		findxy('move', event);
	});
	$( "#can" ).on( "mousedown", function( event ) {
		findxy('down', event);
	});
	$( "#can" ).on( "mouseup", function( event ) {
		findxy('up', event);
	});
	$( "#can" ).on( "mouseout", function( event ) {
		findxy('out', event);
	});

	document.getElementById("distance").style.fontSize = "x-large";
	document.getElementById("time").style.fontSize = "x-large";
}



function draw() {
	ctx.beginPath();
	ctx.moveTo(prevX, prevY);
	ctx.lineTo(currX, currY);
	ctx.strokeStyle = x;
	ctx.lineWidth = y;
	ctx.stroke();
	ctx.closePath();
}

function erase() {
	var m = confirm("Do you want to clear the screen?");
	if (m) {
		ctx.clearRect(0, 0, w, h);
		document.getElementById("canvasimg").style.display = "none";
		document.getElementById("distance").innerHTML = "Distance: 0 cm";
		document.getElementById("time").innerHTML = "Time: 0 seconds";
		scoresOnTheDoors.writingLength = 0;
		//distanceSum = 0;
		scoresOnTheDoors.writingTimeTaken = 0;
		//elapsedTime = 0;
		currTime = 0;
		prevTime = 0;
		submitted = false;
		clearInterval(timerFuncVar);
		timerFuncVar = 0;
	}
}

function submit() {
  if (submitted) {
	//todo disable submit button (below)
	alert("You have already submitted your results")
  }
  else {
		var m = confirm("Submit your results?");
		if(m){
			saveScores();
			$('#can').off();  //remove all mouse event handlers on the canvas
			clearInterval(timerFuncVar);
			timerFuncVar = 0;
			submitted = true;
		}

	}

}

function updateTime() {
  prevTime = currTime;
  currTime = new Date();
  if (prevTime > 0) {
	scoresOnTheDoors.writingTimeTaken = scoresOnTheDoors.writingTimeTaken + ((currTime - prevTime)/1000);
	//elapsedTime = elapsedTime + ((currTime - prevTime)/1000);
  }
  console.log(scoresOnTheDoors.writingTimeTaken);
  document.getElementById("time").innerHTML = "Time: " + scoresOnTheDoors.writingTimeTaken.toFixed(2) + " seconds";
  //document.getElementById("time").innerHTML = "Time: " + elapsedTime.toFixed(2) + " seconds";

}


function findxy(res, e) {
	if (res == 'down') {
		prevX = currX;
		prevY = currY;
		currX = e.clientX - canvas.offsetLeft;
		currY = e.clientY - canvas.offsetTop;

		if(!submitted) {
		  if(!timerFuncVar) {  //prevents another timer starting - one which we won't know the id of - if this is called again.
			timerFuncVar = setInterval(updateTime, 100);
		  }
		}

		flag = true;
		dot_flag = true;
		if (dot_flag) {
			ctx.beginPath();
			ctx.fillStyle = x;
			ctx.fillRect(currX, currY, 2, 2);
			ctx.closePath();
			dot_flag = false;
		}
	}
	if (res == 'up' || res == "out") {
		flag = false;
	}
	if (res == 'move') {
		if (flag) {
			prevX = currX;
			prevY = currY;
			currX = e.clientX - canvas.offsetLeft;
			currY = e.clientY - canvas.offsetTop;
			draw();
			scoresOnTheDoors.writingLength = scoresOnTheDoors.writingLength  + (0.027 * Math.sqrt(Math.pow((currX-prevX), 2)+Math.pow((currY-prevY), 2)));
			//distanceSum = distanceSum + (0.027 * Math.sqrt(Math.pow((currX-prevX), 2)+Math.pow((currY-prevY), 2)));
			document.getElementById("distance").innerHTML = "Distance: " + scoresOnTheDoors.writingLength.toFixed(2) + " cm";
			//document.getElementById("distance").innerHTML = "Distance: " + distanceSum.toFixed(2) + " cm";
		}
	}
}

function saveScores(){
	data=JSON.stringify(scoresOnTheDoors);
	$.ajax({
		type:        "POST",
		url:         "data.json",
		contentType: "application/json",
		dataType:    "json",
		data:        data,
		error:       function(xhr,opt,er){
			console.log("unable to send scores to server: "+er);
			if(typeof(loginUrl) !== "undefined") {
				alert("You score could not be saved. Your session may have timed out. Click OK to log back in via WebLearn. Sorry for any inconvenience caused.");
				window.location.href = loginUrl;
			}
		}
	}).done(function( msg ) {
		alert( "Your score has been saved");
	});
	;
}