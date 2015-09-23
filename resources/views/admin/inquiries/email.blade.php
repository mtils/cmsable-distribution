@extends('email')
@section('content')
        <table cellpadding="10" cellspacing="0" border="0" align="left" id="text-table">
            <tr>
                <td width="768" valign="top">
                    <h3>{{ $subject }}</h3>
                    <br/><br/>
                    {!! $body !!}
                </td>
            </tr>
        </table>
@endsection