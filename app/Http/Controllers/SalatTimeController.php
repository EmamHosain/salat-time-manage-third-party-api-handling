<?php

namespace App\Http\Controllers;

use Http;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalatTimeController extends Controller
{
    private $date;
    private $current_month;
    private $current_year;

    public function __construct()
    {
        $this->date = Carbon::now()->setTimezone('Asia/Dhaka');
        $this->current_month = $this->date->format('m'); // month number
        $this->current_year = $this->date->format('Y');
    }

    public function page()
    {
        $rajshahi = 'rajshahi';
        $response = Http::get('https://api.aladhan.com/v1/calendarByCity', [
            'city' => $rajshahi,
            'country' => 'bangladesh',
            'month' => $this->current_month,
            'year' => $this->current_year,
        ]);
        $current_date = $this->date->format('d-m-Y');
        $current_data = array_filter($response['data'], function ($item) use ($current_date) {
            if ($item['date']['gregorian']['date'] === $current_date) {
                return $item;
            }
        });
        if ($response->successful()) {
            $salatTimes = $response->json()['data'];

        } else {
            $salatTimes = [];
        }




        // dd($salatTimes);

        $cities = [
            "Chattogram",
            "Narayanganj",
            "Dhaka",
            "Khulna",
            "Barishal",
            "Sylhet",
            "Cumilla",
            "Rajshahi",
            "Narsingdi"
        ];
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        return view('welcome', [
            'cities' => $cities,
            'current_data' => $current_data,
            'salatTimes' => $salatTimes,
            'months' => $months
        ]);
    }

    public function index(Request $request)
    {
        $city = $request->query('city', 'rajshahi');
        $month = $request->query('month');
        if (!empty($month)) {
            $month = Carbon::parse($month)->format('m'); // convert_to_number
        } else {
            $month = $this->current_month;
        }
        $year = $request->query('year', $this->current_year);
        $day = $request->query('day', '');

        if ($request->ajax()) {
            $response = Http::get('https://api.aladhan.com/v1/calendarByCity', [
                'city' => $city,
                'country' => 'bangladesh',
                'month' => $month,
                'year' => $year,
            ]);
        } else {
            return response()->json([
                'errorMessage' => 'Ajax error'
            ]);
        }

        // dd($current_data);
        if ($response->successful()) {
            $salatTimes = $response->json()['data'];
        } else {
            $salatTimes = [];
        }

        if (!empty($day) && $day != '') {
            $salatTimes = array_filter($response['data'], function ($item) use ($day) {
                if ($item['date']['gregorian']['day'] == $day) {
                    return $item;
                } else {
                    return [];
                }
            });
        }


       

        return response()->json([
            'view' => view('single-table-row', [
                'salatTimes' => $salatTimes
            ])->render(),
        ]);

    }

    public function getSingleRow(Request $request)
    {
        $timestamp = $request->query('timestamp');
        if ($request->ajax()) {
            $response = Http::get('https://api.aladhan.com/v1/calendarByCity', [
                'city' => 'dhaka',
                'country' => 'bangladesh',
                'month' => $this->current_month,
                'year' => $this->current_year,
            ]);
            if (!empty($timestamp)) {
                $current_data = array_filter($response['data'], function ($item) use ($timestamp) {
                    if ($item['date']['timestamp'] === $timestamp) {
                        return $item;
                    }
                });
            } else {
                return response()->json([
                    'errorMessage' => 'Invalid timestamp'
                ]);
            }

            return response()->json([
                'view' => view('single-row', [
                    'current_data' => $current_data
                ])->render(),
            ]);
        } else {
            return response()->json([
                'errorMessage' => 'something went wrong'
            ]);
        }

    }
}
