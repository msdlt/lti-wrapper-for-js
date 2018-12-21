
    /** Read scores from server */
    $.getJSON("data.json",function(response){
      var obj = response.data;
      if(Array.isArray(obj)){ // did the server return an array?
        // something went wrong, we're meant to receive an object
        if(obj.length===1){ // is the array a single item?
          my_js_scores_object = Object.assign(my_js_scores_object, obj[0]);  //ie adding data from server to existing data
        }else{ // either array was empty, or it has multiple items
          console.warn("server gave no valid scores");
        }
      }else{ // server returned an object already
        my_js_scores_object = Object.assign(my_js_scores_object, obj); // just update our scores
      }
    });
  }; 
  

  /**
   * Write the variable my_js_scores_object to the server.
   */
  var save_scores = function(){
    data=JSON.stringify(my_js_scores_object);
    $.ajax({
      type:        "POST",
      url:         "data.json",
      contentType: "application/json",
      dataType:    "json",
      data:        data,
      error:       function(xhr,opt,er){
        console.log("unable to send scores to server: "+er);
        if(typeof(loginUrl) !== "undefined") {
            alert("You score could not be saved. Your session may have timed out. Click OK to log back in via WebLearn. Your responses on the current case may not have been recorded. Sorry for any inconvenience caused.");
            window.location.href = loginUrl;
        }
      }
    });
