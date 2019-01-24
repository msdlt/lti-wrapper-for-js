
<body onload="init()">
	<h1 id="exercise-header"></h1>
	<p id="exercise-sub-header"></p>
	<canvas id="can" width="1536" height="648"></canvas>
	<img id="canvasimg" style="position:absolute;top:10%;left:52%;" style="display:none;"> 
	<button class="button clear" onclick="erase()" disabled>Restart</button>
	<button class="button submit" onclick="submit()" disabled>Submit</button>
	<div style="position:absolute;top:85%;left:70%;font-family:Helvetica", id="distance">Distance: 0 cm</div>
	<div style="position:absolute;top:90%;left:70%;font-family:Helvetica", id="time">Time: 0 seconds</div>
	
	<div class="dot-label" style="top:70%;left:15%;">1</div>
	<span class="dot" style="top:73%; left:15%;"></span>

	<div class="dot-label" style="top:65%;left:25%;">2</div>
	<span class="dot" style="top:68%; left:25%;"></span>

	<div class="dot-label" style="top:20%;left:15%;">3</div>
	<span class="dot" style="top:23%; left:15%;"></span>

	<div class="dot-label" style="top:30%;left:30%;">4</div>
	<span class="dot" style="top:33%; left:30%;"></span>

	<div class="dot-label" style="top:68%;left:40%;">5</div>
	<span class="dot" style="top:71%; left:40%;"></span>

	<div class="dot-label" style="top:25%;left:35%;">6</div>
	<span class="dot" style="top:28%; left:35%;"></span>

	<div class="dot-label" style="top:13%;left:60%;">7</div>
	<span class="dot" style="top:16%; left:60%;"></span>

	<div class="dot-label" style="top:40%;left:50%;">8</div>
	<span class="dot" style="top:43%; left:50%;"></span>

	<div class="dot-label" style="top:65%;left:80%;">9</div>
	<span class="dot" style="top:68%; left:80%;"></span>

	<div class="dot-label" style="top:45%;left:70%;">10</div>
	<span class="dot" style="top:48%; left:70%;"></span>

	<div class="dot-label" style="top:27%;left:85%;">11</div>
	<span class="dot" style="top:30%; left:85%;"></span>
</body>