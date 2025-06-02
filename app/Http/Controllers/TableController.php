<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function timeTable(){

        return view('Tables.Timetable');

    }
    
    public function room(){

        return view('Tables.Rooms_table');

    }
    public function doctor(){

        return view('Tables.Doctor_tables');

    }
    public function assistant(){

        return view('Tables.Assistant_table');

    }
    public function docName(){

        return view('Tables.doc_name');

    }
    
    public function classroom(){

        return view('Tables.classroom');

    }
    
    public function l1(){

        return view('Tables.L1_timetable');

    }
    
}
