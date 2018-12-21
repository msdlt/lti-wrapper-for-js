<h1 class="page-title">Demo App</h1>
<p>
    This app will just get and set JSON.
</p>

<p>
    <a href="javascript:void(0);" id="get">Get JSON</a> &nbsp; 
    <a href="javascript:void(0);" id="set">Set JSON</a>
</p>

<h3>Set Response</h3>
<div id="set_container">Not called Set yet</div>

<h3>Get Response</h3>
<div id="get_container">Not called Get yet</div>

<script>
    $(document).ready(function() {
        $('#get').on('click', get);
        $('#set').on('click', set);
    });
    
    function get() {
        var getUrl = '../data/view.json';
        $.ajax({
            type: "GET",
            url: getUrl,
            cache: false,
            contentType: "application/json",
            error: function(xhr,opt,er){
                console.log("Unable to retrieve data from server: "+er);
            },
            success: function(response) {
                var data = response.data.data;
                console.log(data);
                $('#get_container').text(JSON.stringify(data))
            },
        });
    }
    
    function set() {
        var dummyData = {
            q1: {
                score: 1,
                answers: {
                    a: 'a correct option',
                    b: 'an incorrect option',
                }
            },
            q2: {
                score: 0,
                answers: {
                    a: 'this one is wrong',
                    b: 'this one is also wrong',
                }
            },
            total_score: 1,
            datetime: Date.now(),
        };
        
        var postUrl = '../data/save.json';
        $.ajax({
            type: "POST",
            url: postUrl,
            contentType: "application/json",
            data: JSON.stringify(dummyData),
            error: function(xhr,opt,er){
                console.log("Save Error: " + er);
                $('#set_container').text("Save Error: " + er)
            },
            success: function(response) {
                console.log(response.message);  //response.message = success: timestamp
                $('#set_container').text(response.message)
            },
        });
    }

</script>