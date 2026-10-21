<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style type="text/css">
        @media only screen and (max-width: 600px) {
            table[class="contenttable"] {
                width: 320px !important;
                border-width: 3px!important;
            }
            table[class="tablefull"] {
                width: 100% !important;
            }
            table[class="tablefull"] + table[class="tablefull"] td {
                padding-top: 0px !important;
            }
            table td[class="tablepadding"] {
                padding: 15px !important;
            }
        }
    </style>
</head>
<body style="margin:0; border: none; background:#f5f7f8">
@php
$payload = json_decode($booking->payload, true);
$request = json_decode($booking->request, true);
@endphp
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tr>
        <td align="center" valign="top"><table class="contenttable" border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff" style="border-width:1px; border-style: solid; border-collapse: separate; border-color:#ededed; margin-top:20px; font-family:Arial, Helvetica, sans-serif">
                <tr>
                    <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="100%" height="30">&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top" align="center">
                                    <a href="#">
                                        @if(auth()->user()->type == 'agency' || auth()->user()->type == 'white')
                                            @foreach(auth()->user()->logo as $key => $entry)
                                                <img src="{{ $entry['url'] }}" height="40"  style="padding-bottom: 0; display: inline !important;" />
                                            @endforeach
                                        @else
                                            @if($site_settings->print_logo)
                                                @foreach($site_settings->print_logo as $key => $entry)
                                                    <img src="{{ $entry['url'] }}" height="40" style="padding-bottom: 0; display: inline !important;" />
                                                @endforeach
                                            @else
                                                <h5>{{ $site_settings->name }}</h5>
                                            @endif
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" height="30">&nbsp;</td>
                            </tr>
                            <tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td style="padding:0px 20px;"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                            <tr>
                                <td style="border:4px solid #eee; border-radius:4px; padding:25px 0px;"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                        <tbody>
                                        <tr>
                                            <td style="font-size:14px; padding:0px 25px;" width="50"></td>
                                            @if(strtolower($booking->status) == 'confirmed')
                                                <td style="font-size:16px; font-weight:600; color:#777; line-height:26px; padding-right:20px;">Congratulations! Your flight booking is <span style="color:#28a745;">confirmed</span>.</td>
                                            @else
                                                <td style="font-size:16px; font-weight:600; color:#777; line-height:26px; padding-right:20px;">
                                                    <div class="text-center text-danger">Reservation is on hold. @if($booking->on_hold_expiry_date) To avoid cancellation, pay before {{ \Carbon\Carbon::parse($booking->on_hold_expiry_date)->setTimezone('Asia/Beirut')->format('d/m/Y H:i') }}@endif</div>
                                                </td>
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table></td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>

                <tr>
                    <td style="padding:20px;"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td style="font-size:14px; line-height:28px;"><span style="color:#7a7a7a;">Booking Reference Number -</span> <a style="outline:none; color:#0071cc; text-decoration:none;" href="#">{{ $booking->pnr }}</a></td>
                            </tr>
                            <tr>
                                <td style="font-size:14px; line-height:28px;"><span style="color:#7a7a7a;">Total </span> ${{ number_format($booking->amount_with_markup + $booking->taxes, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:14px; line-height:28px;">Thank you for booking your travel itinerary with us.</td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td class="tablepadding" style="background:#f8f8f8;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;padding:13px 20px;"><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="font-size:14px; line-height:20px;"><span style="color:#909090">Booked At:</span><br />
                                    <span style="color:#000000;display:inline-block">{{ $booking->created_at }}</span></td>
                                <td style="font-size:14px; line-height:20px;"><span style="color:#909090">Booked By:</span><br />
                                    <a style="outline:none; color:#0071cc; text-decoration:none;" href="#">{{ $booking->user->name }}</a></td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td style="padding:25px 20px 0px;">
                        <h3 style="margin:0; font-weight:normal; font-size: 15px; text-transform: uppercase; color: #c9245f!important">
                            <strong>{{ $booking->dep_from }} - {{ $booking->dep_to }}</strong>
                            <span style="color: #999999; font-size: 13px;">{{ \Carbon\Carbon::parse($booking->dep_from_date)->format('D, j M y') }}</span>
                        </h3>
                    </td>
                </tr>
                <tr>
                    <td class="tablepadding" style="padding:20px;"><table class="" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;">
                            <thead>
                            <tr>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Airline</td>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Departure</td>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Arrival</td>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($payload['departure']['stops']) && count($payload['departure']['stops']) > 0)
                                @foreach($payload['departure']['stops'] as $stop)
                                    <tr>
                                        <td width="25%" valign="top" style="font-size: 13px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:center;padding:7px;line-height:22px;">
                                            {{ $booking->airline }}<br />
                                            <span style="font-size:13px; color:#555;">{{ $stop['flight_number'] }}</span><br />
                                            <span style="font-size:11px; color:#555555;">
                                                <?php
                                                    $durationInMinutes = \Carbon\Carbon::parse($stop['departure_time'])->diffInMinutes(\Carbon\Carbon::parse($stop['arrival_time']));
                                                    if ($durationInMinutes < 60) {
                                                        // If less than an hour, display "in xx minutes"
                                                        $durationText = "{$durationInMinutes} minutes";
                                                    } else {
                                                        // If greater than an hour, display "in xx hour and xx minutes"
                                                        $hours = floor($durationInMinutes / 60);
                                                        $minutes = $durationInMinutes % 60;

                                                        $durationText = "{$hours} hour";
                                                        if ($minutes > 0) {
                                                            $durationText .= " and {$minutes} minutes";
                                                        }
                                                    }
                                                    ?>
                                                {{ $durationText }}
                                            </span>
                                        </td>
                                        <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $stop['from'] }} - {{ \Carbon\Carbon::parse($stop['departure_time'])->format('H:i') }}<br />
                                            <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($stop['departure_time'])->format('D, j M y') }}</span>
                                        </td>
                                        <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $stop['to'] }} - {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('H:i') }}<br />
                                            <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($stop['arrival_time'])->format('D, j M y') }}</span>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td width="25%" valign="top" style="font-size: 13px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:center;padding:7px;line-height:22px;">
                                        {{ $booking->airline }}<br />
                                        {{ $payload['departure']['flight_number'] }}<br/>
                                        <span style="font-size:13px; color:#555555;">Flight Duration:
                                            <?php
                                                $durationInMinutes = \Carbon\Carbon::parse($payload['departure']['departure_time'])->diffInMinutes(\Carbon\Carbon::parse($payload['departure']['arrival_time']));
                                                if ($durationInMinutes < 60) {
                                                    // If less than an hour, display "in xx minutes"
                                                    $durationText = "{$durationInMinutes} minutes";
                                                } else {
                                                    // If greater than an hour, display "in xx hour and xx minutes"
                                                    $hours = floor($durationInMinutes / 60);
                                                    $minutes = $durationInMinutes % 60;

                                                    $durationText = "{$hours} hour";
                                                    if ($minutes > 0) {
                                                        $durationText .= " and {$minutes} minutes";
                                                    }
                                                }
                                            ?>
                                            {{ $durationText }}
                                        </span>
                                    </td>
                                    <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $payload['departure']['from'] }}<br />
                                        <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($payload['departure']['departure_time'])->format('D, j M y') }}</span><br />
                                        <span style="font-size:13px; color:#555555;">Departure Time: {{ \Carbon\Carbon::parse($payload['departure']['departure_time'])->format('H:i') }}</span><br />
                                    </td>
                                    <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $payload['departure']['to'] }}<br />
                                        <span style="font-size:13px; color:#555;">Arrival Time: {{ \Carbon\Carbon::parse($payload['departure']['arrival_time'])->format('H:i') }}</span><br />
                                </tr>
                            @endif
                            </tbody>
                        </table></td>
                </tr>
                @if($booking->ret_from)
                    <tr>
                        <td style="padding:25px 20px 0px;">
                            <h3 style="margin:0; font-weight:normal; font-size: 15px; text-transform: uppercase; color: #c9245f!important">
                                <strong>{{ $booking->ret_from }} - {{ $booking->ret_to }}</strong>
                                <span style="color: #999999; font-size: 13px;">{{ \Carbon\Carbon::parse($booking->ret_from_date)->format('D, j M y') }}</span>
                            </h3>
                        </td>
                    </tr>
                    <tr>
                        <td class="tablepadding" style="padding:20px;"><table class="" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;">
                                <thead>
                                <tr>
                                    <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Airline</td>
                                    <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Departure</td>
                                    <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Arrival</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($payload['return']['stops']) && count($payload['return']['stops']) > 0)
                                    @foreach($payload['return']['stops'] as $stop)
                                        <tr>
                                            <td width="25%" valign="top" style="font-size: 13px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:center;padding:7px;line-height:22px;">
                                                {{ $booking->airline }}<br />
                                                <span style="font-size:13px; color:#555;">{{ $stop['flight_number'] }}</span><br />
                                                <span style="font-size:11px; color:#555555;">
                                                <?php
                                                        $durationInMinutes = \Carbon\Carbon::parse($stop['departure_time'])->diffInMinutes(\Carbon\Carbon::parse($stop['arrival_time']));
                                                        if ($durationInMinutes < 60) {
                                                            // If less than an hour, display "in xx minutes"
                                                            $durationText = "{$durationInMinutes} minutes";
                                                        } else {
                                                            // If greater than an hour, display "in xx hour and xx minutes"
                                                            $hours = floor($durationInMinutes / 60);
                                                            $minutes = $durationInMinutes % 60;

                                                            $durationText = "{$hours} hour";
                                                            if ($minutes > 0) {
                                                                $durationText .= " and {$minutes} minutes";
                                                            }
                                                        }
                                                        ?>
                                                    {{ $durationText }}
                                            </span>
                                            </td>
                                            <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $stop['from'] }} - {{ \Carbon\Carbon::parse($stop['departure_time'])->format('H:i') }}<br />
                                                <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($stop['departure_time'])->format('D, j M y') }}</span>
                                            </td>
                                            <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $stop['to'] }} - {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('H:i') }}<br />
                                                <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($stop['arrival_time'])->format('D, j M y') }}</span>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td width="25%" valign="top" style="font-size: 13px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:center;padding:7px;line-height:22px;">
                                            {{ $booking->airline }}<br />
                                            {{ $payload['return']['flight_number'] }}<br/>
                                            <span style="font-size:13px; color:#555555;">Flight Duration:
                                            <?php
                                                    $durationInMinutes = \Carbon\Carbon::parse($payload['return']['departure_time'])->diffInMinutes(\Carbon\Carbon::parse($payload['return']['arrival_time']));
                                                    if ($durationInMinutes < 60) {
                                                        // If less than an hour, display "in xx minutes"
                                                        $durationText = "{$durationInMinutes} minutes";
                                                    } else {
                                                        // If greater than an hour, display "in xx hour and xx minutes"
                                                        $hours = floor($durationInMinutes / 60);
                                                        $minutes = $durationInMinutes % 60;

                                                        $durationText = "{$hours} hour";
                                                        if ($minutes > 0) {
                                                            $durationText .= " and {$minutes} minutes";
                                                        }
                                                    }
                                                    ?>
                                                {{ $durationText }}
                                        </span>
                                        </td>
                                        <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $payload['return']['from'] }}<br />
                                            <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($payload['return']['departure_time'])->format('D, j M y') }}</span><br />
                                            <span style="font-size:13px; color:#555555;">Departure Time: {{ \Carbon\Carbon::parse($payload['return']['departure_time'])->format('H:i') }}</span><br />
                                        </td>
                                        <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $payload['return']['to'] }}<br />
                                            <span style="font-size:13px; color:#555;">Arrival Time: {{ \Carbon\Carbon::parse($payload['return']['arrival_time'])->format('H:i') }}</span><br />
                                    </tr>
                                @endif
                                </tbody>
                            </table></td>
                    </tr>
                @endif
                <tr>
                    <td style="padding:0px 20px 0px;"><h3 style="margin:0; font-weight:bold; font-size: 15px; text-transform: uppercase; color: #c9245f!important">
                            Passenger(s) Details
                        </h3></td>
                </tr>
                <tr>
                    <td class="tablepadding" style="padding:20px 20px 25px;"><table class="" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;">
                            <thead>
                            <tr>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Type</td>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Name</td>
                                <td align="center" style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Nationality</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($request['travellers'] as $traveler)
                                <tr>
                                    <td valign="top" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px;line-height:22px;">{{ $traveler['type'] }}</td>
                                    <td valign="top" align="left" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $traveler['first_name'] }} {{ $traveler['last_name'] }} </td>
                                    <td valign="top" align="center" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $traveler['nationality'] }} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table></td>
                </tr>
                @if(!empty($booking->contract))
                    <tr>
                        <td style="padding:25px 20px 0px; border-top:1px solid #e9e9e9;"><h3 style="margin:0; font-weight:normal; color:#444444;">
                                Rules
                            </h3></td>
                    </tr>
                    <tr>
                        <td class="tablepadding" style="padding:20px 20px;">
                            {!! $booking->contract->content !!}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td><table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#555555; font-family:Arial, Helvetica, sans-serif;">
                            <tbody>
                            <tr>
                                <td class="tablepadding" align="center" style="font-size:14px; line-height:32px; padding:34px 20px; border-top:1px solid #e9e9e9;"> Any Questions? Get in touch with our Customer Care team.<br />
                                    <a href="mailto:{{ $site_settings->email ?? '' }}" style="background-color:#0071cc; color:#ffffff; padding:8px 25px; border-radius:4px; font-size:14px; text-decoration:none; display:inline-block; text-transform:uppercase; margin-top:10px;">Contact Customer Care</a></td>
                            </tr>
                            <tr> </tr>
                            </tbody>
                        </table></td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#777777; font-family:Arial, Helvetica, sans-serif">
                <tbody>
                <tr>
                    <td class="tablepadding" style="padding:20px 0; border-collapse:collapse">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#777777; font-family:Arial, Helvetica, sans-serif">
                            <tbody>
                            <tr>
                                <td class="tablepadding" align="center" style="line-height:20px; padding-top:10px; padding-bottom:20px;">Copyright &copy; {{ date('Y') }} Samad Online - All Rights Reserved. </td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                </tbody>
            </table></td>
    </tr>
</table>
</body>
</html>
