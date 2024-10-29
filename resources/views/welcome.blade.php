<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salat Times - October 2024 - Dhaka</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        .container {
            max-width: 1500px;
            margin: auto;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-between">
        <div class="" style="width: 50%">
            <div class=" text-center">
                <h2 class="text-center mb-4">Salat Times for {{ $salatTimes[0]['date']['gregorian']['month']['en'] }}
                    2024 -
                    Dhaka</h2>
            </div>


            <div class=" row">
                <div class="mb-4 col-4">
                    <label for="city">Select City</label>
                    <select id="city" class="form-select" aria-label="Default select example">
                        <option value="" selected>Select City</option>
                        @foreach ($cities as $index => $city)
                        <option value="{{ strtolower($city) }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- month start --}}
                <div class="mb-4 col-4">
                    <label for="month">Select Month</label>
                    <select id="month" class="form-select" aria-label="Default select example">
                        <option selected value="">Select Month</option>
                        @foreach ($months as $index => $item)
                        <option value="{{ strtolower($item) }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- month end --}}


                {{-- year start --}}
                @php
                $current_year = Carbon\Carbon::now()->format('Y');
                @endphp
                <div class="mb-4 col-4">
                    <label for="year">Select Year</label>
                    <select id="year" class="form-select" aria-label="Default select example">
                        <option selected value="">Select Year</option>
                        @foreach (range(2000, $current_year) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- year end --}}



                {{-- day start --}}
                <div class="mb-4 col-4">
                    <label for="day">Select Day</label>
                    <select id="day" class="form-select" aria-label="Default select example">
                        <option value="" selected>Select Day</option>
                        @for ($i = 1; $i <= 31; $i++) <option value="{{ $i < 10 ? '0' . $i : $i }}">{{ $i < 10 ? '0' .
                                $i : $i }}</option>
                                @endfor
                    </select>
                </div>
                {{-- day end --}}



                <div class="mb-4 col-4 d-flex align-items-end justify-content-between">
                    <button type="button" id="reset" class="btn btn-success">Reset</button>
                </div>

            </div>



            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Fajr</th>
                            <th scope="col">Sunrise</th>
                            <th scope="col">Dhuhr</th>
                            <th scope="col">Asr</th>
                            <th scope="col">Sunset</th>
                            <th scope="col">Maghrib</th>
                            <th scope="col">Isha</th>
                        </tr>
                    </thead>
                    <tbody id="table_body">

                    </tbody>
                </table>
            </div>
        </div>


        <div style="width: 50%" class="p-5">
            <div class=" row">
                <div class="mb-4 col-6">
                    <label for="date">Select Date</label>
                    <select id="date" class="form-select" aria-label="Default select example">
                        <option selected disabled>Select date</option>
                        @foreach ($salatTimes as $index => $item)
                        <option value="{{ $item['date']['timestamp'] }}">{{ $item['date']['readable'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- current data --}}
            <div id="single_record">
                @php
                function formatDate($date)
                {
                $slice_date = substr($date, 0, 5);
                return Carbon\Carbon::createFromFormat('H:i', $slice_date)->format('h:i A');
                }
                @endphp
                @foreach ($current_data as $item)
                <p class="m-0">Date : {{ $item['date']['gregorian']['date'] }}</p>
                <p class="m-0">Weekday : {{ $item['date']['gregorian']['weekday']['en'] }}</p>
                <p>Salat Time :</p>
                <ul>
                    <li>Fajr : {{ formatDate($item['timings']['Fajr']) }}</li>
                    <li>Sunrise : {{ formatDate($item['timings']['Sunrise']) }}</li>
                    <li>Dhuhr : {{ formatDate($item['timings']['Dhuhr']) }}</li>
                    <li>Asr : {{ formatDate($item['timings']['Asr']) }}</li>
                    <li>Sunset : {{ formatDate($item['timings']['Sunset']) }}</li>
                    <li>Maghrib : {{ formatDate($item['timings']['Maghrib']) }}</li>
                    <li>Isha : {{ formatDate($item['timings']['Isha']) }}</li>
                </ul>
                @endforeach
            </div>


        </div>


    </div>

    <!-- Bootstrap JavaScript and dependencies (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {


            // get data in table 
            let data = {};
            function getDataForTalbeRow(data = null) {
                $.ajax({
                    url: "{{ route('index') }}",
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $('#table_body').html(response.view);
                        if (response.errorMessage) {
                            alert(response.errorMessage)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }


            $('#city').on('change', function() {
                const cityValue = $(this).val();
                data['city'] = cityValue;
                getDataForTalbeRow(data);
            });

            $('#day').on('change', function() {
                const day = $(this).val();
                data['day'] = day;
                getDataForTalbeRow(data)    
            });



            $('#month').on('change', function() {
                const month = $(this).val();
                data['month'] = month;
                getDataForTalbeRow(data);
                // console.log(data);
            });
            $('#year').on('change', function() {
                const year = $(this).val();
                data['year'] = year;
                getDataForTalbeRow(data);
            });

            $('#reset').on('click', function() {
                $('#city').val('');
                $('#month').val('');
                $('#year').val('');
                $('#day').val('');
                data = {};
                getDataForTalbeRow();
            });
            getDataForTalbeRow();









            // get a single item
            function getData(obj) {
                $.ajax({
                    url: "{{ route('get_single_row') }}",
                    type: 'GET',
                    data: obj,
                    success: function(response) {
                        $('#single_record').html(response.view);
                        if (response.errorMessage) {
                            alert(response.errorMessage)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $('#date').on('change', function() {
                const obj = {};
                const value = $(this).val();
                obj['timestamp'] = value;
                getData(obj);
            });
        });
    </script>

</body>

</html>