@php
function formatDate($date)
{
$slice_date = substr($date, 0, 5);
$formatedDate = Carbon\Carbon::createFromFormat('H:i', $slice_date)->format('h:i A');
return $formatedDate;
}
@endphp


@foreach ($current_data as $item)
<p class="m-0">Date : {{ $item['date']['gregorian']['date'] }}</p>
<p class="m-0">Weekday : {{ $item['date']['gregorian']['weekday']['en'] }}</p>
<p>Salat Time :</p>
<ul>
    <li>Fajr : {{ formatDate($item['timings']['Fajr']) }}
    </li>
    <li>Sunrise : {{ formatDate($item['timings']['Sunrise']) }}
    </li>

    <li>Dhuhr : {{ formatDate($item['timings']['Dhuhr']) }}

    </li>

    <li>Asr : {{ formatDate($item['timings']['Asr']) }}
    </li>

    <li>Sunset : {{ formatDate($item['timings']['Sunset']) }}</li>

    <li>Maghrib :
        {{ formatDate($item['timings']['Maghrib']) }}
    </li>
    <li>Isha :
        {{ formatDate($item['timings']['Isha']) }}
    </li>
</ul>
@endforeach