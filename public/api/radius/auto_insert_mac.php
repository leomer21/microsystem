<?php
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////

// IF YOU MADE CHANGE IN RADIUS FILES YOU SHOULD COPY THIS FILES TO /home/hotspot/public_html/public/api/radius AND COMMENT ALL (insert, update, delete) QUERIES
// TO BE ABLE TO GET THE RADIUS RESPONSE MESSAGE INTO USER PANEL AFTER LOGIN

//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////

// Step 9 Get system information for 1: auto insert mac address
if($userLoginByMacAuto!="yes"){ // user logged in by username so we can store his mac address
    $getSystemInformation="select `auto_login`, `limited_devices`, `port_limit` from `area_groups` where `id`='$db_group_id'";
    $r_getSystemInformation=@mysqli_query($conn,$getSystemInformation);
    $row_getSystemInformation=@mysqli_fetch_array($r_getSystemInformation);
    // $auto_insert_mac=$row_getSystemInformation['auto_login']; // switched to getting value from branch instead of group
    if($auto_insert_mac=="1"){
        if(strpos($db_u_macaddress, $mac) !== false) {}//Mac Address inserted before
        else{//Mac Not found id user
            // check no of limited devices
            $limited_devices=$row_getSystemInformation['limited_devices'];
            
            if($limited_devices==0){// user can add unlimited mac
                // check avilable concurrent sessions and total users mac address
                $countMacForUsers=count (explode(",",$db_u_macaddress));
                $port_limit=$row_getSystemInformation['port_limit'];

                if(!isset($db_u_macaddress) or $db_u_macaddress=="")
                {// not found mac in db so we will Update New Mac direct
                $addNewMac=$mac;
                    // @mysqli_query($conn,"update `users` set `u_mac`='$addNewMac' where `u_id`='$db_Uid'"); 
                }
                elseif($countMacForUsers<$port_limit)
                { 
                    // A - can add new mac without problem
                    if($db_u_macaddress){$addNewMac=$db_u_macaddress.",".$mac;}
                    else{$addNewMac=$mac;}
                    // @mysqli_query($conn,"update `users` set `u_mac`='$addNewMac' where `u_id`='$db_Uid'");
                }else{
                    // B - remove first mac from list then add new mac as last one
                    
                    $userMacSplited=explode(",",$db_u_macaddress);
                    $addNewMac="";
                    for($i=0;$i<=$countMacForUsers;$i++)
                    {   
                        if($addNewMac and $addNewMac!=""){$addNewMac.=",";}// just add comma for separation
                        if($i==0){}// skip first mac
                        elseif($i>0 and $i<$countMacForUsers){$addNewMac.=$userMacSplited[$i];}
                        elseif($i==$countMacForUsers){$addNewMac.=$mac;}
                    }
                    // @mysqli_query($conn,"update `users` set `u_mac`='$addNewMac' where `u_id`='$db_Uid'");
                }

            }elseif($limited_devices==1){// Update New Mac direct
                $addNewMac=$mac;
                // @mysqli_query($conn,"update `users` set `u_mac`='$addNewMac' where `u_id`='$db_Uid'");

            }elseif( $limited_devices >1 and count (explode(",",$db_u_macaddress)) < $limited_devices ) //still have device credit, can add new device
            {
                if($db_u_macaddress){$addNewMac=$db_u_macaddress.",".$mac;}
                else{$addNewMac=$mac;}
                // @mysqli_query($conn,"update `users` set `u_mac`='$addNewMac' where `u_id`='$db_Uid'");
            }
        }
    }
}

?>