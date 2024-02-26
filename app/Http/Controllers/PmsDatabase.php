<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Hash;
use Session;
use Input;
use Validator;
use Auth;
use Redirect;
use DB;
use Carbon\Carbon;

class PmsDatabase extends Controller
{
    public function pull(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		
		// OLD
		// $opera51GetReservationsQuery ="select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		// $opera55GetReservationsQuery ="select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		// $suite8GetReservationsQuery = "select YS.YRMS_SHORTDESC ROOM_NO ,case when YS.YRES_XCMS_ID=XC.XCMS_ID then 'Main' else 'Accompany' end as type , XC.XCMS_ID NAME_ID , lower(nvl(XC.XCMS_NAME1,'-')) LAST_NAME ,lower(nvl(XC.XCMS_NAME3,'-')) FIRST_NAME , to_char(ys.YRES_EXPARRTIME,'dd/mm/yyyy') BEGIN_DATE ,to_char(ys.YRES_EXPDEPTIME,'dd/mm/yyyy') END_DATE ,(SELECT nvl(XCID.XCID_ADDRGREET,'-') FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) TITLE ,(SELECT case when XCID.XCID_SEX=1 then 'M' when XCID.XCID_SEX=2 then 'F' else '-' end FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) GENDER ,(select nvl(X.NATIONALITY,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ) NATIONALITY ,(select nvl(X.XCOU_LONGDESC,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ) COUNTRY ,(SELECT nvl(WLAN.WLAN_LONGDESC,'-') FROM WLAN WHERE WLAN.WLAN_ID = XC.XCMS_WLAN_ID ) LANGUAGE ,nvl(YS.TRAVELAGENT_NAME||YS.COMPANY_NAME,'-') TA ,nvl(to_char((SELECT XCID.XCID_BIRTHTIME FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID),'dd/mm/yyyy'),'-') birth_date ,nvl((select X.PRIMARY_TELEFON from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ),'-') TEL ,nvl((select X.PRIMARY_EMAIL from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ),'-') EMAIL ,nvl((select X.XPDA_11_LONG from V8_REP_XCMS_INFOS x where YS.YRES_XCMS_ID=X.XCMS_ID ),'-') vip_status , ys.yres_id as confirmation_number from xcms xc,YRPL yr ,V8_REP_YRES_INFOS YS where YS.INHOUSE=1 and YS.YRES_RESSTATUS=1 and YS.YRES_NOAVAILREASON=0 and xc.XCMS_ID=YR.YRPL_XCMS_ID and YR.YRPL_YRES_ID=YS.YRES_ID";

		// new including room type and expected arrival
		$opera51GetReservationsQuery ="select C.RESV_STATUS RESERVATION_STATUS,C.ROOM_CATEGORY_LABEL ROOM_TYPE, trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL,A.VIP_STATUS,B.CONFIRMATION_NO CONFIRMATION_NUMBER from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select C.RESV_STATUS RESERVATION_STATUS,C.ROOM_CATEGORY_LABEL ROOM_TYPE, trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL,A.VIP_STATUS,B.CONFIRMATION_NO CONFIRMATION_NUMBER from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		$opera55GetReservationsQuery ="select C.RESV_STATUS RESERVATION_STATUS,C.ROOM_CATEGORY_LABEL ROOM_TYPE, trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL,A.VIP_STATUS,B.CONFIRMATION_NO CONFIRMATION_NUMBER from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select C.RESV_STATUS RESERVATION_STATUS,C.ROOM_CATEGORY_LABEL ROOM_TYPE, trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL,A.VIP_STATUS,B.CONFIRMATION_NO CONFIRMATION_NUMBER from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";		
		$suite8GetReservationsQuery = "select (CASE WHEN YRES_RESSTATUS=3 then CASE WHEN YRES_NOAVAILREASON=1 then 'Waitlist' else CASE WHEN YRES_NOAVAILREASON=2 then 'Offer' else CASE WHEN YRES_NOAVAILREASON=3 then 'Canceled' else CASE WHEN YRES_NOAVAILREASON=4 then 'No Show' else 'Voucher Template' end end end end else CASE WHEN CHECKED_OUT =1 then 'Checked Out'else CASE WHEN DEPARTURE_TODAY=1 then 'Departure Expected' else CASE WHEN INHOUSE=1 then 'Checked In' else CASE WHEN ARRIVAL_TODAY=1 then 'Arrival Expected' else 'Expected' end end end end end) RESERVATION_STATUS ,YS.YCAT_SHORTDESC ROOM_TYPE ,YS.YRMS_SHORTDESC ROOM_NO ,case when YS.YRES_XCMS_ID=XC.XCMS_ID then 'Main' else 'Accompany' end as type ,ys.yres_id as RESV_NAME_ID ,XC.XCMS_ID NAME_ID ,lower(nvl(XC.XCMS_NAME1,'-')) LAST_NAME ,lower(nvl(XC.XCMS_NAME3,'-')) FIRST_NAME ,to_char(ys.YRES_EXPARRTIME,'dd/mm/yyyy') BEGIN_DATE ,to_char(ys.YRES_EXPDEPTIME,'dd/mm/yyyy') END_DATE ,(SELECT nvl(XCID.XCID_ADDRGREET,'-') FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) TITLE ,(SELECT case when XCID.XCID_SEX=1 then 'M' when XCID.XCID_SEX=2 then 'F' else '-' end FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) GENDER ,(select nvl(X.NATIONALITY,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ) NATIONALITY ,(select nvl(X.XCOU_LONGDESC,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ) COUNTRY ,(SELECT nvl(WLAN.WLAN_LONGDESC,'-') FROM WLAN WHERE WLAN.WLAN_ID = XC.XCMS_WLAN_ID ) LANGUAGE ,nvl(YS.TRAVELAGENT_NAME||YS.COMPANY_NAME,'-') TA ,nvl(to_char((SELECT XCID.XCID_BIRTHTIME FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID),'dd/mm/yyyy'),'-') birth_date ,nvl((select X.PRIMARY_TELEFON from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ),'-') TEL ,nvl((select X.PRIMARY_EMAIL from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID ),'-') EMAIL ,nvl((select X.XPDA_11_LONG from V8_REP_XCMS_INFOS x where YS.YRES_XCMS_ID=X.XCMS_ID ),'-') vip_status , ys.yres_id as confirmation_number from xcms xc,YRPL yr ,V8_REP_YRES_INFOS YS where (YS.INHOUSE=1 or ARRIVAL_TODAY=1) and YS.YRES_RESSTATUS=1 and YS.YRES_NOAVAILREASON=0 and xc.XCMS_ID=YR.YRPL_XCMS_ID and YR.YRPL_YRES_ID=YS.YRES_ID";
		$protel8GetReservationsQuery ="select (select zim.ziname  from zimmer zim where bu.zimmernr =zim.zinr) ROOM_NO, 'Main' type, bu.kundennr name_id, upper((select case when kun.name1='' then '-' else kun.name1 end from kunden kun where kun.kdnr=bu.kundennr )) LAST_NAME, upper((select case when kun.vorname='' then '-' else kun.vorname end from kunden kun where kun.kdnr=bu.kundennr )) FIRST_NAME, CONVERT(VARCHAR(10), bu.globdvon, 103) BEGIN_DATE, CONVERT(VARCHAR(10), bu.globdbis, 103) END_DATE, (select case when kun.titel ='' then '-' else kun.titel end from kunden kun where kun.kdnr=bu.kundennr ) TITLE, (select CASE WHEN kun.gender = 2 THEN 'Male' WHEN kun.gender = 1 THEN 'Female' ELSE '-' END  from kunden kun where kun.kdnr=bu.kundennr ) GENDER, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.nat ),'-') NATIONALITY, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.landkz ),'-') COUNTRY, isnull((select max(sp.name) from kunden kun,sprache sp where kun.kdnr=bu.kundennr and kun.sprache=sp.nr),'-') LANGUAGE from buch bu where bu.BUCHSTATUS=1";

		require_once '../config.php';

		//////////////////////////////////////////////////////////////////////////////////////////////

		// $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.5.60.35)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;
		// $bd2 = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST =10.5.60.35)(PORT = 1521))
		// (CONNECT_DATA =
		// (SERVER = DEDICATED)
		// (SERVICE_NAME = banco)
		// (INSTANCE_NAME = banco1)))";
		// $conn = oci_connect('opera', 'opera', '10.5.60.34:1521');
		// if (!$conn) {
		// 	$e = oci_error();
		// 	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		// }
		
		
		$allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            // check if PMS integration is on
			if(DB::table($Customer->database.'.settings')->where('type', 'pms_integration')->value('state') == 1){
				
				// check if there is active PMS integration
				foreach(DB::table($Customer->database.'.pms')->where('state', '1')->get() as $pms){

					// check if it is database direct connection or through interface
					if($pms->connection_type == 'database'){
						 
						// connect to oracle database
						$db = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = $pms->db_ip)(PORT = $pms->db_port))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = $pms->db_name)))";
						// $db = "(DESCRIPTION = (ADDRESS_LIST= (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.123)(PORT = 1521))(CONNECT_DATA =(SERVICE_NAME = V8))    )  )";
						if($connection = @OCILogon($pms->db_username, $pms->db_password, $db))
						{
							// reduce execution time of this file 500% and all over the system
							DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.users;');
							DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.radacct;');

							// update last check 
							DB::table($Customer->database.".pms")->where( 'id', $pms->id )->update([ 'last_check' => $todayDateTime ]); 
							// execute query to get reservations
							if($pms->type == 'opera51'){
								$stid = @oci_parse($connection, $opera51GetReservationsQuery)or die("<br>Connection Error<br>");
							}elseif($pms->type == 'opera55'){
								$stid = @oci_parse($connection, $opera55GetReservationsQuery)or die("<br>Connection Error<br>");
							}elseif($pms->type == 'suite8'){
								$stid = @oci_parse($connection, $suite8GetReservationsQuery)or die("<br>Connection Error<br>");
							}elseif($pms->type == 'protel'){
								$stid = @oci_parse($connection, $protelGetReservationsQuery);
							}
							@oci_execute($stid);
							if(isset($stid)){
								$guestsId=array();
								$checkedInRooms=array();
								// delete latest records
								// DB::statement("TRUNCATE TABLE $Customer->database.pms_reservations;");
								DB::statement("Delete from $Customer->database.pms_reservations where pms_id=$pms->id;");
								// return $stid;
								// return response()->json($stid);
								// return response()->json(@oci_fetch_array($stid));
								// return @oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
								if(!@oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){echo "<br>No data recevied<br>";}

								while ($row = @oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){

									if(isset($row['ROOM_NO']) and $row['ROOM_NO']!=""){
										// if(isset(explode('-',$row['BIRTH_DATE'])[2])){echo "";}
										if($row['TYPE']=="Accompany"){
											echo "ROOM_NO: ".$row['ROOM_NO'].", BIRTH_DATE:".$row['BIRTH_DATE'].", FIRST_NAME:".$row['FIRST_NAME'].", LAST_NAME:".$row['LAST_NAME'].", Guest_ID:".$row['NAME_ID']."<br>";
										}
										

										// build guests array to compare between current users to detect checkout reservations
										array_push($guestsId, $row['NAME_ID']);
										array_push($checkedInRooms, $row['ROOM_NO']);

										if(isset(explode('/',$row['BIRTH_DATE'])[2])){ $row['BIRTH_DATE'] = explode('/',$row['BIRTH_DATE'])[2]."-".explode('/',$row['BIRTH_DATE'])[1]."-".explode('/',$row['BIRTH_DATE'])[0]; }
										if(isset(explode('-',$row['BIRTH_DATE'])[2])){ $row['BIRTH_DATE'] = explode('-',$row['BIRTH_DATE'])[2]."-".explode('-',$row['BIRTH_DATE'])[1]."-".explode('-',$row['BIRTH_DATE'])[0]; }
										if(!isset($row['CONFIRMATION_NUMBER'])){$row['CONFIRMATION_NUMBER']="";}else{$row['CONFIRMATION_NUMBER']=$row['CONFIRMATION_NUMBER'];}
										if(!isset($row['VIP_STATUS'])){$row['VIP_STATUS']="";}
										if(!isset($row['TITLE']) or $row['TITLE'] == "-"){$row['TITLE']="";}

										//insert new reach
										DB::table($Customer->database.".pms_reservations")->insert([
											'pms_id' => $pms->id
											, 'room_no' => $row['ROOM_NO']
											, 'begin_date' => date("Y-m-d", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) )
											, 'end_date' => date("Y-m-d", strtotime( str_replace('/', '-',$row['END_DATE']) ) )
											, 'type' => $row['TYPE']
											, 'guest_id' => $row['NAME_ID']
											, 'title' => $row['TITLE']
											, 'first_name' => $row['FIRST_NAME']
											, 'last_name' => $row['LAST_NAME']
											, 'gender' => $row['GENDER']
											, 'nationality' => $row['NATIONALITY']
											, 'country' => $row['COUNTRY']
											, 'mobile' => $row['TEL']
											, 'email' => $row['EMAIL']
											, 'birth_date' => $row['BIRTH_DATE']
											, 'language' => $row['LANGUAGE']
											, 'ta' => $row['TA']
											, 'category' => $row['VIP_STATUS']
											, 'confirmation_number' => $row['CONFIRMATION_NUMBER']
											, 'created_at' => $todayDateTime]);

										// check if this reservation exist in users table 
										$userData = DB::table($Customer->database.".users")->where('pms_room_no', $row['ROOM_NO'])->first(); // 1-5-2023 Dreams have a problem (reception register groups with one guestID then switch all accounts to the specific account, so we switched Microsystem to be room based instead of guest based, so only created one account per room and reception group, and primary individual for two rooms issues has been resolved, but we can not detect returned guest )
										// $userData = DB::table($Customer->database.".users")->where('pms_guest_id', $row['NAME_ID'])->first(); // Dreams have a problem (multible user creation because of change user profile)
										// $userData = DB::table($Customer->database.".users")->where('pms_room_no', $row['ROOM_NO'])->where('pms_guest_id', '!=', '0')->first();
										
										// // in this case we detect the returned guest but we will check if the reception check-in the same guest profile to two rooms, 
										// if(isset($userData->pms_room_no) and $userData->pms_room_no !="0"){
										// 	// thats mean the guest profile aleady registerd in another checked-in profile
										// 	// thats mean the reception checking-in the same guest profile to two rooms, 
										// 	// and thats mean the first room will changed to the last room,
										// 	// so we will unset the user data to be able to register new room account
										// 	print_r(json_encode(array('state' => 2, 'message' => "Guest profile aleady registerd in another checked-in profile (".$row['NAME_ID']."), thats mean the reception checking-in the same guest profile to two rooms($userData->pms_room_no, ".$row['ROOM_NO']."), so will allow to register new room account for new room number (".$row['ROOM_NO'].")")));
										// 	unset($userData);
										// }

										if(isset($userData)){ // user already registerd before
											
											// check if user already active or not
											if($userData->suspend != "0"){ 

												// activate user again
												// DB::table($Customer->database.".users")->where( 'pms_guest_id', $row['NAME_ID'] )->update([ 'suspend' => '0' ]);  
												DB::table($Customer->database.".users")->where( 'u_id', $userData->u_id )->update([ 'suspend' => '0' ]);  // 1-5-2023 Dreams have a problem (reception register groups with one guestID then switch all accounts to the specific account, so we switched Microsystem to be room based instead of guest based, so only created one account per room and reception group, and primary individual for two rooms issues has been resolved, but we can not detect returned guest )
											}
											/*
											// check if this user change theit room no or not
											if($userData->pms_room_no != $row['ROOM_NO']){
												
												// update master room no field 
												DB::table($Customer->database.".users")->where( 'pms_guest_id', $row['NAME_ID'] )->update([ 'pms_room_no' => $row['ROOM_NO'] ]);

												// check if room no in login_username
												if (strpos($pms->login_username, 'room_no') !== false) {
													DB::table($Customer->database.".users")->where( 'pms_guest_id', $row['NAME_ID'] )->update([ 'u_uname' => $row['ROOM_NO'] ]);
												}

												// check if room no in login_password
												if (strpos($pms->login_password, 'room_no') !== false) {
													DB::table($Customer->database.".users")->where( 'pms_guest_id', $row['NAME_ID'] )->update([ 'u_password' => $row['ROOM_NO'] ]);
												}
												
											}
											*/

											// update data incase any update in birthdate or the reception add any extra info that affect in user login or any contacts for marketing
											
											// prepare variales
											$username = "";
											$password = "";
											if(isset($row['TITLE'])){$title=$row['TITLE'];}else{$title="";}
											if(isset($row['FIRST_NAME'])){$firstname=$row['FIRST_NAME'];}else{ $firstname=""; }
											if(isset($row['LAST_NAME'])){$lastname=$row['LAST_NAME'];}else{ $lastname=""; }
											if(isset($row['TEL']) and $row['TEL']!="-" ){ $mobile=$row['TEL']; }else{ $mobile=null; }
											if(isset($row['EMAIL']) and $row['EMAIL']!="-" ){ $email=$row['EMAIL']; }else{ $email=" "; }
											if(isset($row['BIRTH_DATE']) and $row['BIRTH_DATE']!="-" ){ $birthDate=$row['BIRTH_DATE']; }else{ $birthDate=null; }
											
											// get last values and concatinate them with new values if exist
											if( $email == " " or $email == $userData->u_email or strpos($userData->u_email, $email) !== false ){ $newEmail = $userData->u_email; }else{ $newEmail = $userData->u_email.','.$email; }
											if( $mobile == null or $mobile == $userData->u_phone or strpos($userData->u_phone, $mobile) !== false ){ $newMobile = $userData->u_phone; }else{ $newMobile = $userData->u_phone.','.$mobile; }

											// reformat title
											if(isset($title) and $title!="" and $title!=" " and $title!="-"){
												$fullName = $title." ".$firstname." ".$lastname;
											}else{
												$fullName = $firstname." ".$lastname;
											}
											
											// set login_username
											if($pms->login_username=="room_no"){$username = (int)$row['ROOM_NO'];}
											elseif($pms->login_username=="first_name"){$username = $row['FIRST_NAME'];}
											elseif($pms->login_username=="last_name"){$username = $row['LAST_NAME'];}
											elseif($pms->login_username=="mobile"){$username = $row['TEL'];}
											elseif($pms->login_username=="email"){$username = $row['EMAIL'];}
											elseif($pms->login_username=="birth_date" and isset(explode('-',$row['BIRTH_DATE'])[2]) ){$username = explode('-',$row['BIRTH_DATE'])[2]; }
											elseif($pms->login_username=="confirmation_no"){$username = $row['CONFIRMATION_NUMBER'];}
											elseif($pms->login_username=="check_in_date"){$username = date("dmY", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) );}
											elseif($pms->login_username=="check_out_date"){$username = date("dmY", strtotime( str_replace('/', '-',$row['END_DATE']) ) );}
											
											// set login_password
											if($pms->login_password=="room_no"){$password = (int)$row['ROOM_NO'];}
											elseif($pms->login_password=="first_name"){$password = $row['FIRST_NAME'];}
											elseif($pms->login_password=="last_name"){$password = $row['LAST_NAME'];}
											elseif($pms->login_password=="mobile"){$password = $row['TEL'];}
											elseif($pms->login_password=="email"){$password = $row['EMAIL'];}
											elseif($pms->login_password=="birth_date" and isset(explode('-',$row['BIRTH_DATE'])[2]) ){$password = explode('-',$row['BIRTH_DATE'])[2]; }
											elseif($pms->login_password=="confirmation_no"){$password = $row['CONFIRMATION_NUMBER'];}
											elseif($pms->login_password=="check_in_date"){$password = date("dmY", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) );}
											elseif($pms->login_password=="check_out_date"){$password = date("dmY", strtotime( str_replace('/', '-',$row['END_DATE']) ) );}

											// if there is no Birthday or the password is empty for any reason we will replace password to the last name (small letters)
											if($password==""){$password = strtolower($row['LAST_NAME']);}

											// set gender
											if($row['GENDER']=="Male"){$gender = 1;}
											elseif($row['GENDER']=="Female"){$gender = 0;}
											else{$gender = 2;}
											
											if(!isset($row['RESV_NAME_ID'])){$row['RESV_NAME_ID']="";}
											
											// DB::table($Customer->database.".users")->where( 'pms_guest_id', $row['NAME_ID'] )->update([ // // 1-5-2023 Dreams have a problem (reception register groups with one guestID then switch all accounts to the specific account, so we switched Microsystem to be room based instead of guest based, so only created one account per room and reception group, and primary individual for two rooms issues has been resolved, but we can not detect returned guest )
											DB::table($Customer->database.".users")->where( 'u_id', $userData->u_id )->update([ 
												'pms_room_no' => $row['ROOM_NO'], 
												'pms_reservation_id' => $row['RESV_NAME_ID'],
												'u_email' => $newEmail, 
												'u_name' => $fullName, 
												'u_uname' => $username, 
												'u_password' => $password, 
												'u_phone' => $newMobile, 
												'birthdate' => $birthDate,
												'u_country' => $row['COUNTRY'], 
												'u_lang'=> $row['LANGUAGE'],
												'u_gender' => $gender, 
												'branch_id' => DB::table($Customer->database.".branches")->where('state','1')->value('id'), 
												'network_id' => DB::table($Customer->database.".networks")->where('state','1')->value('id'), 
												// 'group_id' => $pms->internet_group, // To avoid the issue of manually replacing group_id in case of abusing
												'notes' => 'Room No: '.$row['ROOM_NO'].', Guest VIP category:'.$row['VIP_STATUS'].", Nationality: ".$row['NATIONALITY'].", checkIn: ".date("Y-m-d", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) ).", checkOut: ".date("Y-m-d", strtotime( str_replace('/', '-',$row['END_DATE']) ) ).", Travel Agent: ".$row['TA'].", Guest birthday: ".$row['BIRTH_DATE'].", Reservation Number: ".$row['CONFIRMATION_NUMBER'].", Guest ID (unique): ".$row['NAME_ID'].", Reservation status: ".$row['RESERVATION_STATUS'].", Room type: ".$row['ROOM_TYPE']
											]);
											
										}else{ 
											// insert new user into `users` table
											$username = "";
											$password = "";
											if(isset($row['TITLE'])){$title=$row['TITLE'];}else{$title="";}
											if(isset($row['FIRST_NAME'])){$firstname=$row['FIRST_NAME'];}else{ $firstname=""; }
											if(isset($row['LAST_NAME'])){$lastname=$row['LAST_NAME'];}else{ $lastname=""; }
											if(isset($row['TEL']) and $row['TEL']!="-" ){ $mobile=$row['TEL']; }else{ $mobile=null; }
											if(isset($row['EMAIL']) and $row['EMAIL']!="-" ){ $email=$row['EMAIL']; }else{ $email=" "; }
											if(isset($row['BIRTH_DATE']) and $row['BIRTH_DATE']!="-" ){ $birthDate=$row['BIRTH_DATE']; }else{ $birthDate=null; }
											
											// reformat title
											if(isset($title) and $title!="" and $title!=" " and $title!="-"){
												$fullName = $title." ".$firstname." ".$lastname;
											}else{
												$fullName = $firstname." ".$lastname;
											}
											

											// set login_username
											if($pms->login_username=="room_no"){$username = (int)$row['ROOM_NO'];}
											elseif($pms->login_username=="first_name"){$username = $row['FIRST_NAME'];}
											elseif($pms->login_username=="last_name"){$username = $row['LAST_NAME'];}
											elseif($pms->login_username=="mobile"){$username = $row['TEL'];}
											elseif($pms->login_username=="email"){$username = $row['EMAIL'];}
											elseif($pms->login_username=="birth_date" and isset(explode('-',$row['BIRTH_DATE'])[2]) ){$username = explode('-',$row['BIRTH_DATE'])[2]; }
											elseif($pms->login_username=="confirmation_no"){$username = $row['CONFIRMATION_NUMBER'];}
											elseif($pms->login_username=="check_in_date"){$username = date("dmY", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) );}
											elseif($pms->login_username=="check_out_date"){$username = date("dmY", strtotime( str_replace('/', '-',$row['END_DATE']) ) );}
											
											// set login_password
											if($pms->login_password=="room_no"){$password = (int)$row['ROOM_NO'];}
											elseif($pms->login_password=="first_name"){$password = $row['FIRST_NAME'];}
											elseif($pms->login_password=="last_name"){$password = $row['LAST_NAME'];}
											elseif($pms->login_password=="mobile"){$password = $row['TEL'];}
											elseif($pms->login_password=="email"){$password = $row['EMAIL'];}
											elseif($pms->login_password=="birth_date" and isset(explode('-',$row['BIRTH_DATE'])[2]) ){$password = explode('-',$row['BIRTH_DATE'])[2]; }
											elseif($pms->login_password=="confirmation_no"){$password = $row['CONFIRMATION_NUMBER'];}
											elseif($pms->login_password=="check_in_date"){$password = date("dmY", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) );}
											elseif($pms->login_password=="check_out_date"){$password = date("dmY", strtotime( str_replace('/', '-',$row['END_DATE']) ) );}

											// if there is no Birthday or the password is empty for any reason we will replace password to the last name (small letters)
											if($password==""){$password = strtolower($row['LAST_NAME']);}
											
											// set gender
											if($row['GENDER']=="Male"){$gender = 1;}
											elseif($row['GENDER']=="Female"){$gender = 0;}
											else{$gender = 2;}
											
											// avoid nulled fields
											// if(!isset($row['TITLE'])){$row['TITLE']="";}
											// if(!isset($row['FIRST_NAME'])){$row['FIRST_NAME']="";}
											// if(!isset($row['LAST_NANME'])){$row['LAST_NANME']="";}
											// if(!isset($row['NATIONALITY'])){$row['NATIONALITY']="";}
											// if(!isset($row['COUNTRY'])){$row['COUNTRY']="";}
											// if(!isset($row['LANGUAGE'])){$row['LANGUAGE']="";}
											// if(!isset($row['TA'])){$row['TA']="";}
											
											if(!isset($row['RESV_NAME_ID'])){$row['RESV_NAME_ID']="";}
											

											$newUserID = DB::table("$Customer->database.users")->insertGetId([ 
												'pms_id' => $pms->id,
												'pms_guest_id' => $row['NAME_ID'], 
												'pms_room_no' => $row['ROOM_NO'], 
												'pms_reservation_id' => $row['RESV_NAME_ID'],
												'u_email' => $email, 
												'Registration_type' => '2', 
												'u_state' => '1', 
												'suspend' => '0', 
												'u_name' => $fullName, 
												'u_uname' => $username, 
												'u_password' => $password, 
												'u_phone' => $mobile, 
												'birthdate' => $birthDate,
												'u_country' => $row['COUNTRY'], 
												'u_lang'=> $row['LANGUAGE'],
												'u_gender' => $gender, 
												'branch_id' => DB::table($Customer->database.".branches")->where('state','1')->value('id'), 
												'network_id' => DB::table($Customer->database.".networks")->where('state','1')->value('id'), 
												'group_id' => $pms->internet_group, 
												// 'notes' => 'Account Type: '.$row['TYPE'].", Nationality: ".$row['NATIONALITY'].", Travel Agent: ".$row['TA'], 
												'notes' => 'Room No: '.$row['ROOM_NO'].', Guest VIP category:'.$row['VIP_STATUS'].", Nationality: ".$row['NATIONALITY'].", checkIn: ".date("Y-m-d", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) ).", checkOut: ".date("Y-m-d", strtotime( str_replace('/', '-',$row['END_DATE']) ) ).", Travel Agent: ".$row['TA'].", Guest birthday: ".$row['BIRTH_DATE'].", Reservation Number: ".$row['CONFIRMATION_NUMBER'].", Guest ID (unique): ".$row['NAME_ID'].", Reservation status: ".$row['RESERVATION_STATUS'].", Room type: ".$row['ROOM_TYPE'],
												'created_at' => $todayDateTime]);
										}

										unset($userData);
										unset($username);
										unset($password);
										unset($gender);
										unset($fullName);
										unset($title);
										unset($username);
										unset($password);
										unset($fullName);
										unset($mobile);
										unset($email);
										unset($birthDate);
										
										// echo "ROOM_NO: ".$row['ROOM_NO'] ."<br>";
										// echo "TYPE: ".$row['TYPE'] ."<br>";
										// echo "NAME_ID: ".$row['NAME_ID'] ."<br>";
										// echo "LAST_NAME: ".$row['LAST_NAME'] ."<br>";
										// echo "FIRST_NAME: ".$row['FIRST_NAME'] ."<br>";
										// echo "BEGIN_DATE: ".$row['BEGIN_DATE'] ."<br>";
										// echo "END_DATE: ".$row['END_DATE'] ."<br>";
										// echo "TITLE: ".$row['TITLE'] ."<br>";
										// echo "GENDER: ".$row['GENDER'] ."<br>";
										// echo "NATIONALITY: ".$row['NATIONALITY'] ."<br>";
										// echo "COUNTRY: ".$row['COUNTRY'] ."<br>";
										// echo "LANGUAGE: ".$row['LANGUAGE'] ."<br>";
										// echo "TA: ".$row['TA'] ."<br>";
						
										// echo "<br>";
										// foreach ($row as $item) {
										// 	echo $item ."<br>";
										// }
									}
								}
								
							}


							//////////////////////////////////////////////////////////////////////////////////////////////////
							////////////////////// check if there is bending invoice to send it to the PMS ///////////////////
							//////////////////////////////////////////////////////////////////////////////////////////////////

						
							// insert new invoice record
							if($pms->type == 'opera51' or $pms->type == 'opera55'){

								// 1st step: get posting username (subervisior) data
								$step1Query = 'select APP_USER_ID,DEF_CASHIER_ID from APPLICATION$_USER a where a.app_user=upper('."'".$pms->db_posting_username."'".')';
								$invoiceStep1 = @oci_parse($connection, $step1Query);
								@oci_execute($invoiceStep1);
								if(isset($invoiceStep1)){
									while ($row2 = @oci_fetch_array($invoiceStep1, OCI_ASSOC+OCI_RETURN_NULLS)){
										$APP_USER_ID = $row2['APP_USER_ID'];
										$DEF_CASHIER_ID = $row2['DEF_CASHIER_ID'];
									}

									// 2nd step: get Transaction Code data
									$step2Query = 'select T.TC_GROUP,T.TC_SUBGROUP,T.TRX_CODE from trx$_codes t where t.trx_code='."'".$pms->db_transaction_code."'";
									$invoiceStep2 = @oci_parse($connection, $step2Query);
									@oci_execute($invoiceStep2);
									if(isset($invoiceStep2)){
										while ($row3 = @oci_fetch_array($invoiceStep2, OCI_ASSOC+OCI_RETURN_NULLS)){
											$TC_GROUP = $row3['TC_GROUP'];
											$TC_SUBGROUP = $row3['TC_SUBGROUP'];
											$TRX_CODE = $row3['TRX_CODE'];
										}

										foreach( DB::table("$Customer->database.pms_invoices")->where('pms_id', $pms->id)->where('delivered', '0')->get() as $bendingInvoice ){

											// 3rd step: get all reservation data using reservation_id
											$userData = DB::table($Customer->database.".users")->where( 'u_id', $bendingInvoice->user_id )->first();
											$step3Query = "SELECT    '' email, D.RESORT resort, d.room_class , D.ROOM_CATEGORY_LABEL ROOM_TYPE, trim(' ' from D.ROOM_NO) ROOM_NO, d.full_name, D.VIP, D.GUEST_COUNTRY , D.NATIONALITY , D.NATIONALITY nationality_desc, D.GUEST_COUNTRY country_desc, D.ACCOMPANYING_NAMES , D.CONFIRMATION_NO , D.RESV_NAME_ID , D.GUEST_NAME_ID , trim('.' from f.custom_reference) voucher_no, to_char(d.begin_date,'dd/mm/yyyy') actual_check_in_date, to_char(d.end_date,'dd/mm/yyyy') actual_check_out_date, to_char(d.end_date-1,'mm/dd/yyyy') actual_check_out_date2, D.NIGHTS, D.PERSONS, D.ADULTS, D.CHILDREN, D.CHILDREN1 ||'/'|| D.CHILDREN2 ||'/'|| D.CHILDREN3 ||'/'|| D.CHILDREN4 ||'/'|| D.CHILDREN5 as CHILD_DETAILS, d.UDFC01 BOARD_TYPE, D.BOOKED_ROOM_CATEGORY_LABEL ROOM_TYPE_RATE, nvl(D.C_T_S_NAME,'-') account_name, (select name from RESORT_BASE_VIEW) hotel_name, D.POSTING_ALLOWED_YN, (case when D.POSTING_ALLOWED_YN ='N' then 'No' else 'Yes' end) osama, D.RATE_CODE, D.MARKET_CODE, D.ORIGIN_OF_BOOKING source_code, '' FOLIO_NO FROM rep_reservation_all_view d, reservation_name f WHERE f.resv_name_id = d.resv_name_id and  f.resv_status = 'CHECKED IN' and d.resv_name_id='$userData->pms_reservation_id' ";
											$invoiceStep3 = @oci_parse($connection, $step3Query);
											@oci_execute($invoiceStep3);
											if(isset($invoiceStep3)){
												while ($row4 = @oci_fetch_array($invoiceStep3, OCI_ASSOC+OCI_RETURN_NULLS)){
													$ROOM_CLASS = $row4['ROOM_CLASS'];
													$ROOM_NO = $row4['ROOM_NO'];
													$RESV_NAME_ID = $row4['RESV_NAME_ID'];
													$GUEST_NAME_ID = $row4['GUEST_NAME_ID'];
													$MARKET_CODE = $row4['MARKET_CODE'];
													$SOURCE_CODE = $row4['SOURCE_CODE'];
													$RATE_CODE = $row4['RATE_CODE'];
													$FOLIO_NO = $row4['FOLIO_NO'];
												}
												// 4th FINAL STEP: posting invoice 
												$currency = DB::table($Customer->database.".settings")->where( 'type', 'currency' )->value('value');
												// fields: 61
												// avlues: 62
												$step4Query = "
												Insert into FINANCIAL_TRANSACTIONS
												(ROOM_CLASS, TAX_INCLUSIVE_YN, NET_AMOUNT, GROSS_AMOUNT, CHEQUE_NUMBER, TRX_NO, RESORT, FT_SUBTYPE, TC_GROUP, TC_SUBGROUP, TRX_CODE, TRX_DATE, BUSINESS_DATE, ROOM, CURRENCY, RESV_NAME_ID, CASHIER_ID, FOLIO_VIEW, QUANTITY,  PRICE_PER_UNIT, TRX_AMOUNT, NAME_ID, POSTED_AMOUNT, MARKET_CODE, SOURCE_CODE, RATE_CODE, DEFERRED_YN, EXCHANGE_RATE, GUEST_ACCOUNT_DEBIT, TRAN_ACTION_ID, FIN_DML_SEQ_NO, REVENUE_AMT, FOLIO_NO, INSERT_USER, INSERT_DATE, UPDATE_USER, UPDATE_DATE, FIXED_CHARGES_YN, EURO_EXCHANGE_RATE, TAX_GENERATED_YN, DISPLAY_YN, COLL_AGENT_POSTING_YN, DEFERRED_TAXES_YN, POSTING_DATE, ROOM_NTS, ORIGINAL_RESV_NAME_ID, ORIGINAL_ROOM, CLOSURE_NO, POSTING_TYPE, CALC_POINTS_YN, AUTO_SETTLE_YN, DEP_TAX_TRANSFERED_YN,  REMARK) 
												Values ('$ROOM_CLASS', 'N', '$bendingInvoice->price', '$bendingInvoice->price', '$bendingInvoice->id', TRX_DETAIL_SEQNO.nextval,(select resort from resort_view), 'C    ', '$TC_GROUP', '$TC_SUBGROUP', '$TRX_CODE', (select max(BD.BUSINESS_DATE) from BUSINESSDATE bd where BD.STATE='OPEN'), 
												(select max(BD.BUSINESS_DATE) from BUSINESSDATE bd where BD.STATE='OPEN'), '$userData->pms_room_no', '$currency','$userData->pms_reservation_id', '$DEF_CASHIER_ID', 1, 1, '$bendingInvoice->price', '$bendingInvoice->price','$GUEST_NAME_ID', '$bendingInvoice->price', '$MARKET_CODE', '$SOURCE_CODE', '$RATE_CODE', 'N', 1, '$bendingInvoice->price', FIN_ACTION_ID_SEQNO.nextval, FIN_DML_SEQNO.nextval, '$bendingInvoice->price', '$FOLIO_NO', '$APP_USER_ID', sysdate, '$APP_USER_ID', sysdate, 'N', 1, 'N', 'Y', 'N', 'N', (select to_date((to_char(max(BD.BUSINESS_DATE),'dd/mm/yyyy')||to_char(sysdate,' hh24:mi:ss')),'dd/mm/yyyy hh24:mi:ss') from BUSINESSDATE bd where BD.STATE='OPEN'), 0, '$RESV_NAME_ID','$ROOM_NO', 3, 'MANUAL', 'N', 'N', 'N','$bendingInvoice->package_name')";
												$finalPostingInvoice = @oci_parse($connection, $step4Query);
												
												if(@oci_execute($finalPostingInvoice)){
													
													// invoice inserted successfully, so we will update delevery date of the invoice
													DB::table($Customer->database.".pms_invoices")->where( 'id', $bendingInvoice->id )->update([ 'delivered' => '1', 'delivered_at' => $todayDateTime ]); 
												}
												
											}

										}

									}
								}

							}elseif($pms->type == 'suite8'){
								//$invoice = @oci_parse($connection, $suite8InsertInvoiceQuery);
							}elseif($pms->type == 'protel'){
								//$invoice = @oci_parse($connection, $protelInsertInvoiceQuery);
							}

						

							@OCILogoff($connection);
						}else{
							echo "Connection Failed";
						}

					}

					////////////////////////////////////////////////////////////////////////////////////////////////////
					/////////////////////////// check if there is reservations checking out ////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////////////////////
				

					// compare active users in table users VS pms_reservations table
					// if(isset($guestsId) and count($guestsId)>0){
					if(isset($checkedInRooms) and count($checkedInRooms)>0){	

						foreach( DB::table("$Customer->database.users")->where('pms_id', $pms->id)->where('group_id', '!=', $pms->checkout_group)->get() as $activeUser ){
							
							// if( in_array($activeUser->pms_guest_id ,$guestsId) == false ){
							if( in_array($activeUser->pms_room_no ,$checkedInRooms) == false ){
								// // we will diactivate this user
								// DB::table($Customer->database.".users")->where( 'u_id', $activeUser->u_id )->update([ 'suspend' => '1' ]);
								// echo "activeUser->pms_guest_id : $activeUser->pms_guest_id <br>";
								// move user to check-out group 
								$finalUserName = $activeUser->u_name.", Checked Out".$activeUser->pms_room_no." at:".$todayDateTime;
								DB::table($Customer->database.".users")->where( 'u_id', $activeUser->u_id )->update([ 'group_id' => $pms->checkout_group, 'pms_id' => '0',  'pms_room_no' => '0',  'pms_reservation_id' => '0', 'u_name' => $finalUserName, 'updated_at' => $todayDateTime ]); 
							}
						}
					}
				}
			}

		}

		return "Done";
    }
}