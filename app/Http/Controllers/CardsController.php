<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Cards;
use App\Network;
use App\Groups;
use Input;
use DB;
use Validator;
use Auth;
use Carbon\Carbon;
use Excel;
use App;

class CardsController extends Controller
{


    public function Index()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['cards'] == 1){
            return view('back-end.cards.index');
        }else{
            return view('errors.404');
        }
    }

    public function Json()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['cards']) {
            $history = App\History::where('operation', 'Generate cards')->get();
            $alldata = array();
            $counter = 0;

            foreach ($history as $data) {
                $details = explode(";", $data->details);

                $from = $details[0];
                $to = $details[1];
                $count = $details[2];
                $price = $details[3];
                $date = $data->add_date;

                $history_id = $data->id;
                $state = $data->notes;
                $alldata[$counter] = ['from' => $from, 'to' => $to, 'count' => $count, 'state' => $state, 'h_id' => $history_id, 'price' => $price, 'date' => $date];
                $counter++;
            }
            return array('aaData' => $alldata);
        }else{
            return view('errors.404');
        }
    }

    public function add()
    {
        $cadrscount = Input::get('cardscount');

        /*$oldCardsCount = App\Cards::orderBy('id', 'desc')->first();
        if ($oldCardsCount) {
            $oldCardsCount = $oldCardsCount->id;
        }
        if (!$oldCardsCount) {
            $fromSerial = 0;
        } else {
            $fromSerial = $oldCardsCount + 1;
        }
        $toSerial = $oldCardsCount + $cadrscount;*/
        $counter = 0;
        for ($i = 1; $i <= $cadrscount; $i++) {

            @$after_calculate = $counter * ($counter / $counter) * $counter;
            $final0 = round($after_calculate, 0);
            $sp2 = str_split($final0);
            if (!isset($sp2[0])) {
                $sp2[0] = rand(1, 9);
            }
            if (!isset($sp2[1])) {
                $sp2[1] = rand(0, 9);
            }
            if (!isset($sp2[2])) {
                $sp2[2] = rand(1, 9);
            }
            if (!isset($sp2[3])) {
                $sp2[3] = rand(0, 9);
            }
            $random_number1 = rand(11, 99);
            $random_number2 = rand(12, 99);
            $____final____ = $random_number1 . $sp2[0] . $sp2[1] . $sp2[2] . $sp2[3] . $random_number2;
            $strlen_of_final = @strlen($____final____);
            if ($strlen_of_final < 8) {
                $_get_len_subtract = 8 - $strlen_of_final;
                if ($_get_len_subtract == 7) {
                    $random_number3 = rand(1111111, 9999999);
                }
                if ($_get_len_subtract == 6) {
                    $random_number3 = rand(111111, 999999);
                }
                if ($_get_len_subtract == 5) {
                    $random_number3 = rand(11111, 99999);
                }
                if ($_get_len_subtract == 4) {
                    $random_number3 = rand(1111, 9999);
                }
                if ($_get_len_subtract == 3) {
                    $random_number3 = rand(111, 999);
                }
                if ($_get_len_subtract == 2) {
                    $random_number3 = rand(11, 99);
                }
                if ($_get_len_subtract == 1) {
                    $random_number3 = rand(1, 9);
                }
                $____final____ = $____final____ . $random_number3;
            }
            $check = App\Cards::where('number', $____final____)->get();
            if ($check) {
                $____final____ = rand(11119161, 99999999);
            }

            if (Input::get('state') == "true") {
                $state = 1;
            } else {
                $state = 1;
            }
            $counter++;
            $lastid = App\Cards::insertGetId(
                [ 'number' => $____final____, 'price' => Input::get('price'), 'start_pay_date' => Input::get('startdate'), 'end_pay_date' => Input::get('enddate'), 'state' => $state]
            );
            $to = $lastid;
            $from = ($lastid - $cadrscount)+1;
        }
        $dt = Carbon::now();
        App\History::insert(
            ['type1' => 'hotspot', 'type2' => 'admin', 'operation' => 'Generate cards', 'details' => $from . ';' . $to . ';' . $cadrscount . ';'. Input::get('price'), 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString(), 'notes' => $state]
        );
        //return redirect()->route('cards');
    }

    public function getcards($id)
    {
        $cards = Cards::find($id);
        return view('back-end.cards.edit', ['cards' => $cards]);
    }

    public function delete($from, $to, $h_id)
    {
        App\History::where('id',$h_id)->delete();
        App\Cards::whereBetween('id', [$from,$to])->delete();
    }

    public function getcardlist($from, $to)
    {
        $cards = App\Cards::whereBetween('id', [$from,$to])->get();
        /*$counter = 0;
        foreach($cards as $card){
            //if(isset($card->u_id)) {
                $cards[$counter] = ['id' => $card->id,'u_id' => $card->u_id,'number' => $card->number,'price' => $card->price, 'start_pay_date' => $card->start_pay_date, 'end_pay_date' => $card->end_pay_date, 'date_of_charging' => $card->date_of_charging, 'u_name' => App\Users::where('u_id', $card->u_id)->value('u_name')];
            //}
            //$cards2[$counter] =  array('id'=>$card->id,'u_id'=>$card->u_id, 'number' => $card->number);
            $counter++;
        }*/
        return view('back-end.cards.list', ['cards' => $cards]);
    }

    public function state($from,$to,$value,$h_id){
        $value = ($value == 'true')? 1 : 0;
        App\History::where('id',$h_id)->update(['notes'=>$value]);
        App\Cards::whereBetween('id', [$from,$to])->update(['state'=>$value]);
    }
    public function exportcards($from,$to){
        $cards = Cards::whereBetween('id', [$from,$to])->get()->toArray();
        Excel::create('Cards', function($excel) use($cards) {
            $excel->sheet('Sheet 1', function($sheet) use($cards) {
                $sheet->fromArray($cards);
            });
        })->download('xlsx');


        return redirect()->route('cards');

    }
}