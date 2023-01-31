<?php
include "../connection/config.php";
extract($_REQUEST);

if(isset($_REQUEST['type']) && $type == "delhi"){
    
    $get_markets = query("select market from gametime_delhi order by market");
    while($cf = fetch($get_markets)){
        
        $mmt['market'] = $cf['market'];
        $mmt['type'] = "main";
        $data['data'][] = $mmt;
        
    }

} else {

    $get_markets = query("select market from gametime_new order by market");
    while($cf = fetch($get_markets)){
        
        $mmt['market'] = $cf['market'];
        $mmt['type'] = "main";
        $data['data'][] = $mmt;
        
    }
    
    
    $get_markets = query("select market from gametime_manual order by market");
    while($cf = fetch($get_markets)){
        
        $mmt['market'] = $cf['market'];
        $mmt['type'] = "main";
        $data['data'][] = $mmt;
        
    }
    
     $get_markets = query("select market from gametime_delhi order by market");
    while($cf = fetch($get_markets)){
        
        $mmt['market'] = $cf['market'];
        $mmt['type'] = "main";
        $data['data'][] = $mmt;
        
    }
    
    
    $get_marketsx = query("select name from starline_markets order by name");
    while($cfx = fetch($get_marketsx)){
        
          $mmt['market'] = $cfx['name'];
            $mmt['type'] = "starline";
            $data['data'][] = $mmt;
    }

}


echo json_encode($data);