<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CalendarService;

class CalendarController extends Controller
{
    public function store(Request $request) {
        $rules = [
            'date' => 'required|date',
        ];
        $this->validate($request, $rules);
        $serviceResponse = CalendarService::isHoliday($request['date']);
        if(!$serviceResponse) {
            $serviceResponse = 'There is no holiday on this day.';
        }
        return view('calendar-service-response', ['serviceResponse' => $serviceResponse]);
    }
}
