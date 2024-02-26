//////////// UPDATE HERE //////////////
var dbResync = '0';
var pmsInterfacePort = '5010';
var pmsInterfaceIP = '192.168.1.37';
var microsystemPmsID = '1'; // Access To PMS 10.51.142.24
var microsystemDomain = 'http://serenity.microsystem.com.eg';
var microsystemDatabase = 'serenity';
var microsystemToken = '4e0a91b8b5c152ab7ed1799b3d79880c6d7f4cf9098a062ecde1f22cd4361b1df088f7bc841abcd8409b28bcbf601389339e2dcca9ae6eed7fd52f95f4b3232a'; // update this token in `admin` table in `mobileapp_token` field
//////////// UPDATE HERE //////////////

var net = require('net');
var client = new net.Socket();
var request = require('request');

// Establishing TCP connection with IP and Port
function connect() {
    client.connect({
        port: pmsInterfacePort,
        host: pmsInterfaceIP
    });
}

// After TCP connection is connected
client.on('connect', function () {
    console.log('Connected');
    dbResync = '0';
    /*
    // our link start samles
    client.write('LS|DA220609|TI103423|');
    client.write('LD|DA220609|TI103423|V#2.0.0|IFWW|');
    client.write('LR|RIGO|FLG#GNRNGLGSNP|');
    client.write('LR|RIGC|FLG#GNRNROGLGSNP|');
    client.write('LR|RIPS|FLPTRNTADATIWSCT|');
    client.write('LR|RIPA|FLASRNWS|');
    client.write('LA|DA220609|TI103425|');
    client.write('DR|DA220609|TI144546|');
    */
   // default ACR link start sambles
    // client.write('LS|'+timeNow()+'|'); // link start
    client.write('LD|'+timeNow()+'|V#5.5|IFWW|'); // link desc
    client.write('LR|RINS|FLDATI|'); // link records
    // client.write('LR|RIGI|FLRNG#GNGLGGGAGDGSCSSF|');
    // client.write('LR|RIGI|FLRNG#GTGFGNGLGGGAGDGSCSSF|');
    client.write('LR|RIGI|FLRNA0A1A2A3A4A5A6A7A8A9G#GTGFGNGLGGGAGDGSCSGVG+SF|');
    client.write('LR|RIGO|FLRNG#GSGAGDG+SF|');
    client.write('LR|RIGC|FLRNROA0A1A2A3A4A5A6A7A8A9G#GTGFGNGLGGGAGDGSCSGVG+|');
    client.write('LR|RIPS|FLRNPTDATIDDDUSOP#S1S2T1TAMAM#|');
    // client.write('LR|RIPS|FLRNPTDATI|');
    client.write('LR|RIPA|FLRNASDATI|');
    client.write('LA|'+timeNow()+'|'); // link a live

    // client.write('DR|'+timeNow()+'|'); // database resync


    // // IAC records
    // client.write('LD|'+timeNow()+'|V#4.2|IFWW|'); // link desc
    // client.write('LR|RIPR|FLPIPMG#GNRNP#TACTSOX1S1WSDATI|'); //  "Post Inquery" query request for a spesific room, EX: PR|PI4142|P#246|WSIACBOX|DA230613|TI211026|\'03\cf4\f0\fs16 
    // this is IACBOX examble: // client.write('LR|RIPL|FLG#GNRNGAGDGFGLGVA0A1A2A3A4P#WSDATI|'); // "Post List" query response for a spesific room, EX.PL|P#246|WSIACBOX|DA230613|TI211026|RN4142|G#912789|GNLAZZARI|GA230610|GD230617|GFSAMUELE|GLEA|GV1|A01|A105/06/2007|A21|A31|A4LAZZARI SAMUELE |\'03\cf3 
    // client.write('LR|RIPL|FLRNA0A1A2A3A4A5A6A7A8A9G#GTGFGNGLGGGAGDGSCSGVG+P#WSDATI|'); // "Post List" query response for a spesific room, EX.PL|P#246|WSIACBOX|DA230613|TI211026|RN4142|G#912789|GNLAZZARI|GA230610|GD230617|GFSAMUELE|GLEA|GV1|A01|A105/06/2007|A21|A31|A4LAZZARI SAMUELE |\'03\cf3 	
    // client.write('LR|RIPA|FLASCTRNG#GNP#WSDATI|');
    // client.write('LR|RIGI|FLSFG#GNRNGAGDGFGLGVGSDATI|');
    // client.write('LR|RIGO|FLSFG#RNGSDATI|');
    // client.write('LR|RIGC|FLG#GNRNROGAGDGFGLGVGSDATI|');
    // client.write('LA|'+timeNow()+'|'); // link a live

});

// in case of receving any data
client.on('data', function (data) {
    var string = data.toString('utf8');
    if( string.split("|")[0] == "LA" && dbResync == '0' ){
    // if( string.split("|")[0] == "LA" ){
        dbResync = '1';
        client.write('DR|'+timeNow()+'|'); // database resync
    }
    send(data);
    console.log('Received from PMS interface: ' + data);
    // client.destroy(); // kill client after server's response
});

// get invoices every second
setInterval(function () {
    pullInvoices();
}, 1000 * 60 *1) //pull invoices each 1 minute

// Send Link a live to the PMS interface every 1 min
setInterval(function () {
    //sendLinkAliveToPMS
    client.write('LS|'+timeNow()+'|'); // link a live    
}, 1000 * 60 *1) //pull invoices each 1 minute

// send any recevied data from PMS to the Microsystem
function send(data) {
    // var dataDecoded = data.toString();
    var options = {
        'method': 'POST',
        'url': microsystemDomain+'/api/pmsInterface/rowData',
        'headers': {
            'Content-Type': 'application/json',
            'Cookie': 'XSRF-TOKEN=eyJpdiI6Ik5aM0s5dVJCMkM4dkNFXC9ldVwvQUVsdz09IiwidmFsdWUiOiJKTms1N05ITEhwRW9pMjAyaG1tWjR3Sk92VEpLank0NFlWYXZqWlV5UERGRUp0cDJjMzNzdVlMSHczVFhFQTJremZWNWFjS25WaTFHVmFwNUJESlh0Zz09IiwibWFjIjoiYTgyNGQ3MmEyNmUyMThmZDEwZjU2NTc2Nzg5NTAwYWFkM2U0N2E5YWQ2NTRiYTMyMTI0YmUyODdlZDQzMzEwNyJ9; laravel_session=ab067b84465bfa7a9f5877f6a73c26647d4911d6'
        },
        body: JSON.stringify({
            "system": microsystemDatabase,
            "token": microsystemToken,
            "pmsId": microsystemPmsID,
            "row_data": data.toString()
        })

    };
    request(options, function (error, response) {
        // if (error) throw new Error(error);
        try{
            console.log(response.body);
        }catch (err) {
            console.log('Error in sending JSON data:'+data);
        }
        
    });

}

// Pull any bending posting invoices from Microsystem
function pullInvoices() {
    
    var options = {
        'method': 'POST',
        'url': microsystemDomain+'/api/pmsInterface/pullInvoices',
        'headers': {
            'Content-Type': 'application/json',
            'Cookie': 'XSRF-TOKEN=eyJpdiI6Ik5aM0s5dVJCMkM4dkNFXC9ldVwvQUVsdz09IiwidmFsdWUiOiJKTms1N05ITEhwRW9pMjAyaG1tWjR3Sk92VEpLank0NFlWYXZqWlV5UERGRUp0cDJjMzNzdVlMSHczVFhFQTJremZWNWFjS25WaTFHVmFwNUJESlh0Zz09IiwibWFjIjoiYTgyNGQ3MmEyNmUyMThmZDEwZjU2NTc2Nzg5NTAwYWFkM2U0N2E5YWQ2NTRiYTMyMTI0YmUyODdlZDQzMzEwNyJ9; laravel_session=ab067b84465bfa7a9f5877f6a73c26647d4911d6'
        },
        body: JSON.stringify({
            "system": microsystemDatabase,
            "token": microsystemToken,
            "pmsId": microsystemPmsID
        })

    };
    request(options, function (error, response) {
        // if (error) throw new Error(error);
        // console.log(response.body);
        try {
            const obj = JSON.parse(response.body);
            if (obj.state == 1) {
                obj.data.forEach((element) => {
                    client.write('PS|RN'+element.room_no+'|P#'+element.id+'|PTC|'+timeNow()+'|TA'+element.price+'|CT'+element.package_name+'|');
                    console.log('PS|RN'+element.room_no+'|P#'+element.id+'|PTC|'+timeNow()+'|TA'+element.price+'|CT'+element.package_name+'|');
                    console.log('New invoice has been posted to the Room: '+element.room_no+', Id:'+element.id+', Price:'+element.price+', Package name:'+element.package_name);
                });
            }
        } catch (err) {
            console.log('Error in pullInvoices JSON response');
        }
        
    });

}

// get Time NOW
function timeNow() {
    // return Date();
    let nz_date_string = new Date().toLocaleString('en-eg', {timeZone: 'Africa/Cairo'}, {format: 'YYYY-MM-DD HH:mm'});
    // Date object initialized from the above datetime string
    let date_nz = new Date(nz_date_string);

    // year as (YYYY) format
    let year = date_nz.getFullYear().toString().substr(-2);
    // month as (MM) format
    let month = ("0" + (date_nz.getMonth() + 1)).slice(-2);

    // date as (DD) format
    let date = ("0" + date_nz.getDate()).slice(-2);

    // hours as (HH) format
    let hours = ("0" + date_nz.getHours()).slice(-2);

    // minutes as (mm) format
    let minutes = ("0" + date_nz.getMinutes()).slice(-2);

    // seconds as (ss) format
    let seconds = ("0" + date_nz.getSeconds()).slice(-2);

    // date as YYYY-MM-DD format
    let date_yyyy_mm_dd = year + "-" + month + "-" + date;
    // console.log("Date in YYYY-MM-DD format: " + date_yyyy_mm_dd);

    // time as hh:mm:ss format
    let time_hh_mm_ss = hours + ":" + minutes + ":" + seconds;
    // console.log("Time in hh:mm:ss format: " + time_hh_mm_ss);

    // date and time as YYYY-MM-DD hh:mm:ss format
    // let date_time = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;
    // return date_time;

    // let date_time = hours + minutes + seconds;
    // return date_time;

    let date_time = "DA" + year + month + date + "|TI" + hours + minutes + seconds;
    return date_time;

    // DA220609|TI144546

};

// in case of connection closed, auto reconnect after 1 minute
client.on('close', function () {
    setTimeout(connect, 1 * 60 * 1000); 
});

// in case of receving any connection error, auto reconnect after 1 minute
client.on('error', function (error) {
    console.error('Connection error:', error);
    setTimeout(connect, 1 * 60 * 1000); 
});

// Print Tine NOW
console.log(timeNow());

// establish TCP connection
connect();

