Echolink check status Online/Offline
=========================

Checks several brands, whether they are online or offline
 
 

RUN
--------- 
should connect to the database

    //create instance
    $e = new EcholinkStatus();

    //add repeaters
    $e->addRepeater("ok0mar");
    $e->addRepeater("ok0bhd");
    $e->addRepeater("ok0uec");

    //run script check from server echolink.org
    $e->check();


    // get data after check
    $e->GetRepeater()

    //array [..] = array("repeater","status","update","date")
    var_dump($e->GetRepeater());

 

