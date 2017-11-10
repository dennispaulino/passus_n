{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'NanoSTIMA')

@section('content_header')
    <h1>NanoSTIMA - BackOffice</h1>
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    
      
   <style type="text/css">
  #list ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}


 #list li {
  font: 200 15px/1.5 Helvetica, Verdana, sans-serif;
  border-bottom: 1px solid #ccc;
}
 
 #list li:last-child {
  border: none;
}
 
 #list li a {
  text-decoration: none;
  color: #000;
  display: block;
  width: 200px;
 
  -webkit-transition: font-size 0.3s ease, background-color 0.3s ease;
  -moz-transition: font-size 0.3s ease, background-color 0.3s ease;
  -o-transition: font-size 0.3s ease, background-color 0.3s ease;
  -ms-transition: font-size 0.3s ease, background-color 0.3s ease;
  transition: font-size 0.3s ease, background-color 0.3s ease;
}
 
 #list li a:hover {
  font-size: 30px;
  background: #f6f6f6;
}

#myInput {
    background-image: url('/css/searchicon.png'); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

.historylist {
    /* Remove default list styling */
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.historylist  li a {
    border: 1px solid #ddd; /* Add a border to all links */
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6; /* Grey background color */
    padding: 12px; /* Add some padding */
    text-decoration: none; /* Remove default text underline */
    font-size: 18px; /* Increase the font-size */
    color: black; /* Add a black text color */
    display: block; /* Make it into a block element to fill the whole list */
}

.historylist  li a.header {
    background-color: #e2e2e2; /* Add a darker background color for headers */
    cursor: default; /* Change cursor style */
}

.historylist  li a:hover:not(.header) {
    background-color: #eee; /* Add a hover effect to all links, except for headers */
}



/* 
Generic Styling, for Desktops/Laptops 
*/
table { 
  width: 100%; 
  border-collapse: collapse; 
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}


ul.ui-autocomplete {
    z-index: 1100;
}


    </style>
    
<link rel='stylesheet' href='/fullcalendar.css' />
<link rel='stylesheet' href="/plugins/jQueryUI/jquery-ui.min.css" />
@stop


@section('content')
<div class="flash-message" id="flashMessage">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div>


<h3>Add CSV file to save data in database </h3>
<div class="row">
      
        <div class=" col-sm-9 col-lg-9"> 
                <input type="file" id="csv-file" name="files"/>
        </div>


      
        <div class=" col-sm-3 col-lg-3"> 
            <form id="formSubmitCSV" name="formSubmitCSV" action="/admin/submitcsv" method="post">
                
                <input type="hidden" id="hiddenCSVSubmitElement" name="csvData"/>
               {{ csrf_field() }}
                
            </form>
            <button style="visibility:hidden; background-color: #4CAF50;" id="submitCSV" onclick="submitCSVData()" >Submit CSV data </button>
        </div>
</div>


<br>
<table id="csvtable" border="1">  </table>


<p style="display:none" id="hiddenCSVSubmitElement"></p>
@stop


@section('js')

    <!-- jQuery 2.2.3 -->
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script>

<!-- Bootstrap 3.3.6 -->
<!--<script src="/bootstrap/js/bootstrap.min.js"></script>-->
<!-- FastClick -->
<script src="/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<!-- FLOT CHARTS -->
<script src="/plugins/flot/jquery.flot.min.js "></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="/plugins/flot/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="/plugins/flot/jquery.flot.pie.min.js"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="/plugins/flot/jquery.flot.categories.min.js"></script>
<script src="/plugins/flot/jquery.flot.time.js"></script>
<script src='/lib-full_calendar/moment.min.js'></script>
<script src='/lib-full_calendar/gcal.js'></script>
<script src='/dist/js/fullcalendar.min.js'></script>

<!-- Page script -->


  
<script lang="javascript" src="dist/js-xlsx-master/dist/xlsx.full.min.js"></script>



<script src="/dist/papaparse.min.js"></script>


<script>
  var myList;
 
  function handleFileSelect(evt) {
    var file = evt.target.files[0];
 
    Papa.parse(file, {
      header: true,
      dynamicTyping: true,
      complete: function(results) {
        myList = results["data"];
        document.getElementById("csvtable").innerHTML ="";
        buildHtmlTable('#csvtable');
        
      }
    });
  }
 
 
 
 
  $(document).ready(function(){
    $("#csv-file").change(handleFileSelect);
  });
  
  

  
// Builds the HTML Table out of myList.
function buildHtmlTable(selector) {

  var columns = addAllColumnHeaders(myList, selector);

  for (var i = 0; i < myList.length; i++) {
    var row$ = $('<tr/>');
    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
      var cellValue = myList[i][columns[colIndex]];
      if (cellValue == null) cellValue = "";
      row$.append($('<td/>').html(cellValue));
    }
    $(selector).append(row$);
    
    
    
    var elementButtonSubmit = document.getElementById("submitCSV");
    elementButtonSubmit.style.visibility = "visible";
    
  }
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records.
function addAllColumnHeaders(myList, selector) {
  var columnSet = [];
  var headerTr$ = $('<tr/>');

  for (var i = 0; i < myList.length; i++) {
    var rowHash = myList[i];
    for (var key in rowHash) {
      if ($.inArray(key, columnSet) == -1) {
        columnSet.push(key);
        headerTr$.append($('<th/>').html(key));
      }
    }
  }
  $(selector).append(headerTr$);

  return columnSet;
}



function submitCSVData()
{
   document.getElementById('hiddenCSVSubmitElement').value = JSON.stringify(myList);
   document.getElementById('formSubmitCSV').submit();
   alert(myList[5]["Data Sessao"]);
    
}
</script>


@stop