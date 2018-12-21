    <div id="wrapper" class="fullheight">
      <div class="overlay"></div>
      <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
        <ul class="nav sidebar-nav">
          <!--
              Any entry in this list with a data-casename attribute will load a case file.
              If there is a data-filename attribute, that file will be loaded (adding .html).
              Otherwise, the link text will be converted to a filename by removing spaces.
              
              Note that the data-casename attribute's value is currently irrelevant, 
              as the case name gets read from the html file itself.
          -->
          
          <li class="sidebar-brand">
            <a href="">
              <i class="fa fa-fw fa-home"></i> 
              NeuroSim
            </a>
          </li>
          <li>
            <h3>Stroke syndromes</h3>
            
          </li>
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-01-left-mca">Case 1</a>
          </li>
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-02-right-mca">Case 2</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-03-venous-thrombosis">Case 3</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-04-right-pica">Case 4</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-05-right-aca">Case 5</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-06-sensory-thalamic-bleed">Case 6</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-07-left-occipital">Case 7</a>
          </li> 
          <li>
            <a href="#" class="case-iscomplete" data-casename="stroke-08-pons">Case 8</a>
          </li> 
 
 
         <li>
            <a href="#" class="" data-casename="" data-filename="fullscreen_imaging">Browse images</a>
          </li> 
 
          
          <li>
            <h3 class="credits-popup"> 
              <i class="fa fa-users fa-lg"></i>
              Credits
            </h3>
          </li>
        </ul>
      </nav>

      <div class="fullheight" id="page-content-wrapper">
        <button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas">
          <span class="hamb-top"></span>
          <span class="hamb-middle"></span>
          <span class="hamb-bottom"></span>
        </button>
<!--
        <div class="container">
          <div class="row">
-->
            <div class="fullheight" id="guts">
              
              
              
              <!-- CONTENT -->
              
              <div class="col-lg-8 col-lg-offset-2 col-sm-12">
              
              <h1 class="page-header">NeuroSim Cases</h1>  
              <p class="lead">
              </p>
                
    <div>
      <h3 > Instructions </h3>
      <ul class="neurosim-instruction-list">
        <li>
          <em>History</em> Read the case history carefully
        </li>
        <li>
          <em>Acuity</em> cover each eye to see what the patient can read
        </li>
        <li>
          <em>Visual fields</em> click to test locations in the visual field
          Black dots indicate they can see it; green means they cannot.
        </li>
        <li>
          <em>Face</em> move the mouse to test eye movements. 
          Hold and drag mouse to shine a pentorch.
          Click on mouth to look in oral cavity.
        </li>
        <li>
          <em>Power and reflexes</em> Bars and numbers indicate MRC grading of each muscle group.
          Each reflex is graded by 0 to 4 + signs. 
        </li>
        <li>
          <em>Dermatomes</em> select pin, cotton wool or tuning fork to test 
          pain, light touch and vibration sensation. 
          Click on the body to test sensation. A black dot indicates 
          normal perception. Green means reduced sensation.
        </li>
        <li>
          <em>Blue buttons</em> Choose the best answer. 
        </li>
        <li>
          <em>Grey buttons</em> select all the answers that apply.
        </li>
      </ul>
      You need to get <b>at least one</b> answer correct to "complete" a case.
    </div>
                
                
              
              </div>
              
              
            </div>
          </div>
        </div>
<!--
      </div>
    </div>
-->
  </body>  
  
  <!-- Menu Toggle Script -->
  <script>
  var loginUrl = "<?php echo $loginUrl; ?>";
  
$(document).ready(function () {
  var trigger = $('.hamburger'),
      overlay = $('.overlay'),
     isClosed = false;

  trigger.click(function () {
      hamburger_cross();      
  });
  /** handle hamburger clicks, switching it between hamburger and cross */
  function hamburger_cross() {
    if (isClosed == true) {          
      hamburger_close();
    } else {   
      hamburger_open();
    }
  }
  /* change hamburger icon and re-show main page */
  function hamburger_close(){
    overlay.hide();
    trigger.removeClass('is-open');
    trigger.addClass('is-closed');
    isClosed = false;
  }
  /** change hamburger icon and grey out the page */
  function hamburger_open(){
    overlay.show();
    trigger.removeClass('is-closed');
    trigger.addClass('is-open');
    isClosed = true;
  }
  /** when click off the menu bar, hide it */
  $('[data-toggle="offcanvas"]').click(function () {
    toggle();
  });  
  /* toggle menu bar */
  function toggle(){
    $('#wrapper').toggleClass('toggled');
  }
  
  /// SANJAY
  $(".navbar > ul > li > a[data-casename]").click(function(e){
    var loadfile = $(e.target).attr("data-filename"); // get data-filename attribute
    var casename = e.target.innerHTML.trim().replace(/ /,"").toLowerCase(); 
    if(loadfile===undefined){
      // remove spaces from the link text, make lower case
      loadfile=casename;
    }
    
    //    casename = $(e.target).attr("data-casename"); // use case name as file name?
    $("#guts").load(loadfile+".html"); // load the html file with that name into "guts".
    // call NeuroSim.new_case to initialise the case data.
    // this is actually redundant, as the case name (and the concomitant
    // pathologies) will now be set up within NeuroSim.initialise_gui_elements()
    // and will take the casename from the case html file's <h1> header tag.
    // 
    // the benefit of this is that the case's name is independent of how the 
    // case html file is loaded. Now, the data-casename attributes in this index 
    // file are not needed.
    //
    ////    NeuroSim.new_case(casename);
    
    // Now initialise any NeuroSim components
    NeuroSim.initialise_gui_element($("#guts"));
    // should specify <h1 data-case='Some disease'>
    hamburger_close();
    toggle();
  });
  $(".credits-popup").click(function(e){
    // display credits in a dialog bootbox. 
    // credits are read from case_snippets.html#.snippet-credits
    bootbox.alert(NeuroSim.case_snippets.find(".snippet-credits").html());
    $('.modal').animate({scrollTop:0}, 500, 'swing'); // ensure at top
  });
  
  hamburger_open();
  toggle();
  
});
  </script>
