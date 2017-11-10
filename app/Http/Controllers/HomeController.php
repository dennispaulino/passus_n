<?php

namespace App\Http\Controllers;

//use App\Http\Requests;
use Request;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Client;
use Auth;
use Debugbar;

class HomeController extends Controller
{
    
    
    
     
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   public function loadxlsx()
    {
        return view('/admin/loadxlsx');
    }
    public function submitcsv()
    {
        if(Request::exists('csvData'))
        {
            
            //falta buscar o id do paciente!!!!!!!
         Debugbar::info(Request::get('csvData')); 
         $arrayJson_FROMCSV_Data =json_decode(Request::get('csvData'));
        
            if($arrayJson_FROMCSV_Data!= null )
            { 
                    if($arrayJson_FROMCSV_Data != FALSE)
                   {        
                        
                        $skipFirstLoop=false;
                            foreach ( $arrayJson_FROMCSV_Data as $rowData)
                            {
                                if (!$skipFirstLoop)
                                {
                                    $skipFirstLoop=true;
                                    continue;
                                    
                                    
                                  if(isset($rowData->{'Data Sessao'}))
                                  {
                                     $dateStart=new DateTime ($rowData->{'Data Sessao'});
                                      Debugbar::info("DateSTart:".$dateStart->format('Y-m-d'));
                                    
                                         
                                      $speed_Session=-1;
                                      if(isset($rowData->velocidade))
                                      {
                                          $speed_Session=$rowData->velocidade;
                                      }
                                      
                                        $elevation_Session=-1;
                                      if(isset($rowData->velocidade))
                                      {
                                          $elevation_Session=$rowData->inclinação;
                                      }
                                   
                                      $id_Session=-1;
                                      
                                        if(isset($rowData->{'Sessao nº'}))
                                        {
                                         $id_Session=$rowData->{'Sessao nº'};
                                        }
                                        
                                       $PSE_5_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 5'}))
                                        {
                                         $PSE_5_min_Session=$rowData->{'Sessao nº'};
                                        }
                                        
                                         $PSE_10_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 10'}))
                                        {
                                         $PSE_10_min_Session=$rowData->{'PSE 10'};
                                        }
                                        
                                        
                                         $PSE_15_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 15'}))
                                        {
                                         $PSE_15_min_Session=$rowData->{'PSE 15'};
                                        }
                                        
                                         $PSE_20_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 20'}))
                                        {
                                         $PSE_20_min_Session=$rowData->{'PSE 20'};
                                        }
                                        
                                        
                                         $PSE_25_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 25'}))
                                        {
                                         $PSE_25_min_Session=$rowData->{'PSE 25'};
                                        }
                                        
                                           
                                         $PSE_30_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 30'}))
                                        {
                                         $PSE_30_min_Session=$rowData->{'PSE 30'};
                                        }
                                        
                                           
                                         $PSE_35_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 35'}))
                                        {
                                         $PSE_35_min_Session=$rowData->{'PSE 35'};
                                        }
                                        
                                          $PSE_40_min_Session=-1;
                                      
                                        if(isset($rowData->{'PSE 40'}))
                                        {
                                         $PSE_40_min_Session=$rowData->{'PSE 40'};
                                        }
                                        
                                        
                                        
                                      
                                   callNanoSTIMAWebservicePOSTWalkRecordSessionUser($dateStart->format('Y-m-d'),'falta por o id',1,$speed_Session,$elevation_Session,$id_Session,$PSE_5_min_Session,$PSE_10_min_Session,$PSE_15_min_Session,$PSE_20_min_Session,$PSE_25_min_Session,$PSE_30_min_Session,$PSE_35_min_Session,$PSE_40_min_Session);
                                   //$idUser,$state,$speed_Session,$elevation_Session,$id_Session,$PSE_5_min_Session,$PSE_10_min_Session,$PSE_15_min_Session,$PSE_20_min_Session,$PSE_25_min_Session,$PSE_30_min_Session,$PSE_35_min_Session,$PSE_40_min_Session)
                                  }
                                }
                                
                                
                                
                                
                            }
                        
                       \Session::flash('alert-success', 'User '.$arrayJson_FROMCSV_Data[2]->velocidade.' was successful added!');
                        return Redirect::to("/admin/loadxlsx");
                   }
             }
        }
        
         
        \Session::flash('alert-warning', 'It was not possible to save the data in the database!');
        
              return Redirect::to("/admin/loadxlsx");
    }
    
    public function index()
    {
        //id do utilizador autenticado
       $userid=Auth::user()->id;
       
        $usersPatientsAllDailyRec = array(); //array para armazenar os dailyrecords de cada paciente que o utilizador Profissional de Saúde logado tem
              
      $usersPatientsAllStep= array();//array para armazenar os Steps de cada paciente que o utilizador Profissional de Saúde logado tem
             
        if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/relationuser.php?idUserProfessional='.$userid)== -1) //se não existir uma  relação entre o profissional de saúde e ALGUM paciente, então retorna 0 senão retorna um array com vários dados dos pacientes (DailyRec, WalkRec, Steps...) 
            {
               $usersPatients=0;
           }
          else
            {
                $usersPatients= $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/relationuser.php?idUserProfessional='.$userid);
              
                foreach ($usersPatients as $userPatientRelationUserInfo)
                {
                  $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient]=0;
                    if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/dailyrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient)!=-1)
                        {
                         
                            $usersPatientsAllDailyRec[$userPatientRelationUserInfo->idUserPatient]=$this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/dailyrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient);

                            //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS STEP......
                            //como cada registo STEP está relacionada com um registo DailyRecord, é armazenado no array multidimensional $usersPatientsAllStep o id do paciente e o id do respectivo dailyrecord. E.g. $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient][$usersPatientsStepsBasedOnDailyRec->idDailyRec]
                         
                            
                         
                            if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&lastDays=7')!=-1)
                            {
                                   $auxStepArray=array();
                                   $auxStepArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&lastDays=7');
                                   foreach ( $auxStepArray as $stepRecord)
                                   {
                                       $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient]+= $stepRecord->distance/1000;

                                       
                                   }
                            }
                        }
                }
            }


       

        
        return view('home')->with('data', ['usersPatients'=>$usersPatients, 'usersPatientsAllStep' => $usersPatientsAllStep]);
    }
     public function adduser()
    {  $userid=Auth::user()->id;
        Debugbar::info("Deus é grande ;)"); 
        Debugbar::info(Request::get('emails_AddUser')); 
              
        
     
        
        $emailUserPatient=Request::get('emails_AddUser') ;
              
        
        $idUserPatient =  $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/user.php?email='.$emailUserPatient);
    
        if($idUserPatient!=-1)
        {
           Debugbar::info("Falhou no user id Patient "); 
                 Debugbar::info($idUserPatient[0]->id); 
            if( $this->callNanoSTIMAWebservicePOSTRelationUser('http://192.168.109.1/~nanostima/relationuser.php',$userid,$idUserPatient[0]->id)!= -1) //se não existir uma  relação entre o profissional de saúde e ALGUM paciente, então retorna 0 senão retorna um array com vários dados dos pacientes (DailyRec, WalkRec, Steps...) 
                {
                     \Session::flash('alert-success', 'User '.Request::get('emails_AddUser').' was successful added!');
                   
                  return Redirect::to("/admin/users");
               }
        }
       
       \Session::flash('alert-warning', 'It was not possible to add the user '.Request::get('emails_AddUser').' !');
      return Redirect::to("/admin/users");
    }
     public function users()
    {
         //id do utilizador autenticado
       $userid=Auth::user()->id;
       
        if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/relationuser.php?idUserProfessional='.$userid)== -1) //se não existir uma  relação entre o profissional de saúde e ALGUM paciente, então retorna 0 senão retorna um array com vários dados dos pacientes (DailyRec, WalkRec, Steps...) 
            {
               $usersPatients=0;
           }
          else
            {
              
              //resultados em json, com um array de relationuser (id de utilizadorProfissional e id de utilizadorPaciente)
              $usersPatients= $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/relationuser.php?idUserProfessional='.$userid);
              
              
             //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS DAILYREC......
              $usersPatientsAllDailyRec = array(); //array para armazenar os dailyrecords de cada paciente que o utilizador Profissional de Saúde logado tem
              
              $usersPatientsAllStep= array();//array para armazenar os Steps de cada paciente que o utilizador Profissional de Saúde logado tem
               
              $usersPatientsEmail= array();
              
              $usersPatientsAllWalkRec= array(); //array para armazenar os dailyrecords de cada paciente que o utilizador Profissional de Saúde logado tem
                
              $usersPatientsAllPainLevelbasedOnWalkRec = array();
              
              $usersPatientsAllPausebasedOnWalkRec=array();
              
              
              //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE O SEU EMAIL A PARTIR DO ID (CHAVE PRIMARIA) ......
                 
                    
                    if($this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/user.php')!=-1)
                        {
                         
                            $auxUserArray=array();
                            $auxUserArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/user.php');
                            foreach ( $auxUserArray as $userRecord)
                            {
                                 $usersPatientsEmail[$userRecord->id]=$userRecord->email;
                            }
                      }
                     //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE O SEU EMAIL A PARTIR DO ID (CHAVE PRIMARIA) ......
                  
              
                       
              foreach ($usersPatients as $userPatientRelationUserInfo)
              {
                  
                  
                
                    if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/dailyrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient)!=-1)
                        {
                         
                      $usersPatientsAllDailyRec[$userPatientRelationUserInfo->idUserPatient]=$this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/dailyrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient);

                      
                    
                         //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS STEP......
                         //como cada registo STEP está relacionada com um registo DailyRecord, é armazenado no array multidimensional $usersPatientsAllStep o id do paciente e o id do respectivo dailyrecord. E.g. $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient][$usersPatientsStepsBasedOnDailyRec->idDailyRec]
                         
                         $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient]= array();
                         
                          if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&lastDays=10000')!=-1)
                                             {
                                                    $auxStepArray=array();
                                                    $auxStepArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&lastDays=10000');
                                                    foreach ( $auxStepArray as $stepRecord)
                                                    {
                                                    $usersPatientsAllStep[$userPatientRelationUserInfo->idUserPatient][$stepRecord->idDailyRec][]=$stepRecord;

                                                    }
                                             }
                         
                           //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS STEP......
                         
                            
                         
                         
                            
                            
                        }
                       //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS DAILYREC......
                        
                        
                        
                          //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS WALKREC......
                           
             
                        if($this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/walkrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient)!=-1)
                            {

                            
                          $usersPatientsAllWalkRec[$userPatientRelationUserInfo->idUserPatient]= $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/walkrecord.php?idUser='.$userPatientRelationUserInfo->idUserPatient);
                            
                          
                          
                          
                          
                           
                         //como cada registo PainLevel está relacionada com um registo DailyRecord, mas é necessário ir buscar os registo Pain Level consoante as datas dateSTart e dateEnd das caminhadas (WalkRecord), é armazenado no array multidimensional $usersPatientsAllPainLevel, o id do paciente e o id do  walkrecord. E.g. $usersPatientsAllPainLevel[$userPatientRelationUserInfo->idUserPatient][$usersPatientsPainLevelBasedOnWalkRec->idDailyRec]

                             $usersPatientsAllPainLevelbasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient]= array();
                             $usersPatientsAllPausebasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient]= array();
                                 $usersPatientsAllStepbasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient]= array();

                                 
                                 
                                    //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS PainLevel......       
                                         if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/painstate.php?idUser='.$userPatientRelationUserInfo->idUserPatient)!=-1)
                                             {
                                                    $auxPainLevelArray=array();
                                                    $auxPainLevelArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/painstate.php?idUser='.$userPatientRelationUserInfo->idUserPatient);
                                                    foreach ( $auxPainLevelArray as $painLevelRecord)
                                                    {
                                                    $usersPatientsAllPainLevelbasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient][$painLevelRecord->idWalkRec][]=$painLevelRecord;

                                                    }
                                             }
                                              //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS PainLevel......
                                             
                                             
                                             
                                              //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS Pause......  
                                               if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/pause.php?idUser='.$userPatientRelationUserInfo->idUserPatient)!=-1)
                                             {
                                                    $auxPauseArray=array();
                                                    $auxPauseArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/pause.php?idUser='.$userPatientRelationUserInfo->idUserPatient);
                                                    foreach ( $auxPauseArray as $pauseRecord)
                                                    {
                                                    $usersPatientsAllPausebasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient][$pauseRecord->idWalkRecord][]=$pauseRecord;

                                                    }
                                             }
                                               //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS Pause......
                                          
                                             $usersPatientsAllStepbasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient]= array();
                         
                                             
                                             
                                             //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS STEP baseados nas caminhadas "Walk REC"......
                                              if( $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&idDailyRec=allwalkrecbyiduser')!=-1)
                                             {
                                                    $auxStepWalkRecArray=array();
                                                    $auxStepWalkRecArray = $this->callNanoSTIMAWebserviceGET('http://192.168.109.1/~nanostima/step.php?idUser='.$userPatientRelationUserInfo->idUserPatient.'&idDailyRec=allwalkrecbyiduser');
                                                    foreach ( $auxStepWalkRecArray as $stepWalkRecord)
                                                    {
                                                    $usersPatientsAllStepbasedOnWalkRec[$userPatientRelationUserInfo->idUserPatient][$stepWalkRecord->idWalkRec][]=$stepWalkRecord;

                                                    }
                                             }
                                             //......INICIO CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS STEP baseados nas caminhadas "Walk REC"......
                          
                            }
                        
                        //......FIM CÓDIGO PARA IR BUSCAR A CADA PACIENTE OS REGISTOS WALKREC......
             
            
                        }
              
                      
           }
          
           //limpa todos os elementos vazios e elementos-filhos vazios do array  $usersPatientsAllStep
          $usersPatientsAllStep= array_filter(array_map('array_filter', $usersPatientsAllStep));
           
           Debugbar::info( "Todos os registos dos Steps dos pacientes associados ao profissional de saúde");  
            Debugbar::info(  $usersPatientsAllStep);  
             Debugbar::info(  $usersPatients);  
            Debugbar::info(  $usersPatientsAllDailyRec);  
            Debugbar::info(  $usersPatientsAllPausebasedOnWalkRec);      
            Debugbar::info(  $usersPatientsAllWalkRec); 
            Debugbar::info(  $usersPatientsEmail); 
                        
             
         return view('admin.users')->with('data', ['usersPatients'=>$usersPatients, 'usersPatientsAllDailyRec' => $usersPatientsAllDailyRec, 'usersPatientsAllStep' => $usersPatientsAllStep, 'usersPatientsEmail' => $usersPatientsEmail,'usersPatientsAllWalkRec' => $usersPatientsAllWalkRec,'usersPatientsAllPainLevelbasedOnWalkRec' => $usersPatientsAllPainLevelbasedOnWalkRec,'usersPatientsAllPausebasedOnWalkRec' => $usersPatientsAllPausebasedOnWalkRec,'usersPatientsAllStepbasedOnWalkRec' => $usersPatientsAllStepbasedOnWalkRec]);
    }
    public function profile()
    {
        return view('admin.profile');
    }
    public function warnings()
    {
     
    }
      public function quick()
    {
        return view('admin.quickstart');
    }

    
    
     function callNanoSTIMAWebserviceGET($url)
    {  // in this function it is called the webservice by the $url parameters, that must include the parameters if needed for the GET Request
        // If the response returned in JSON of alldata->status->status is equal to 7 (success) then it return the obtained values, else returns -1
        
        $client= new Client();
        $response = $client->get($url);

        $obj = json_decode($response->getBody());

        if( $obj->alldata->status->status==7)
            {
            return $obj->alldata->data->result;
            }
        else
            {
            return -1;
            }
    
    }
    
    function callNanoSTIMAWebservicePOSTRelationUser($url,$idUserProfessional,$idUserPatient)
    {  // in this function it is called the webservice by the $url , that must include the parameters for the POST Request
        // If the response returned in JSON of alldata->status->status is equal to 7 (success) then it return the obtained values, else returns -1
        
        $client= new Client();
        $response = $client->request('POST',$url,['form_params' => ['idUserPatient' => $idUserPatient,'idUserProfessional' => $idUserProfessional,'dateStart' => date('Y-m-d H:i:s'),'state'=>1]]);

        $obj = json_decode($response->getBody());

        
        
        if( $obj->alldata->status[0]->status==7)
            {
            return $obj->alldata->data->result;
            }
        else
            {
            return -1;
            }
    
    }
    
      function callNanoSTIMAWebservicePOSTWalkRecordSessionUser($dateStart,$idUser,$state,$speed_Session,$elevation_Session,$id_Session,$PSE_5_min_Session,$PSE_10_min_Session,$PSE_15_min_Session,$PSE_20_min_Session,$PSE_25_min_Session,$PSE_30_min_Session,$PSE_35_min_Session,$PSE_40_min_Session)
    {  // in this function it is called the webservice by the $url , that must include the parameters for the POST Request
        // If the response returned in JSON of alldata->status->status is equal to 7 (success) then it return the obtained values, else returns -1
        
        $client= new Client();
        
        
        //dateStart=2017-10-8&dateEnd=2017-10-15&distanceGPS=2&altitudeDelta=2&idUser=13&state=1&speed_Session=22&elevation_Session=2&id_Session=1&PSE_5_min_Session=1&PSE_10_min_Session=1&PSE_15_min_Session=2&PSE_20_min_Session=1&PSE_25_min_Session=1&PSE_30_min_Session=4&PSE_35_min_Session=1&PSE_40_min_Session=1
        $response = $client->request('POST',"http://192.168.109.1/~nanostima/walkrecord.php",['form_params' => ['idUserPatient' => $idUserPatient,'idUserProfessional' => $idUserProfessional,'dateStart' => date('Y-m-d H:i:s'),'state'=>1]]);

        $obj = json_decode($response->getBody());

        
        
        if( $obj->alldata->status[0]->status==7)
            {
            return $obj->alldata->data->result;
            }
        else
            {
            return -1;
            }
    
    }



}
