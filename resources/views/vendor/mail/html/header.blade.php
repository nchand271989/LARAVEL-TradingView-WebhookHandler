@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'TradeBot')
<img src="https://www.svgrepo.com/show/499831/target.svg" class="logo" alt="TradeBot Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
