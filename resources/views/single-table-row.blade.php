@php
function formatDate($date)
{
$slice_date = substr($date, 0, 5);
return Carbon\Carbon::createFromFormat('H:i', $slice_date)->format('h:i A');
}
@endphp

@if (is_array($salatTimes) && count($salatTimes) === 0)
<tr>
    <td colspan="8" style="text-align: center;">No Data Available</td>
</tr>
@else
@foreach ($salatTimes as $day)
<tr>
    <td>{{ $day['date']['gregorian']['date'] }}</td>
    <td>{{ formatDate($day['timings']['Fajr']) }}</td>
    <td>{{ formatDate($day['timings']['Sunrise']) }}</td>
    <td>{{ formatDate($day['timings']['Dhuhr']) }}</td>
    <td>{{ formatDate($day['timings']['Asr']) }}</td>
    <td>{{ formatDate($day['timings']['Sunset']) }}</td>
    <td>{{ formatDate($day['timings']['Maghrib']) }}</td>
    <td>{{ formatDate($day['timings']['Isha']) }}</td>
</tr>
@endforeach
@endif