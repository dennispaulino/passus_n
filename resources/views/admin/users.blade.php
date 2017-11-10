{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'NanoSTIMA')

@section('content_header')
    <h1>NanoSTIMA - BackOffice</h1>
@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css')}}">
    
      
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
ul.ui-autocomplete {
    z-index: 1100;
}

input {
    vertical-align: -2px;
}
    </style>
    
<link rel='stylesheet' href="{{ asset('/fullcalendar.css')}}" />
<link rel='stylesheet' href="{{ asset('/plugins/jQueryUI/jquery-ui.min.css')}}" />
@stop


@section('content')
      

<!--                    //**************************************************************************************************************************************
                    //**********inicialização das variáveis com a informAção daily record dos utilizadores associados ao professional logado***************************
                    //**************************************************************************************************************************************
-->
  
                   
      
                    




 <!--***********************************************************-->
       <!--***************INICIO - Mostrar mensagens sobre operações com falhas ou bem sucedidas que o utilizador executou no WebSite*************-->
       <!--***********************************************************-->
<div class="flash-message" id="flashMessage">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div> <!-- end .flash-message -->
   <!--***********************************************************-->
       <!--***************FIM - Mostrar mensagens sobre operações com falhas ou bem sucedidas que o utilizador executou no WebSite*************-->
       <!--***********************************************************-->
  
  
  
    <div id="idUserPatientSelected"></div>
   <div class="row">
       
       <!--***********************************************************-->
       <!--***************INICIO - Lista dos Utilizadores*************-->
       <!--***********************************************************-->
        <div class=" col-sm-5 col-lg-3"> 
            
                     
            <h3 >Users  </h3>
            
            
                <div id="list" >
                    

                    <ul style="width: 200px; height: 400px; overflow-x: hidden;overflow-y: auto;" >
                   @if(count($data['usersPatients'])!=0)
                        @foreach ( $data['usersPatients'] as $user)
                  
                    
                    <li>  
                       
                       <?php echo(' <a id="statistics_{{$user->idUserPatient}}" title="Click on user list to change statistics information" class="list-group-item"                      
                           href="#"   onClick="statisticsUserSelected(\''.str_replace("'", "\\'", $user->idUserPatient).'\', \''.str_replace("'", "\\'", $data['usersPatientsEmail'][$user->idUserPatient]).'\')">'.$user->idUserPatient.'</a>');
 
                   ?>
                    </li>
                    
                   
                   
                    
                    @endforeach
                 @endif
              

                   </ul> 
                </div>
          
            
               <!--***********************************************************-->
       <!--***************INICIO - Botão e formulário para adicionar Utilizadores*************-->
       <!--***********************************************************-->
       
           
            <div id="dialog-form" title="Search">
                <div class="ui-widget">
                    
			  <form id="addUserForm" action="{{ asset('admin/adduser')}}" method="get">
                    <label for="emails_AddUser" >Emails : </label>
                    <input id="emails_AddUser"  name="emails_AddUser"/>
                </form>
                </div>
            </div>
                
            {{Form::button('Add user',['onClick'=>'addUser()','id'=>'open-search','class'=>'btn btn-primary'])}}
            <div id="resultSearchUser">
                
            </div>
            
         
            
               <!--***********************************************************-->
       <!--***************FIM - Botão e formulário para adicionar Utilizadores*************-->
       <!--***********************************************************-->
       
            
        </div>
       <!--***********************************************************-->
       <!--***************FIM - Lista dos Utilizadores*************-->
       <!--***********************************************************-->
       
       
       
       <!--********************************************************************************************************************************-->
       <!--***************INICIO - Detalhes (dados, estatisticas - gráficos, avisos, histórico sobre os  Utilizadores)************************************-->
       <!--********************************************************************************************************************************-->
       
       <div id="userContent" class="col-sm-7 col-lg-9">
            
           <h3 id ="userIdH3"> User : </h3>
        


            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#statistic">Statistics</a></li>
<!--                <li><a data-toggle="tab" href="#personalinformation">Personal Information</a></li>-->
                <li><a data-toggle="tab" href="#warning">Warnings</a></li>
                <li><a data-toggle="tab" href="#history">History</a></li>
            </ul>

            <div class="tab-content">
              
                
            <!--***********************************************************-->
            <!--***************INICIO - Dados dos Utilizadores*************-->
            <!--***********************************************************-->
            
                <div id="personalinformation" class="tab-pane fade">
                <h3>Menu 1</h3>
                <p>Some content in menu 1.</p>
                </div>
            
             <!--***********************************************************-->
            <!--***************FIM - Dados dos Utilizadores*************-->
            <!--***********************************************************-->
            
             <!--***********************************************************-->
            <!--***************INICIO - Estatísticas dos Utilizadores*************-->
            <!--***********************************************************-->
            
                <div id="statistic" class="tab-pane fade in active">
        
                <!-- Flot Charts based on user statistics -->
               
                  
                                <!--
                ************************************************************
                ******************************INICIO************************
                **************Calendar about user´s tasks*******************
                ************************************************************-->
                
                
                
                      <!-- Line chart -->
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <i class="fa fa-bar-chart-o"></i>

                          <h3 class="box-title">Calendar</h3>

                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                          
                          </div>
                        </div>
                        <div class="box-body">
                            <div id='calendar'> 
                           
                                               </div>
                        
                        </div>
                        <!-- /.box-body-->
                      </div>
                   
                                <!--
                ************************************************************
                ******************************FIM************************
                **************Calendar about user´s tasks*******************
                ************************************************************-->


                
                
                

                  <div class="row">
                    <div class="col-md-8">
                    

                   
                      <!-- Bar chart -->
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <i class="fa fa-bar-chart-o"></i>

                          <h3 class="box-title">Distance walked chart (kilometers)</h3>

                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                          
                          </div>
                        </div>
                        <div class="box-body">
                          <div id="bar-chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body-->
                      </div>
                      <!-- /.box -->
                    </div>
                     
                      
                      <div class="col-md-4">
                    <!-- Donut chart -->
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <i class="fa fa-bar-chart-o"></i>

                          <h3 class="box-title">Tasks success chart</h3>

                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                        </div>
                        <div class="box-body">
                          <div id="donut-chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body-->
                      </div>
                      <!-- /.box -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
             

                </div>
            
    <!-- /.content -->

                <!--***********************************************************-->
            <!--***************FIM - Estatísticas do Utilizador*************-->
            <!--***********************************************************-->

            <!--***********************************************************-->
            <!--***************Inicio - Warning do Utilizador*************-->
            <!--***********************************************************-->
            <div id="warning" class="tab-pane fade">
                <h3>Latests Warnings</h3>

                <div class="box box-primary">
                     <div class="box-header with-border">

                              <i class="ion ion-alert-circled"></i>



                       <h3 class="box-title">Warnings</h3>


                     </div>
                     <div class="box-body">
                <!-- Table row -->

                            <table class="table table-striped" >
                              <thead>
                              <tr>
                                <th width="80%">Content</th>
                                <th width="13%">Date</th>
                                <th >Action</th>
                              </tr>
                              </thead>
                              <tbody>

                               <tr >

                                    <td width="80%" style="color:orange" > The user with the id 1 missed his objective of walking 1 hour  </td>
                                    <td width="13%">03/02/2017</td>
                                    <td style>{{Form::button('Send Message',['onClick'=>'#'])}}</td>
                              </tr>
                               <tr>

                                    <td width="80%" style="color:red"> The user with the id 4 send an emergency notification at 18:07 01/02/2017 on location  long : -4.09224 lat: 31.029382.  <br> Click to see on the map.   </td>
                                    <td width="13%">03/02/2017</td>
                                    <td >{{Form::button('Send Message',['onClick'=>'#'])}}</td>
                              </tr>

                              </tbody>
                            </table>
                       </div>
                          <!-- /.col -->
                </div>
     
            </div>
            
            
            
            
              <!--***********************************************************-->
            <!--***************FIM - Warning do Utilizador*************-->
            <!--***********************************************************-->

              <!--***********************************************************-->
            <!--***************Inicio - Histórico do Utilizador*************-->
            <!--***********************************************************-->
             <div id="history" class="tab-pane fade">
              
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#dailyrec">Daily Records</a></li>
                       <li><a data-toggle="tab" href="#walkrec">WalkRecords</a></li>

                </ul>

                <div class="tab-content">
                    <div id="dailyrec" class="tab-pane fade  active in">
                       <div class="list-group">
                            <input type="text" id="inputDailyRecordSearch" onkeyup="SearchInHistoryTab('inputDailyRecordSearch','dailyrecUL')" placeholder="Search for daily records..."/>

                                <ul id="dailyrecUL" class="historylist">
                               
                                 
                                </ul> 
                       
                        </div>
                        
                    </div>
                    
                    <div id="walkrec" class="tab-pane fade">
                        <div class="list-group">
                            <div class="row"> 
                                 <div class="col-sm-4 col-lg-3"> 
                            <input type="text" id="inputWalkRecordSearch" onkeyup="SearchInHistoryTab('inputWalkRecordSearch','walkrecUL')" placeholder="Search for walk records..." />
                                </div>
                                <div class="col-sm-4 col-lg-5"> 
                            <button  onclick="descendHistoryList()">Date descending</button> 
                            
                             <button   onclick="ascendHistoryList()">Date ascending</button> 
                             </div>
                               </div>
                             </div>
                             <ul id="walkrecUL" class="historylist">
                               
                                   
                             </ul> 
                             
                             
                            
                        </div>
                        
                    </div>
                </div>
                 
            </div>
             <!--***********************************************************-->
            <!--***************FIM - Histórico do Utilizador*************-->
            <!--***********************************************************-->
        </div>

     </div>
 </div>
       
   
       <!--********************************************************************************************************************************-->
       <!--*************** FIM - Detalhes (dados, estatisticas - gráficos, avisos sobre os  Utilizadores)************************************-->
       <!--********************************************************************************************************************************-->
       
  
   
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>  
    
  <footer class="main-footer">
    <div>
        <b>Version 1.0</b>
    </div>
    <strong>NanoSTIMA 2017</strong>
  </footer>

<!-- ./wrapper -->





@stop


@section('js')

    <!-- jQuery 2.2.3 -->
<script src=" {{asset('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

<script src="{{ asset('/plugins/jQueryUI/jquery-ui.min.js')}}"></script>

<script src="{{asset('/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->

<!-- FastClick -->
<script src="{{ asset('/plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/dist/js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('/dist/js/demo.js')}}"></script>
<!-- FLOT CHARTS -->
<script src="{{ asset('/plugins/flot/jquery.flot.min.js')}} "></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{ asset('/plugins/flot/jquery.flot.resize.min.js')}}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{ asset('/plugins/flot/jquery.flot.pie.min.js')}}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{ asset('/plugins/flot/jquery.flot.categories.min.js')}}"></script>
<script src="{{ asset('/plugins/flot/jquery.flot.time.js')}}"></script>
  
<script type="text/javascript" src="/plugins/flot/jshashtable-2.1.js"></script>    
<script type="text/javascript" src="/plugins/flot/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="/plugins/flot/jquery.flot.symbol.js"></script>
<script type="text/javascript" src="/plugins/flot/jquery.flot.axislabels.js"></script>

<script src="{{ asset('/lib-full_calendar/moment.min.js')}}"></script>
<script src="{{ asset('/lib-full_calendar/gcal.js')}}"></script>
<script src="{{ asset('/dist/js/fullcalendar.min.js')}}"></script>

<!-- Page script -->
    <script> console.log('Hi!'); </script>
    <!-- jQuery 2.2.3 -->

    
      
<!-- Page script -->
<script>
  


  /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + "<br>"
        + Math.round(series.percent) + "%</div>";
  }


//               INICIO - Botão e formulário para adicionar Utilizadores -SCRIPT************************************************************************-->
         function addUser(){
          
                            
                   $("#dialog-form").dialog('open');
                   
        }
  
            //<!--*********FIM - Botão e formulário para adicionar Utilizadores -SCRIPT*************-->***********************************************-->
  
        function statisticsUserSelected(idUser,email)
        {
            var idUserPatientSelected=idUser;

         
         if(idUserPatientSelected!==0)
         {
            
      


       document.getElementById("userIdH3").innerHTML ="User : "+ idUserPatientSelected;
        
    
    
    
    
        //HISTORY TAB  - WalkRecords
    
        historyTabWalkRecords(idUserPatientSelected);
     
     
         //HISTORY TAB  - DailyRecords
    
        historyTabDailyRecords(idUserPatientSelected);
     
    
     
     /*********************************************************************
     ***************INICIO Calendário - Google Calendar Embbed*************
     *********************************************************************/
        if(~email.indexOf('@gmail.com'))// verifica se o email que o utilizador possui pertence à google, senão pertencer não é possível utilizar o google calendar
        { 
         document.getElementById("calendar").innerHTML  ='<iframe src="https://calendar.google.com/calendar/embed?src='+email+'&ctz=Europe/Lisbon" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>';
        }
        else
        {
          document.getElementById("calendar").innerHTML  ="<h3>It is not possible to access this user 'Google Calendar' account, because this user doesn´t have an gmail account associated!</h3>";
          }
          
          
          /* **
          ******************************************************************
     ***************FIM Calendário - Google Calendar Embbed*************
     *********************************************************************/
    
    /*
         * 
         *    *********************************
                  *
                  * *********************************
                  *  *********************************
                  *   **************
                  *      *********************************
                  *
                  * *********************************
                  *  *********************************
                  *   **************
                  *      *********************************
                  *
                  * *********************************
                  *  *********************************
                  *   **************
                  *   

        AQUI É QUE VOU "PUXAR" A INFORMAÇÃO DO ARRAY DATA[ALLDATAUSER] PASSADA NO CONTROLLER
                  DEPOIS VOU APRESENTAR ESSA INFORMAÇÃO DAS ESTATISTICAS
                  *********************************
                  *
                  * *********************************
                  *  *********************************
                  *   ********************************* ********************************* *********************************




        */


             //alert(js_array[idUserPatientSelected][i].date);


          /*
           * BAR CHART
           * ---------
           */


        barChartLast7DistanceWalked(idUserPatientSelected);
          /*
           * DONUT CHART - Gráfico que mostra as tarefas cumpridas do utilizador
           * -----------
           */

          var donutData = [
            {label: "Accomplished", data: 30, color: "#00DD00"},
            {label: "Not Done", data: 20, color: "#ffff4d"},
            {label: "Failed", data: 50, color: "#FF4500"}
          ];



          $.plot("#donut-chart", donutData, {
            series: {
              pie: {
                show: true,
                radius: 1,
                innerRadius: 0.5,
                label: {
                  show: true,
                  radius: 2 / 3,
                  formatter: labelFormatter,
                  threshold: 0.1
                }

              }
            },
            legend: {
              show: false
            }
          });
          /*
           * END DONUT CHART
           */

         }


        }
  



         function barChartLast7DistanceWalked(idUserPatientSelected)
         {/* INICIO barChartLast7DistanceWalked */
           var todayDate = new Date();
              var todayYear = todayDate.getFullYear();


          //Present in the graph the 7 seven days before in the following format ("dd/mm"), full format is ("dd/MM/yyyy)
          var actualDay= returnDateBeforeDays(0);
          var actualDayFullFormat=returnDateBeforeDaysFormatYYYYMMDD(0);
          var oneDayBefore = returnDateBeforeDays(1);
          var oneDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(1);
          var twoDayBefore = returnDateBeforeDays(2);
          var twoDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(2);
          var threeDayBefore = returnDateBeforeDays(3);
          var threeDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(3);
          var fourDayBefore = returnDateBeforeDays(4);
          var fourDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(4);
          var fiveDayBefore = returnDateBeforeDays(5);
          var fiveDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(5);
          var sixDayBefore = returnDateBeforeDays(6);
          var sixDayBeforeFullFormat=returnDateBeforeDaysFormatYYYYMMDD(6);


           var actualDayDistance = 0 ;
          var oneDayBeforeDistance = 0 ;
          var twoDayBeforeDistance  = 0 ;
          var threeDayBeforeDistance =0 ;
          var fourDayBeforeDistance  = 0 ;
          var fiveDayBeforeDistance  = 0 ;
          var sixDayBeforeDistance  = 0 ;


            var arrayDailyRec=<?php  echo json_encode($data['usersPatientsAllDailyRec']);?>;
            var arrayStep=<?php  echo json_encode($data['usersPatientsAllStep']);?>;

                if(arrayDailyRec.hasOwnProperty(idUserPatientSelected))
                {

          for(var i=0;i<arrayDailyRec[idUserPatientSelected].length;i++)
          {

              var idDailyRecAux=arrayDailyRec[idUserPatientSelected][i].idDailyRec;

               if(arrayStep[idUserPatientSelected]!=null ) 
               {
                    if(arrayStep[idUserPatientSelected][idDailyRecAux]!=null)
                       {


                             switch(arrayDailyRec[idUserPatientSelected][i].date) 
                                 {
                              case actualDayFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  actualDayDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              case oneDayBeforeFullFormat:
                               for(var j=0;j<arrayStep[idUserPatientSelected][idDailyRecAux].length;j++)
                                  {
                                  oneDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }

                                  break;
                              case twoDayBeforeFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  twoDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              case threeDayBeforeFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  threeDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              case fourDayBeforeFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  fourDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              case fiveDayBeforeFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  fiveDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              case sixDayBeforeFullFormat:
                              for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {
                                  sixDayBeforeDistance+=parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }
                                  break;
                              default:

                                } 
                        }





                    }
                }
            }
            
          var bar_data = {
            data: [[sixDayBefore, sixDayBeforeDistance/1000], [fiveDayBefore, fiveDayBeforeDistance/1000], [fourDayBefore, fourDayBeforeDistance/1000], [threeDayBefore, threeDayBeforeDistance/1000], [twoDayBefore, twoDayBeforeDistance/1000], [oneDayBefore, oneDayBeforeDistance/1000],[actualDay, actualDayDistance/1000]],
            color: "#3c8dbc"
          };



          $.plot("#bar-chart", [bar_data], {
            grid: {
              borderWidth: 0.5,
              borderColor: "#f3f3f3",
              tickColor: "#f3f3f3"
            },
            series: {
              bars: {
                show: true,
                barWidth: 0.5,
                align: "center"
              }
            },
            xaxis: {
              mode: "categories",
              tickLength: 0,
          min:0
            
            },
            yaxis: {
              min:0
               }
          });
         
    
    
    
                /* FIM barChartLast7DistanceWalked */


    
             
         }
         
         
         
         
         
         function historyTabWalkRecords(idUserPatientSelected)
         {
              
            var arrayWalkRec=<?php  echo json_encode($data['usersPatientsAllWalkRec']);?>;
             var arrayStepWalkRec=<?php  echo json_encode($data['usersPatientsAllStepbasedOnWalkRec']);?>;
            var ulHTMLCode="";
         
         
            if(arrayWalkRec.hasOwnProperty(idUserPatientSelected))
               {
                   for(var i=0;i<arrayWalkRec[idUserPatientSelected].length;i++)
                     {
                             
                        var countStep=0;
                        if(arrayStepWalkRec[idUserPatientSelected].hasOwnProperty(arrayWalkRec[idUserPatientSelected][i].idWalkRec))
                          {        
                            for(var j=0;j<arrayStepWalkRec[idUserPatientSelected][arrayWalkRec[idUserPatientSelected][i].idWalkRec].length;j++)
                            {

                                countStep+= parseFloat(arrayStepWalkRec[idUserPatientSelected][arrayWalkRec[idUserPatientSelected][i].idWalkRec][j].distance);
                            }
                          }
                             
                        countStep=round(countStep,1);
                        var walkLength=(new Date((new Date(arrayWalkRec[idUserPatientSelected][i].dateEnd)).getTime()-(new Date(arrayWalkRec[idUserPatientSelected][i].dateStart)).getTime())).toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");;
                              //ulHTMLCode += "<li> <a href='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' onclick='toogleWalkRecPainLevelChart("+idUserPatientSelected+","+i+","+countStep+")' data-toggle='collapse'>   <i class='fa fa-calendar'> </i> &nbsp; <span class='datestart'>" + arrayWalkRec[idUserPatientSelected][i].dateStart+" </span> &nbsp;    <i class='fa fa-clock-o'> </i> " + walkLength+ "&nbsp; Distance Step (meters): " + countStep+"</a><div id='historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' class='collapse' ><div class='box box-primary'>                         <div class='box-header with-border'>                           <i class='fa fa-bar-chart-o'></i>                            <h3 class='box-title'>Pain Level and Distance chart</h3>                                            </div>                         <div class='box-body'>           <div class='row'>  <div class='col-lg-8 col-md-8 col-xs-6'>                <div id='historytabwalkrecid2_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' style='height: 300px;width: 500px;'></div>       </div> <div class='col-lg-4 col-md-4 col-xs-6'>  <form>   <div class='checkboxes'>     <label for='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"_painlevel'><input type='checkbox' checked id='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"_painlevel'    onclick='toogleWalkRecPainLevelChart("+idUserPatientSelected+","+i+","+countStep+")' /> <span>Pain Level</span></label>    <br/>   <label for='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"_distance''><input type='checkbox' id='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"_distance'  onclick='toogleWalkRecPainLevelChart("+idUserPatientSelected+","+i+","+countStep+")' /> <span>Distance</span></label> <br/>  </div> </form>   </div> </div>                  </div>                                           </div></div></li>";
                          
                          ulHTMLCode += "<li> <a href='#historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' onclick='toogleWalkRecPainLevelChart("+idUserPatientSelected+","+i+","+countStep+")' data-toggle='collapse'>   <i class='fa fa-calendar'> </i> &nbsp; <span class='datestart'>" + arrayWalkRec[idUserPatientSelected][i].dateStart+" </span> &nbsp;    <i class='fa fa-clock-o'> </i> " + walkLength+ "&nbsp; Distance Step (meters): " + countStep+"</a><div id='historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' class='collapse' ><div class='box box-primary'>                         <div class='box-header with-border'>                           <i class='fa fa-bar-chart-o'></i>                            <h3 class='box-title'>Pain Level and Distance chart</h3>                                            </div>                         <div class='box-body'>           <div class='row'>  <div class='col-lg-8 col-md-8 col-xs-6'>                <div id='historytabwalkrecid2_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"' style='height: 300px;width: 500px;'></div>       </div> <div class='col-lg-4 col-md-4 col-xs-6' id='historytabwalkrecid_"+arrayWalkRec[idUserPatientSelected][i].idWalkRec+"_walkinfo'>  </div>                  </div>                                          </div> </div></div></li>";
                     
                     }  
                     
               
               }

             document.getElementById("walkrecUL").innerHTML =ulHTMLCode;

    
     
         }
         
         function toogleWalkRecPainLevelChart(idUser,idWalkRecArrayIndex,countStep)
         {
            
            var arrayWalkRec=<?php  echo json_encode($data['usersPatientsAllWalkRec']);?>;
            
            var arrayPainLevelbasedOnWalkRec=<?php  echo json_encode($data['usersPatientsAllPainLevelbasedOnWalkRec']);?>;
            var arrayPainLevelChart=[];
            
            var arrayPauseWalkRec=<?php  echo json_encode($data['usersPatientsAllPausebasedOnWalkRec']);?>;
         
        

             var timeWalkedWithPauses=(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateEnd)).getTime()-(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime();
             var timeWalkedWithoutPauses=timeWalkedWithPauses;
             
             var stringPausesInfo="";
        
        
        
              if(arrayPainLevelbasedOnWalkRec[idUser].hasOwnProperty(arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec))
            {
                  
                for(var i=0;i<arrayPainLevelbasedOnWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec].length;i++)
                {
                        
                var dateFromBeginningPainLevel=(new Date(arrayPainLevelbasedOnWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateStart)).getTime()-(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime();
                
   
                 arrayPainLevelChart.push([dateFromBeginningPainLevel, arrayPainLevelbasedOnWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].painState]);
                }
    
            }
                      
              
              
             if(arrayPauseWalkRec[idUser].hasOwnProperty(arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec))
            {
                if(arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec].length==0)
                {
                    stringPausesInfo+= "<p>Pauses: None</p>"; 
                }
                else{
                 stringPausesInfo+= "<p>Pauses: </p>";
                for(var i=0;i<arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec].length;i++)
                {
                    var pauseDateStartFromBeginning=stringToHHMMSS(parseInt((new Date(arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateStart)).getTime()-(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime()));
                   //FromBeginning
                    var pauseDateEndFromBeginning=stringToHHMMSS(parseInt((new Date(arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateEnd)).getTime()-(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime()));
                    stringPausesInfo+= "<p style='padding-left:3em'>Start: "+ pauseDateStartFromBeginning +"  End:"+pauseDateEndFromBeginning+"</p>";
                    
                   var pauseDiff=(new Date(arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateEnd)).getTime()-(new Date(arrayPauseWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateStart)).getTime();
                  timeWalkedWithoutPauses-=parseInt(pauseDiff);
                }
                }
            }
              
                         
            var arrayStepWalkRec=<?php  echo json_encode($data['usersPatientsAllStepbasedOnWalkRec']);?>;
          
            var arrayStepTime=[];

        
                    
            if(arrayStepWalkRec[idUser].hasOwnProperty(arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec))
              {     
                  var totalWalkDistance=0;
                for(var i=0;i <arrayStepWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec].length;i++)
                {
                      var dateFromBeginningDistance=(new Date(arrayStepWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].dateStart)).getTime()-(new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime();
                
   
                   totalWalkDistance+=parseInt(arrayStepWalkRec[idUser][arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec][i].distance);
                   arrayStepTime.push([dateFromBeginningDistance,totalWalkDistance]);
                }
            }

                
                 
  
             document.getElementById("historytabwalkrecid2_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec).innerHTML ="";
             var textWalkInfo="<p>Time walked without pauses: "+stringToHHMMSS(timeWalkedWithoutPauses/1000)+"</p> <p>"+ stringPausesInfo +"</p>";
              //"+timeWalkedWithoutPauses+
             document.getElementById("historytabwalkrecid_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec+"_walkinfo").innerHTML =textWalkInfo;
               // if(document.getElementById("historytabwalkrecid_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec+"_distance").checked)
                var dataset = [
                 {
                     label: "Pain Level",
                     data: arrayPainLevelChart,
                     yaxis: 1,
                     color: "#FF0000",
                     points: { symbol: "circle", fillColor: "#FF0000", show: true },
                     lines: { show: true }
                 }, {
                     label: "Total Distance",
                     data: arrayStepTime,
                     yaxis: 2,
                     color: "#319400",
                     points: { symbol: "diamond", fillColor: "#319400", show: true },
                     lines: { show: true }
                 }
             ];


             var options = {
                  xaxis: { mode: "time",
                           timezone: "browser",
                           min: 0,
                           max: timeWalkedWithPauses
               },
                 yaxes: [{
                     min:1,
                     max:5,
                     ticksize:1,
                     position: "left",
                     color: "white",
                     axisLabel: "Pain Level",
                     axisLabelUseCanvas: true,
                     axisLabelFontSizePixels: 12,
                     axisLabelFontFamily: 'Verdana, Arial'
                 }, {
                     min:0,
                     max:countStep,
                     alignTicksWithAxis: 2,
                     position: "right",
                     color: "white",
                     axisLabel: "Distance",
                     axisLabelUseCanvas: true,
                     axisLabelFontSizePixels: 12,
                     axisLabelFontFamily: 'Verdana, Arial'
                 }
             ],
                 legend: {
                     noColumns: 0,
                     labelFormatter: function (label, series) {
                         return "<font color=\"white\">" + label + "</font>";
                     },            
                     backgroundColor: "#000",
                     backgroundOpacity: 0.9,
                     labelBoxBorderColor: "#000000",
                     position: "nw"
                 },
                 grid: {
                     hoverable: true,
                     borderWidth: 3,
                     mouseActiveRadius: 50,
                     backgroundColor: { colors: ["#ffffff", "#EDF5FF"] },
                     axisMargin: 20
                 }
             };
                             setTimeout(function(){


             //                       $.plot("#historytabwalkrecid2_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec, [dataset],{
             //                                    yaxis: {
             //                                        min:1
             //                                    },
             //                                    xaxis: {  mode: "time",
             //                                         timezone: "browser",
             //                                        min: (new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateStart)).getTime(),
             //                                            max: (new Date(arrayWalkRec[idUser][idWalkRecArrayIndex].dateEnd)).getTime()
             //                                             },
             //                                    "lines": {"show": "true"},
             //                                    "points": {"show": "true"},
             //                                    clickable:true,hoverable: true
             //                            });
                   
                                            $.plot($("#historytabwalkrecid2_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec), dataset, options);        
                                 $("#historytabwalkrecid2_"+arrayWalkRec[idUser][idWalkRecArrayIndex].idWalkRec).UseTooltip();
                             }, 500);
    
                    }
         
         
         function historyTabDailyRecords(idUserPatientSelected)
         {
              var arrayDailyRec=<?php  echo json_encode($data['usersPatientsAllDailyRec']);?>;
               var arrayStep=<?php  echo json_encode($data['usersPatientsAllStep']);?>;
            var ulHTMLCode="";
            if(!isEmpty(arrayDailyRec))
            {
             if(arrayDailyRec.hasOwnProperty(idUserPatientSelected))
               {
                   for(var i=0;i<arrayDailyRec[idUserPatientSelected].length;i++)
                     {
                        var countStep=0;
                        if(!isEmpty(arrayStep))
                        {
                          if(arrayStep[idUserPatientSelected].hasOwnProperty(arrayDailyRec[idUserPatientSelected][i].idDailyRec))
                            {  

                                  for(var j=0;j<arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec].length;j++)
                                  {

                                      countStep+= parseFloat(arrayStep[idUserPatientSelected][arrayDailyRec[idUserPatientSelected][i].idDailyRec][j].distance);
                                  }

                            }
                            countStep=round(countStep,1);
                           ulHTMLCode += "<li> <a href='#historytabdailyrecid_"+arrayDailyRec[idUserPatientSelected][i].idDailyRec+"'>Date Start: " + arrayDailyRec[idUserPatientSelected][i].date+"  Distance GPS (meters): " + round(arrayDailyRec[idUserPatientSelected][i].distanceGPS,1)+"  Distance from steps (meters): " + countStep+"</a><div id='historytabwalkrecid_"+arrayDailyRec[idUserPatientSelected][i].idDailyRec+"' class='collapse' ><div class='box box-primary'>                         <div class='box-header with-border'>                           <i class='fa fa-bar-chart-o'></i>                            <h3 class='box-title'>Pain Level chart</h3>                                            </div>                         <div class='box-body'>                           <div id='historytabdailyrecid2_"+arrayDailyRec[idUserPatientSelected][i].idDailyRec+"' style='height: 300px;'></div>                         </div>                                           </div></div></li>";
                         }  
                      }
                     

               }
               }

             document.getElementById("dailyrecUL").innerHTML =ulHTMLCode;

    
         }

            function returnDateBeforeDays( days){ 
              var date = new Date();
              var last = new Date(date.getTime() - (days * 1000 * 24 * 60 * 60 ));
              var day =last.getDate();
              var month=last.getMonth()+1;
              if(day<10) {
                 day='0'+day;
             } 

             if(month<10) {
                 month='0'+month;
             } 

            var  finaldate = day+'/'+month;
             return finaldate;
           }


            function returnDateBeforeDaysFormatYYYYMMDD( days){ 
              var date = new Date();
              var last = new Date(date.getTime() - (days * 1000 * 24 * 60 * 60 ));
              var day =last.getDate();
              var month=last.getMonth()+1;
                var year=last.getFullYear();
              if(day<10) {
                 day='0'+day;
             } 

             if(month<10) {
                 month='0'+month;
             } 

            var  finaldate = year+'-'+month+'-'+day;
             return finaldate;
           }



           function stringToDate(_date,_format,_delimiter)
        {
          var formatLowerCase=_format.toLowerCase();
          var formatItems=formatLowerCase.split(_delimiter);
          var dateItems=_date.split(_delimiter);
          var monthIndex=formatItems.indexOf("mm");
          var dayIndex=formatItems.indexOf("dd");
          var yearIndex=formatItems.indexOf("yyyy");
          var month=parseInt(dateItems[monthIndex]);
          month-=1;
          var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
          return formatedDate;
        }
        function stringToHHMMSS(timeInSec) {
    var sec_num = parseInt(timeInSec, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
    }
        function round(value, precision)
        {
        var multiplier = Math.pow(10, precision || 0);
        return Math.round(value * multiplier) / multiplier;
        }       
        function isEmpty(str) {
        return (!str || 0 === str.length);
    }
        
        
        
//        
//         //adicionar utilizador
            $(function() {
            var userEmails = <?php  echo json_encode($data['usersPatientsEmail']);?>;
            var userPatients = <?php  echo json_encode($data['usersPatients']);?>;
    
        
    
                //remover do array usersemails os emails que já estão associados a este profissional de saude logado
               for (keyUserPatient of userPatients)
                {
                   delete userEmails[keyUserPatient.idUserPatient];
             
                }

                
                
                $( "#emails_AddUser" ).autocomplete({
                    source: Object.values(userEmails)
                    });

                $( "#dialog-form" ).dialog({
                    autoOpen: false,
                    height: 300,
                    width: 350,
                    modal: true,
                    buttons: {
                        "Save value": function() {
                            if ($("#emails_AddUser").val() != '') {
                                $( this ).dialog( "close" );
                            }
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    close: function() {
                        if(!isEmpty($("#emails_AddUser").val()))
                      document.getElementById('addUserForm').submit();
                    }             
                });
 
            });
        
        
       
        //função para fazer desaparecer as mensagens de alerta, vindas dos controllers
        
         setTimeout(function() {
            $('#flashMessage').fadeOut('fast');
            }, 10000);
            
            
            
           

function descendingSort(ulTag){
var ul=document.getElementById(ulTag);
        var new_ul = ul.cloneNode(false);


    // Add all lis to an array
    var lis = [];
    for(var i = ul.childNodes.length; i--;){
        if(ul.childNodes[i].nodeName === 'LI')
            lis.push(ul.childNodes[i]);
    }


    ul.childNodes[2].innerHTML.split("Date Start:")[1].split(";")[0];
    // Sort the lis in descending order
  
    lis.sort(function(a, b){
       return parseInt(b.childNodes[0].data.split(";")[0] , 10) - 
              parseInt(a.childNodes[0].data.split(";")[0] , 10);
    });


    // Add them into the ul in order
    for(var i = 0; i < lis.length; i++)
        new_ul.appendChild(lis[i]);
    
    ul.parentNode.replaceChild(new_ul, ul);
}


function ascendingSort(ulTag){
    var ul=document.getElementById(ulTag);
var new_ul = ul.cloneNode(false);

    // Add all lis to an array
    var lis = [];
    for(var i = ul.childNodes.length; i--;){
        if(ul.childNodes[i].nodeName === 'LI')
        {    lis.push(ul.childNodes[i]);
       
            }
        
    }

    // Sort the lis in descending order
    lis.sort(function(a, b){
       return parseInt(b.childNodes[0].data.split("Date Start:")[1].split(";")[0] , 10) - 
              parseInt(a.childNodes[0].data.split("Date Start:")[1].split(";")[0] , 10);
    });

    // Add them into the ul in order
    for(var i = 0; i < lis.length; i++)
        new_ul.appendChild(lis[i]);
    ul.parentNode.replaceChild(new_ul, ul);
    
    
}
        function descendHistoryList() {
  var list, i, switching, b, shouldSwitch;
  list = document.getElementById("walkrecUL");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    b = list.getElementsByTagName("LI");
    //Loop through all list-items:
          
    
    for (i = 0; i < (b.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*check if the next item should
      switch place with the current item:*/
                
     
      if ( b[i].getElementsByTagName('span')[0].innerHTML< b[i+1].getElementsByTagName('span')[0].innerHTML) {
       /*if next item is alphabetically
        lower than current item, mark as a switch
        and break the loop:*/
        shouldSwitch= true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark the switch as done:*/
      b[i].parentNode.insertBefore(b[i + 1], b[i]);
      switching = true;
    }
  }
}    

   function ascendHistoryList() {
  var list, i, switching, b, shouldSwitch;
  

  list = document.getElementById("walkrecUL");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    b = list.getElementsByTagName("LI");
    //Loop through all list-items:
          
    
    for (i = 0; i < (b.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*check if the next item should
      switch place with the current item:*/
    
       
      if ( b[i].getElementsByTagName('span')[0].innerHTML> b[i+1].getElementsByTagName('span')[0].innerHTML) {
        /*if next item is alphabetically
        lower than current item, mark as a switch
        and break the loop:*/
        shouldSwitch= true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark the switch as done:*/
      b[i].parentNode.insertBefore(b[i + 1], b[i]);
      switching = true;
    }
  }
}    
            </script>
              
              
              
              
             
      

<!--
************************************************************
******************************INICIO************************
**************Calendar about user´s tasks*******************
************************************************************-->
<!--<script>
    $('#calendar').fullCalendar({
    dayClick: function() {
       event.backgroundColor = 'yellow';
    },
    
    eventClick: function(event) {
        event.backgroundColor = 'yellow';
        $('#calendar').fullCalendar( 'rerenderEvents' );
    },


    
})
    </script>-->
    
<!--    <script>
    
   

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var events_array = [
        {
        title: 'Test1',
        start: new Date(2012, 8, 20),
        tip: 'Personal tip 1'},
    {
        title: 'Test2',
        start: new Date(2012, 8, 21),
        tip: 'Personal tip 2'}
    ];

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        selectable: true,
        events: events_array,
        eventRender: function(event, element) {
            element.attr('title', event.tip);
        },
         googleCalendarApiKey: 'AIzaSyDC1q37t61SJT8SUcbeboAYV1L6z39tCew',
        events: {
            googleCalendarId: 'dennispaulino08@gmail.com'
        },
        
           select: function(start, end, jsEvent, view) {
         // start contains the date you have selected
         // end contains the end date. 
         // Caution: the end date is exclusive (new since v2).
         var allDay = !start.hasTime && !end.hasTime;
         alert(["Event Start date: " + moment(start).format(),
                "Event End date: " + moment(end).format(),
                "AllDay: " + allDay].join("\n"));
    }
           });

</script>-->





<!--
************************************************************
******************************FIM************************
**************Calendar about user´s tasks*******************
************************************************************-->



<!--
************************************************************
******************************INICIO************************
**************Function for Search Bar in History Tab*******************
************************************************************-->

<script>


function gd(year, month, day) {
    return new Date(year, month - 1, day).getTime();
}

var previousPoint = null, previousLabel = null;

$.fn.UseTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();
                
                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;

                showTooltip(item.pageX, item.pageY, color,
                            "<strong>" + item.series.label + "</strong>"  +
                            " : <strong>" + y + "</strong> ");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

function showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 120,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}

                              
function SearchInHistoryTab(inputID,ulistID) {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById(inputID);
    filter = input.value.toUpperCase();
    ul = document.getElementById(ulistID);
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>

<!--
************************************************************
******************************END************************
**************Function for Search Bar in History Tab*******************
************************************************************-->




<!--
**************************************************************************************************************************************
******************************INICIO**************************************************************************************************
**************Function to load csv from SESSION IN HOSPITAL NANOSTIMA PROGRAM to send the retrieved data to database******************
**************************************************************************************************************************************-->







<!--
**************************************************************************************************************************************
******************************END**************************************************************************************************
**************Function to load csv from SESSION IN HOSPITAL NANOSTIMA PROGRAM to send the retrieved data to database******************
**************************************************************************************************************************************-->









@stop

