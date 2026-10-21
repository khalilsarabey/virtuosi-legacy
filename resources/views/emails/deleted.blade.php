
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Deletion</title>
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
                                        @if($site_settings->print_logo)
                                            @foreach($site_settings->print_logo as $key => $entry)
                                                <img src="{{ $entry['url'] }}" height="40" style="padding-bottom: 0; display: inline !important;" />
                                            @endforeach
                                        @else
                                            <h5>{{ $site_settings->name }}</h5>
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
                                                <td style="font-size:16px; font-weight:600; color:#777; line-height:26px; padding-right:20px;">The following seat has been  <span style="color:red">deleted</span>.</td>
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
                                <td style="font-size:14px; line-height:28px;"><span style="color:#7a7a7a;">Booking Reference Number -</span> <a style="outline:none; color:#0071cc; text-decoration:none;" href="#">{{ $ticket->booking->reference }}</a></td>
                            </tr>
                            <tr>
                                <td style="font-size:14px; line-height:28px;"><span style="color:#7a7a7a;"></span></td>
                            </tr>
                            <tr>
                                <td style="font-size:14px; line-height:28px;"></td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td class="tablepadding" style="background:#f8f8f8;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;padding:13px 20px;"><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="font-size:14px; line-height:20px;"><span style="color:#909090">Booked At:</span><br />
                                    <span style="color:#000000;display:inline-block">{{ $ticket->created_at }}</span></td>
                                <td style="font-size:14px; line-height:20px;"><span style="color:#909090">Booked By:</span><br />
                                    <a style="outline:none; color:#0071cc; text-decoration:none;" href="#">{{ $ticket->booking->user->name }}</a></td>
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td style="padding:25px 20px 0px;"><h3 style="margin:0; font-weight:normal; font-size: 15px; text-transform: uppercase; color: #c9245f!important">
                            <strong>{{ strtoupper($ticket->type) }}</strong>
                        </h3></td>
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
                            <tr>
                                <td width="25%" valign="top" style="font-size: 13px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:center;padding:7px;line-height:22px;">
                                    @if($ticket->flight->airline->thumbnail)
                                        <img src="https://samadonline.com/airlines/{{ $ticket->flight->airline->thumbnail }}" class="img-fluid" />
                                    @endif
                                    <br />
                                    {{ $ticket->flight->airline->name }}<br /></td>
                                <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $ticket->dep_from_city }}<br />
                                    <span style="font-size:14px; color:#111111;">{{ \Carbon\Carbon::parse($ticket->dep_date)->format('D, j M y') }}</span><br />
                                    <span style="font-size:13px; color:#555555;">Departure Time: {{ $ticket->dep_time }}</span><br />
                                </td>
                                <td valign="top" style="border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $ticket->dep_to_city }}<br />
                                    <span style="font-size:13px; color:#555;">Arrival Time: {{ $ticket->dep_arr_time }}</span><br />
                                </td>
                                <span style="font-size:13px; color:#555;">Flight Number: {{ $ticket->flight->dep_flight_number }}</span><br />
                            </tr>
                            </tbody>
                        </table></td>
                </tr>
                <tr>
                    <td style="padding:0px 20px 0px;"><h3 style="margin:0; font-weight:bold; font-size: 15px; text-transform: uppercase; color: #c9245f!important">
                            Passenger(s) Details
                        </h3></td>
                </tr>
                <tr>
                    <td class="tablepadding" style="padding:20px 20px 25px;"><table class="" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;">
                            <thead>
                            <tr>
                                <td style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Name</td>
                                <td align="center" style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">DOB</td>
                                <td align="center" style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Phone</td>
                                <td align="center" style="font-size:13px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;padding:7px;color:#777777">Nationality</td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td valign="top" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px;line-height:22px;">
                                        {{ $ticket->title }} {{ $ticket->first_name }} {{ $ticket->last_name }}
                                    </td>
                                    <td valign="top" align="center" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $ticket->date_of_birth }} </td>
                                    <td valign="top" align="center" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $ticket->phone  }} </td>
                                    <td valign="top" align="center" style="font-size: 14px; border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:7px;line-height:22px;"> {{ $ticket->nationality }} </td>
                                </tr>
                            </tbody>
                        </table></td>
                </tr>
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
