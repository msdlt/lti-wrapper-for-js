<head>
    <style>
      .button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius:12px;
    }

    .clear{
      position:absolute;
      top:85%;
      left:45%;
    }

    .submit{
      position:absolute;
      top:85%;
      left:55%;
    }

    </style>
    <script type="text/javascript">
    var canvas, ctx, flag = false,
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
        timerFlag = false,
        timerFuncVar = 0;

    var x = "blue",
        y = 2;

    var d = new Date();

    function init() {
        canvas = document.getElementById('can');
        ctx = canvas.getContext("2d");
        w = canvas.width;
        h = canvas.height;

        canvas.addEventListener("mousemove", function (e) {
            findxy('move', e)
        }, false);
        canvas.addEventListener("mousedown", function (e) {
            findxy('down', e)
        }, false);
        canvas.addEventListener("mouseup", function (e) {
            findxy('up', e)
        }, false);
        canvas.addEventListener("mouseout", function (e) {
            findxy('out', e)
        }, false);

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
            distanceSum = 0;
            elapsedTime = 0;
            currTime = 0;
            prevTime = 0;
            timerFlag = false;
            clearTimeout(timerFuncVar);
        }
    }

    function submit() {
      if (submitted) {
        alert("You have already submitted your results")
      }
      else {
          var m = confirm("Submit your results?");
          if(m){
            submitted = true;
            timerFlag = false;
          }

      }

    }

    function updateTime() {
      prevTime = currTime;
      currTime = new Date();
      if (prevTime > 0) {
        elapsedTime = elapsedTime + ((currTime - prevTime)/1000);
      }
      document.getElementById("time").innerHTML = "Time: " + elapsedTime.toFixed(2) + " seconds";

    }


    function findxy(res, e) {
        if (res == 'down') {
            prevX = currX;
            prevY = currY;
            currX = e.clientX - canvas.offsetLeft;
            currY = e.clientY - canvas.offsetTop;

            if(!timerFlag) {
              timerFuncVar = setInterval(updateTime, 100);
              timerFlag = true;
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
                distanceSum = distanceSum + (0.027 * Math.sqrt(Math.pow((currX-prevX), 2)+Math.pow((currY-prevY), 2)));
                document.getElementById("distance").innerHTML = "Distance: " + distanceSum.toFixed(2) + " cm";
            }
        }
    }
    </script>
    </head>
    <body onload="init()">
        <canvas id="can" width="1536" height="648" style="position:absolute;top:10%;left:10%; border:2px solid;"></canvas>
        <img id="canvasimg" style="position:absolute;top:10%;left:52%;" style="display:none;">
        <button class="button clear" onclick="erase()">Clear</button>
        <button class="button submit" onclick="submit()">Submit</button>
        <div style="position:absolute;top:85%;left:70%;font-family:Helvetica", id="distance">Distance: 0 cm</div>
        <div style="position:absolute;top:90%;left:70%;font-family:Helvetica", id="time">Time: 0 seconds</div>
    </body>