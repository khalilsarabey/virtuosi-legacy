<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <style type="text/css" nonce="hhVc4lCXkLO8BGYHlnKbvQ">
        body, td, div, p, a, input {
            font-family: arial, sans-serif;
        }
    </style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="https://www.google.com/a/cpanel/khalilsarabey.com/images/favicon.ico"
          type="image/x-icon">
    <title>Booking Update</title>
    <style type="text/css" nonce="hhVc4lCXkLO8BGYHlnKbvQ">
        body, td {
            font-size: 12px
        }

        a:link, a:active {
            color: #1155CC;
            text-decoration: none
        }

        a:hover {
            text-decoration: underline;
            cursor: pointer
        }

        a:visited {
            color: #6611CC
        }

        img {
            border: 0px
        }

        pre {
            white-space: pre;
            white-space: -moz-pre-wrap;
            white-space: -o-pre-wrap;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 800px;
            overflow: auto;
        }

        .logo {
            left: -7px;
            position: relative;
        }
    </style>
<body>
<div class="bodycontainer">
    <div class="maincontent">
        <table width=100% cellpadding=0 cellspacing=0 border=0 class="message" style="max-width:700px;margin:50px auto">
            <tr>
                <td class="text-align: left">
                    @if($booking->user->type == 'agency')
                        @foreach(auth()->user()->logo as $key => $entry)
                            <img src="{{ $entry['url'] }}" height="70"/>
                        @endforeach
                    @else
                        <img src="https://samadonline.com/img/login_logo.png" height="70"/>
                    @endif
                </td>
                <td style="text-align:right">
                    <span
                        style="color:black;font-weight:bold">RESERVATION NUMBER : </span>{{ $booking->reference }}
                    <br>
                    <span style="color:black;font-weight:bold">TOTAL:</span> ${{ number_format($booking->total) }}
                </td>
            </tr>
        </table>
        <table width=100% cellpadding=0 cellspacing=0 border=0 class="message" style="max-width: 700px; margin: 0 auto;">
            <tr>
                <td>
                    <table width=100% cellspacing=0 border=0>
                        <tr>
                            <td>
                                <div style="overflow: hidden;">
                                    <font size=-1>

                                            <div style="width:95%;margin:0px auto">
                                                <div
                                                    style="margin-top:25px;padding-top:3px;padding-bottom:3px;padding-left:3px;font-size:17px;color:#cb225b!important;width:100%;font-family:Arial,HelveticaCondensed,Tahoma;font-weight:bold">
                                                    DEPARTURE
                                                </div>
                                                <table style="width:100%;border-collapse:collapse;border:none;margin-top:8px;margin-bottom:6px">
                                                    <tbody>
                                                    <tr style="height:55px;background:white;text-align:center;vertical-align:top">
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            FROM
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            TO
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            DEPARTURE DATE
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            AIRLINE
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                            FLIGHT NO.
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                            DEPARTURE TIME
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                            ARRIVAL TIME
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            BAGGAGE ALLOWANCE
                                                        </td>
                                                        <td style="color:#698911!important; font-size: 12px; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray;width:9%;padding-top:0px;vertical-align:middle">
                                                            DURATION
                                                        </td>
                                                    </tr>
                                                    <tr style="height:70px;text-align:center;vertical-align:middle; background: #eeeeee">
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                            {{ $booking->dep_from_city }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                            {{ $booking->dep_to_city }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                            {{ $booking->dep_date }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                            {{ $booking->depFlight->airline->name }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                            {{ $booking->depFlight->dep_flight_number }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                            {{ $booking->depFlight->dep_time }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                            {{ $booking->depFlight->dep_arr_time }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                            {{ $booking->depFlight->baggage_allowance }}
                                                        </td>
                                                        <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                            @php
                                                                $d = \Carbon\Carbon::parse($booking->depFlight->dep_time);
                                                                $a = \Carbon\Carbon::parse($booking->depFlight->dep_arr_time);
                                                            @endphp
                                                            <strong>{{ $a->diff($d)->format('%h.%I') }} Hours</strong>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                @if($booking->ret_from_city)
                                                    <div
                                                        style="margin-top:50px;padding-top:3px;padding-bottom:3px;padding-left:3px;font-size:17px;color:#cb225b!important;width:100%;font-family:Arial,HelveticaCondensed,Tahoma;font-weight:bold">
                                                        RETURN
                                                    </div>
                                                    <table
                                                        style="width:100%;border-collapse:collapse;border:none;margin-top:8px;margin-bottom:6px">
                                                        <tbody>
                                                        <tr style="height:55px;background:white;text-align:center;vertical-align:top">
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                                FROM
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                                TO
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                                DEPARTURE DATE
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                                AIRLINE
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                                FLIGHT NO.
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                                DEP TIME
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                                ARR TIME
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                                BAGGAGE
                                                            </td>
                                                            <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray;width:9%;padding-top:0px;vertical-align:middle">
                                                                DURATION
                                                            </td>
                                                        </tr>
                                                        <tr style="height:70px;text-align:center;vertical-align:middle; background: #eeeeee">
                                                            @if($booking->type == 'fixed')
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->ret_from_city }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->ret_to_city }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->depFlight->ret_dep_date }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->depFlight->airline->name }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->depFlight->ret_flight_number }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->depFlight->ret_dep_time }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->depFlight->ret_arr_time }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->depFlight->baggage_allowance }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    @php
                                                                        $d = \Carbon\Carbon::parse($booking->depFlight->ret_dep_time);
                                                                        $a = \Carbon\Carbon::parse($booking->depFlight->ret_arr_time);
                                                                    @endphp
                                                                    <strong>{{ $a->diff($d)->format('%h.%I') }}
                                                                        Hours</strong>
                                                                </td>
                                                            @else
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->retFlight->depFrom->city  }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->retFlight->depTo->city }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->retFlight->dep_date }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    {{ $booking->retFlight->airline->name }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->retFlight->flight_number }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->retFlight->dep_time }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->retFlight->dep_arr_time }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                    {{ $booking->retFlight->baggage_allowance }}
                                                                </td>
                                                                <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                    @php
                                                                        $d = \Carbon\Carbon::parse($booking->retFlight->dep_time);
                                                                        $a = \Carbon\Carbon::parse($booking->retFlight->arr_time);
                                                                    @endphp
                                                                    <strong>{{ $a->diff($d)->format('%h.%I') }}
                                                                        Hours</strong>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                @endif
                                                <div
                                                    style="margin-top:50px;padding-top:3px;padding-bottom:3px;padding-left:3px;font-size:17px;color:#cb225b!important;width:100%;font-family:Arial,HelveticaCondensed,Tahoma;font-weight:bold">
                                                    PASSENGERS
                                                </div>
                                                <table
                                                    style="width:100%;border-collapse:collapse;border:none;margin-top:8px;margin-bottom:6px">
                                                    <tbody>
                                                    <tr style="height:55px;background:white;text-align:center;vertical-align:top">
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            TITLE
                                                        </td>
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            FIRST NAME
                                                        </td>
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            LAST NAME
                                                        </td>
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; padding-top:0px;vertical-align:middle">
                                                            DATE OF BIRTH
                                                        </td>
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                            PHONE
                                                        </td>
                                                        <td style="color:#698911!important; font-weight: bold;font-family:Arial,HelveticaCondensed,Tahoma;border: 0; border-top:1px solid lightgray; border-bottom:1px solid lightgray; width:9%;padding-top:0px;vertical-align:middle">
                                                            NATIONALITY
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $tickets = $booking->tickets->unique(function ($item) {
                                                        return $item['title'].$item['first_name'].$item['last_name'].$item['date_of_birth'].$item['phone'];
                                                        });
                                                    @endphp
                                                    @foreach($tickets as $ticket)
                                                        <tr style="height:70px;text-align:center;vertical-align:middle; background: #eeeeee">
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                {{ $ticket->title }}
                                                            </td>
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                {{ $ticket->first_name }}
                                                            </td>
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                {{ $ticket->last_name }}
                                                            </td>
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0">
                                                                {{ $ticket->date_of_birth }}
                                                            </td>
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                {{ $ticket->phone  }}
                                                            </td>
                                                            <td style="color:black;font-family:Arial,HelveticaCondensed,Tahoma;border:0;width:9%">
                                                                {{ $ticket->nationality }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                @if(!empty($booking->depFlight->contract))
                                                <div
                                                    style="margin-top:25px;padding-top:3px;padding-bottom:3px;padding-left:3px;font-size:17px;color:#cb225b!important;width:100%;font-family:Arial,HelveticaCondensed,Tahoma;font-weight:bold">
                                                    RULES
                                                </div>
                                                    <div class="col-12">
                                                        <div class="mb-5 mt-5">
                                                            <div
                                                                style="border-bottom: 1px solid lightgrey; height:0px; width:100%;"></div>
                                                        </div>
                                                        </h4>
                                                        {!! $booking->depFlight->contract->content !!}
                                                    </div>
                                                @endif
                                                <div
                                                    style="width:100%;height:1px;background-color: #eeeeee; margin-top:50px;"></div>
                                                <table width="100%" style="max-width: 100%; margin: 0 auto"
                                                       cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td width="50%" style="width:50%;">
                                                            <div
                                                                style="padding-top:25px;font-size:14px;color:black;font-family:Calibri,Tahoma;font-weight:normal">
                                                                <span style="color: #aaa">BOOKED BY</span> <br>
                                                                {{ $booking->user->name }}
                                                            </div>
                                                        </td>
                                                        <td width="50%" style="text-align: right">
                                                            <div
                                                                style="padding-top:5px;font-size:14px;color:black;font-family:Calibri,Tahoma;font-weight:normal">
                                                                <span style="color: #aaa">BOOKING DATE</span><br>
                                                                {{ $booking->created_at }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                    </font>
                                </div>
                    </table>
        </table>
    </div>
</div>
</body>
</html>
