<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) != 'Laravel')
<img src="{{ asset('images/logo complete black.png') }}" class="w-26 h-10"  alt="">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
