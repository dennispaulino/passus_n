{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'NanoSTIMA')

@section('content_header')
    <h1>NanoSTIMA - BackOffice</h1>
@stop

@section('content')
    
  <div class="container"  style ='background-color: #abebc6 ;'>
      <div class="row"  style ='background-color: #abebc6 ;'>
      <div class="col-md-12 col-xs-4"  style ='background-color: #abebc6;'>
       <div class="pull-right"> 
          {{Form::button('Edit',['onClick'=>'editProfile()'])}}
       </div>
       <br>

      </div>
        
          <div class="panel panel-info" >
            <div class="panel-heading" style ='background-color:  #FFFFFF ;'>
                <h2>{{Auth::user()->name}}</h2>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="http://icons.iconarchive.com/icons/dakirby309/windows-8-metro/256/Folders-OS-User-No-Frame-Metro-icon.png" class="img-circle img-responsive"> </div>
                
                <div class=" col-md-9 col-lg-9 " id="profile-info">   <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                  <dl>
                    <dt>DEPARTMENT:</dt>
                    <dd>Administrator</dd>
                    <dt>HIRE DATE</dt>
                    <dd>11/12/2013</dd>
                    <dt>DATE OF BIRTH</dt>
                       <dd>11/12/2013</dd>
                    <dt>GENDER</dt>
                    <dd>Male</dd>
                  </dl>
                </div>-->
                 <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Department:</td>
                        <td>Programming</td>
                      </tr>
                      <tr>
                        <td>Hire date:</td>
                        <td>06/23/2013</td>
                      </tr>
                      <tr>
                        <td>Date of Birth</td>
                        <td>01/24/1988</td>
                      </tr>
                   
                         <tr>
                             <tr>
                        <td>Gender</td>
                        <td>Female</td>
                      </tr>
                        <tr>
                        <td>Home Address</td>
                        <td>Kathmandu,Nepal</td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><a href="mailto:info@support.com">info@support.com</a></td>
                      </tr>
                        <td>Phone Number</td>
                        <td>123-4567-890(Landline)<br><br>555-4567-890(Mobile)
                        </td>
                           
                      </tr>
                     
                    </tbody>
                  </table>
                  
                  <a href="#" class="btn btn-primary">Change Password</a>
               
                </div>
              </div>
            </div>
              
            
          </div>
           </div>
            
          </div>
        
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


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
   
@stop

@section('js')

    <!-- jQuery 2.2.3 -->
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>

      
      <script>
         function editProfile(){
         $msg = "<form action='#' method='POST' id='profile'> <div class='form-group'> <label for='name'>Name:</label> <input type='text' class='form-control' id='name'> </div><div class='form-group'><label for='number'>CellPhone Number:</label><input type='number' pattern='.{9,15}' required oninvalid='this.setCustomValidity('Zero or minimum 3 characters required')' class='form-control' id='cellphone'></div><input type='hidden' name='_token' value='{{ csrf_token() }}'><button type='submit' class='btn btn-default'>Submit</button></form> ";    
        $.ajax({
               type:'POST',
                success:function(){
                  $("#profile-info").html($msg);
               }
            });
         }
      </script>
      
      
      
      <script type="text/javascript">
jQuery.validator.setDefaults({
    debug: true,
    success: "valid"
});;
</script>

  <script>
  $(document).ready(function(){
    $("#profile").validate({
  rules: {
    cellphone: {
      required: true,
      minlength: 3
    }
  }
});
  });
  </script>

@stop