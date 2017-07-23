<?php

require_once "lib/nusoap.php";

class service {

	//private $con;

	public function __construct(){

		

	}


    public function getRate($currency) {

        //$currency='USD';
        //die('Argument is: '.$currency);
        //creating connection
        $con = new mysqli('localhost','root','','ci_vetro');
        //mysql_connect('localhost','root','') or die("Connection Error: ".mysql_error());
         //mysql_select_db('ci_vetro');

        //check connection
        if($con->connect_error):
            die('Connection Error: '. $con->connect_error);
        endif;
		
    	   $sql= "SELECT * from exchange_rates where description='$currency'";
    	   $result= $con->query($sql);

   		if($result->num_rows > 0):
		   	while($row = $result->fetch_assoc()) :
		       $desc=$row["description"];
                $rate=$row["rate"];
		    endwhile;
		else:
    		echo "No currency found";
   		endif;

		$con->close();
    	   		  //die($rate); 
        switch ($desc):
            case 'USD':
                return $rate;
            break;
            case 'EUR':
                return $rate;
            break;
            case 'GBP':
                 return $rate;
            break;
            case 'KES':
                   return $rate;
            break;
            default:
              return 0;
            break;
        endswitch;
    }

    public function getPrice($rate,$amount,$foreign){
     
       if($foreign==='on'):
          return $amount / $rate;
       endif;
        return $amount * $rate;
    }

    public function getSurcharge($id,$amount){
        $con = new mysqli('localhost','root','','ci_vetro');
      
        //check connection
        if($con->connect_error):
            die('Connection Error: '. $con->connect_error);
        endif;
        
           $sql= "SELECT * from surcharge where id='$id'";
           $result= $con->query($sql);

        if($result->num_rows > 0):
            while($row = $result->fetch_assoc()) :
                $rate=$row["rate"];
            endwhile;
        else:
            echo "No rate found";
        endif;

        $con->close();
        //die($amount);
        return $amount*$rate;
    }
}
 
$server = new soap_server();
$server->configureWSDL("currencyservice", "http://localhost/ci_vetro/services/currency/service.php");
 
$server->register("service.getRate",
    array("type" => "xsd:string"),
    array("return" => "xsd:decimal"),
    "http://localhost/ci_vetro/services/currency/service.php",
    "http://localhost/ci_vetro/services/currency/service.php#getRate",
    "rpc",
    "encoded",
    "Get currency by type");
$server->register(
        "service.getPrice",
        array("rate"=>"xsd:decimal",
            "amount"=>"xsd:decimal",
            "foreign"=>"xsd:string"),
        array("return"=>"xsd:decimal"),
        "http://localhost/ci_vetro/services/currency/service.php",
    "http://localhost/ci_vetro/services/currency/service.php#getPrice",
    "rpc",
    "encoded",
    "Get amount by rate"
    );
$server->register("service.getSurcharge",
    array("id"=>"xsd:int",
        "amount"=>"xsd:decimal"),
    array("return"=>"xsd:decimal"),
    "http://localhost/ci_vetro/services/currency/service.php",
    "http://localhost/ci_vetro/services/currency/service.php#getSurcharge",
    "rpc",
    "encoded",
    "Get charge by currency"
    );
 //$server->setClass('service');
 $server->soap_defencoding = 'utf-8';
@$server->service($HTTP_RAW_POST_DATA);