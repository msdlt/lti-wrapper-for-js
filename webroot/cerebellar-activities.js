var canvas = null, 
	ctx = false, 
	flag = false,
	prevX = 0,
	currX = 0,
	prevY = 0,
	currY = 0,
	dot_flag = false,
	distanceSum = 0,
	submitted = false,
	elapsedTime = 0,
	currTime = 0,
	prevTime = 0,
	timerFuncVar = 0;
	
	exerciseNo = 0;
	
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
	
	exerciseNo = 1;
	$("#exercise-header").text("Task 1: Writing ");
	$("#exercise-sub-header").text("Write the word 'Neuroscience' as quickly as you can, while still being legible, and click Submit as soon as you have finished");

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
	var m = confirm("Do you want to restart?");
	if (m) {
		reset();
	}
}

function reset() {
	ctx.clearRect(0, 0, w, h);
	document.getElementById("canvasimg").style.display = "none";
	document.getElementById("distance").innerHTML = "Distance: 0 cm";
	document.getElementById("time").innerHTML = "Time: 0 seconds";
	//scoresOnTheDoors.writingLength = 0;
	distanceSum = 0;
	//scoresOnTheDoors.writingTimeTaken = 0;
	elapsedTime = 0;
	currTime = 0;
	prevTime = 0;
	submitted = false;
	clearInterval(timerFuncVar);
	timerFuncVar = 0;
	$("button.clear").prop('disabled', true);
	$("button.submit").prop('disabled', true);
}

function submit() {
  /*if (submitted) {
	//todo disable submit button (below)
	alert("You have already submitted your results")
  }
  else {*/
	if (distanceSum == 0 || elapsedTime == 0) {
		alert("It doesn't look as if you have written anything. Please try again");
		return;
	}
	var m = confirm("Submit your results?");
	if(m){
		$("button.clear").prop('disabled', true);
		$("button.submit").prop('disabled', true);
		saveScores();
		if(exerciseNo == 1) {
			//setting up next exercise
			//elapsedTime = 0;
			//distanceSum = 0
			reset();
			$("#exercise-header").text("Task 2: Join the dots ");
			$("#exercise-sub-header").text("Join the dots, in order, as quickly as you can, making sure your line touches each dot, and click Submit as soon as you have finished");
			$(".dot, .dot-label").show();
			exerciseNo = 2;
		} else {
			$('#can').off();  //remove all mouse event handlers on the canvas
			clearInterval(timerFuncVar);
			timerFuncVar = 0;
		}
	}

	//}

}

function updateTime() {
	prevTime = currTime;
	currTime = new Date();
	if (prevTime > 0) {
		//scoresOnTheDoors.writingTimeTaken = scoresOnTheDoors.writingTimeTaken + ((currTime - prevTime)/1000);
		elapsedTime = elapsedTime + ((currTime - prevTime)/1000);
	}
	//console.log(scoresOnTheDoors.writingTimeTaken);
	//document.getElementById("time").innerHTML = "Time: " + scoresOnTheDoors.writingTimeTaken.toFixed(2) + " seconds";
	document.getElementById("time").innerHTML = "Time: " + elapsedTime.toFixed(2) + " seconds";

}


function findxy(res, e) {
	if (res == 'down') {
		prevX = currX;
		prevY = currY;
		currX = e.clientX - canvas.offsetLeft;
		currY = e.clientY - canvas.offsetTop;
		
		$("button.clear").prop('disabled', false);
		$("button.submit").prop('disabled', false);

		//if(!submitted) {
		if(!timerFuncVar) {  //prevents another timer starting - one which we won't know the id of - if this is called again.
			timerFuncVar = setInterval(updateTime, 100);
		}
		//}

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
			//scoresOnTheDoors.writingLength = scoresOnTheDoors.writingLength  + (0.027 * Math.sqrt(Math.pow((currX-prevX), 2)+Math.pow((currY-prevY), 2)));
			distanceSum = distanceSum + (0.027 * Math.sqrt(Math.pow((currX-prevX), 2)+Math.pow((currY-prevY), 2)));
			//document.getElementById("distance").innerHTML = "Distance: " + scoresOnTheDoors.writingLength.toFixed(2) + " cm";
			document.getElementById("distance").innerHTML = "Distance: " + distanceSum.toFixed(2); // + " cm";
		}
	}
}

function saveScores(){
	//let's populate data according to which exercise we're doing:
	if(exerciseNo==1) {
		scoresOnTheDoors.writingLength = distanceSum;
		scoresOnTheDoors.writingTimeTaken = elapsedTime;
	} else {
		//exercise 2
		scoresOnTheDoors.joiningLength = distanceSum;
		scoresOnTheDoors.joiningTimeTaken = elapsedTime;
	}
	/* Save data to back end - will only work when launched as an LTI tool */	
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
		//if(exerciseNo == 1) {
			alert( "Your results have been saved");
		//} else {
			//alert( "Your results for Task 2 have been saved");
		//}
	});
	;
}