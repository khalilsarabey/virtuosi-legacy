<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ $site_settings->name }} - B2B Booking Engine</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/line-awesome.css">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/animated-headline.css">
    <link rel="stylesheet" href="/css/jquery-ui.css">
    <link rel="stylesheet" href="/css/flag-icon.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/default.css">
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="stylesheet" href="/css/childstyle.css">

    <script src="/js/flatpickr.js"></script>
    <style>
        .flatpickr-calendar {
            background: transparent;
            opacity: 0;
            display: none;
            text-align: center;
            visibility: hidden;
            padding: 0;
            -webkit-animation: none;
            animation: none;
            direction: ltr;
            border: 0;
            font-size: 14px;
            line-height: 24px;
            border-radius: 5px;
            position: absolute;
            width: 307.875px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            background: #fff;
            -webkit-box-shadow: 0px 2px 16px 8px rgba(204,204,204,0.57);
            -moz-box-shadow: 0px 2px 16px 8px rgba(204,204,204,0.57);
            box-shadow: 0px 2px 26px 8px rgba(204,204,204,0.57);;
        }
        .flatpickr-calendar.open,
        .flatpickr-calendar.inline {
            opacity: 1;
            max-height: 640px;
            visibility: visible;
        }
        .flatpickr-calendar.open {
            display: inline-block;
            z-index: 99999;
        }
        .flatpickr-calendar.animate.open {
            -webkit-animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
            animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        .flatpickr-calendar.inline {
            display: block;
            position: relative;
            top: 2px;
        }
        .flatpickr-calendar.static {
            position: absolute;
            top: calc(100% + 2px);
        }
        .flatpickr-calendar.static.open {
            z-index: 999;
            display: block;
        }
        .flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+1) .flatpickr-day.inRange:nth-child(7n+7) {
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        .flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+2) .flatpickr-day.inRange:nth-child(7n+1) {
            -webkit-box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;
            box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;
        }
        .flatpickr-calendar .hasWeeks .dayContainer,
        .flatpickr-calendar .hasTime .dayContainer {
            border-bottom: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .flatpickr-calendar .hasWeeks .dayContainer {
            border-left: 0;
        }
        .flatpickr-calendar.hasTime .flatpickr-time {
            height: 40px;
            border-top: 1px solid #eee;
        }
        .flatpickr-calendar.noCalendar.hasTime .flatpickr-time {
            height: auto;
        }
        .flatpickr-calendar:before,
        .flatpickr-calendar:after {
            position: absolute;
            display: block;
            pointer-events: none;
            border: solid transparent;
            content: '';
            height: 0;
            width: 0;
            left: 22px;
        }
        .flatpickr-calendar.rightMost:before,
        .flatpickr-calendar.arrowRight:before,
        .flatpickr-calendar.rightMost:after,
        .flatpickr-calendar.arrowRight:after {
            left: auto;
            right: 22px;
        }
        .flatpickr-calendar.arrowCenter:before,
        .flatpickr-calendar.arrowCenter:after {
            left: 50%;
            right: 50%;
        }
        .flatpickr-calendar:before {
            border-width: 5px;
            margin: 0 -5px;
        }
        .flatpickr-calendar:after {
            border-width: 4px;
            margin: 0 -4px;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            bottom: 100%;
        }
        .flatpickr-calendar.arrowTop:before {
            border-bottom-color: #eee;
        }
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #fff;
        }
        .flatpickr-calendar.arrowBottom:before,
        .flatpickr-calendar.arrowBottom:after {
            top: 100%;
        }
        .flatpickr-calendar.arrowBottom:before {
            border-top-color: #eee;
        }
        .flatpickr-calendar.arrowBottom:after {
            border-top-color: #fff;
        }
        .flatpickr-calendar:focus {
            outline: 0;
        }
        .flatpickr-wrapper {
            position: relative;
            display: inline-block;
        }
        .flatpickr-months {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }
        .flatpickr-months .flatpickr-month {
            background: transparent;
            color: #3c3f40;
            fill: #3c3f40;
            height: 34px;
            line-height: 1;
            text-align: center;
            position: relative;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            overflow: hidden;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            text-decoration: none;
            cursor: pointer;
            position: absolute;
            top: 0;
            height: 34px;
            padding: 10px;
            z-index: 3;
            color: #3c3f40;
            fill: #3c3f40;
        }
        .flatpickr-months .flatpickr-prev-month.flatpickr-disabled,
        .flatpickr-months .flatpickr-next-month.flatpickr-disabled {
            display: none;
        }
        .flatpickr-months .flatpickr-prev-month i,
        .flatpickr-months .flatpickr-next-month i {
            position: relative;
        }
        .flatpickr-months .flatpickr-prev-month.flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month.flatpickr-prev-month {
            /*
                  /*rtl:begin:ignore*/
            /*
                  */
            left: 0;
            /*
                  /*rtl:end:ignore*/
            /*
                  */
        }
        /*
              /*rtl:begin:ignore*/
        /*
              /*rtl:end:ignore*/
        .flatpickr-months .flatpickr-prev-month.flatpickr-next-month,
        .flatpickr-months .flatpickr-next-month.flatpickr-next-month {
            /*
                  /*rtl:begin:ignore*/
            /*
                  */
            right: 0;
            /*
                  /*rtl:end:ignore*/
            /*
                  */
        }
        /*
              /*rtl:begin:ignore*/
        /*
              /*rtl:end:ignore*/
        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            color: #f64747;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #f64747;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 14px;
            height: 14px;
        }
        .flatpickr-months .flatpickr-prev-month svg path,
        .flatpickr-months .flatpickr-next-month svg path {
            -webkit-transition: fill 0.1s;
            transition: fill 0.1s;
            fill: inherit;
        }
        .numInputWrapper {
            position: relative;
            height: auto;
        }
        .numInputWrapper input,
        .numInputWrapper span {
            display: inline-block;
        }
        .numInputWrapper input {
            width: 100%;
        }
        .numInputWrapper input::-ms-clear {
            display: none;
        }
        .numInputWrapper input::-webkit-outer-spin-button,
        .numInputWrapper input::-webkit-inner-spin-button {
            margin: 0;
            -webkit-appearance: none;
        }
        .numInputWrapper span {
            position: absolute;
            right: 0;
            width: 14px;
            padding: 0 4px 0 2px;
            height: 50%;
            line-height: 50%;
            opacity: 0;
            cursor: pointer;
            border: 1px solid rgba(64,72,72,0.15);
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .numInputWrapper span:hover {
            background: rgba(0,0,0,0.1);
        }
        .numInputWrapper span:active {
            background: rgba(0,0,0,0.2);
        }
        .numInputWrapper span:after {
            display: block;
            content: "";
            position: absolute;
        }
        .numInputWrapper span.arrowUp {
            top: 0;
            border-bottom: 0;
        }
        .numInputWrapper span.arrowUp:after {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid rgba(64,72,72,0.6);
            top: 26%;
        }
        .numInputWrapper span.arrowDown {
            top: 50%;
        }
        .numInputWrapper span.arrowDown:after {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid rgba(64,72,72,0.6);
            top: 40%;
        }
        .numInputWrapper span svg {
            width: inherit;
            height: auto;
        }
        .numInputWrapper span svg path {
            fill: rgba(60,63,64,0.5);
        }
        .numInputWrapper:hover {
            background: rgba(0,0,0,0.05);
        }
        .numInputWrapper:hover span {
            opacity: 1;
        }
        .flatpickr-current-month {
            font-size: 135%;
            line-height: inherit;
            font-weight: 300;
            color: inherit;
            position: absolute;
            width: 75%;
            left: 12.5%;
            padding: 7.48px 0 0 0;
            line-height: 1;
            height: 34px;
            display: inline-block;
            text-align: center;
            -webkit-transform: translate3d(0px, 0px, 0px);
            transform: translate3d(0px, 0px, 0px);
        }
        .flatpickr-current-month span.cur-month {
            font-family: inherit;
            font-weight: 700;
            color: inherit;
            display: inline-block;
            margin-left: 0.5ch;
            padding: 0;
        }
        .flatpickr-current-month span.cur-month:hover {
            background: rgba(0,0,0,0.05);
        }
        .flatpickr-current-month .numInputWrapper {
            width: 6ch;
            width: 7ch\0;
            display: inline-block;
        }
        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #3c3f40;
        }
        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #3c3f40;
        }
        .flatpickr-current-month input.cur-year {
            background: transparent;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: inherit;
            cursor: text;
            padding: 0 0 0 0.5ch;
            margin: 0;
            display: inline-block;
            font-size: inherit;
            font-family: inherit;
            font-weight: 300;
            line-height: inherit;
            height: auto;
            border: 0;
            border-radius: 0;
            vertical-align: initial;
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }
        .flatpickr-current-month input.cur-year:focus {
            outline: 0;
        }
        .flatpickr-current-month input.cur-year[disabled],
        .flatpickr-current-month input.cur-year[disabled]:hover {
            font-size: 100%;
            color: rgba(60,63,64,0.5);
            background: transparent;
            pointer-events: none;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            appearance: menulist;
            background: transparent;
            border: none;
            border-radius: 0;
            box-sizing: border-box;
            color: inherit;
            cursor: pointer;
            font-size: inherit;
            font-family: inherit;
            font-weight: 300;
            height: auto;
            line-height: inherit;
            margin: -1px 0 0 0;
            outline: none;
            padding: 0 0 0 0.5ch;
            position: relative;
            vertical-align: initial;
            -webkit-box-sizing: border-box;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            width: auto;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months:focus,
        .flatpickr-current-month .flatpickr-monthDropdown-months:active {
            outline: none;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(0,0,0,0.05);
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: transparent;
            outline: none;
            padding: 0;
        }
        .flatpickr-weekdays {
            background: transparent;
            text-align: center;
            overflow: hidden;
            width: 100%;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            height: 28px;
        }
        .flatpickr-weekdays .flatpickr-weekdaycontainer {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        span.flatpickr-weekday {
            cursor: default;
            font-size: 90%;
            background: transparent;
            color: rgba(0,0,0,0.54);
            line-height: 1;
            margin: 0;
            text-align: center;
            display: block;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            font-weight: bolder;
        }
        .dayContainer,
        .flatpickr-weeks {
            padding: 1px 0 0 0;
        }
        .flatpickr-days {
            position: relative;
            overflow: hidden;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -webkit-align-items: flex-start;
            -ms-flex-align: start;
            align-items: flex-start;
            width: 307.875px;
        }
        .flatpickr-days:focus {
            outline: 0;
        }
        .dayContainer {
            padding: 0;
            outline: 0;
            text-align: left;
            width: 307.875px;
            min-width: 307.875px;
            max-width: 307.875px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            display: inline-block;
            display: -ms-flexbox;
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            -ms-flex-pack: justify;
            -webkit-justify-content: space-around;
            justify-content: space-around;
            -webkit-transform: translate3d(0px, 0px, 0px);
            transform: translate3d(0px, 0px, 0px);
            opacity: 1;
        }
        .dayContainer + .dayContainer {
            -webkit-box-shadow: -1px 0 0 #eee;
            box-shadow: -1px 0 0 #eee;
        }
        .flatpickr-day {
            background: none;
            border: 1px solid transparent;
            border-radius: 150px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: #404848;
            cursor: pointer;
            font-weight: 400;
            width: 14.2857143%;
            -webkit-flex-basis: 14.2857143%;
            -ms-flex-preferred-size: 14.2857143%;
            flex-basis: 14.2857143%;
            max-width: 39px;
            height: 39px;
            line-height: 39px;
            margin: 0;
            display: inline-block;
            position: relative;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
        }
        .flatpickr-day.inRange,
        .flatpickr-day.prevMonthDay.inRange,
        .flatpickr-day.nextMonthDay.inRange,
        .flatpickr-day.today.inRange,
        .flatpickr-day.prevMonthDay.today.inRange,
        .flatpickr-day.nextMonthDay.today.inRange,
        .flatpickr-day:hover,
        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover,
        .flatpickr-day:focus,
        .flatpickr-day.prevMonthDay:focus,
        .flatpickr-day.nextMonthDay:focus {
            cursor: pointer;
            outline: 0;
            background: #e9e9e9;
            border-color: #e9e9e9;
        }
        .flatpickr-day.today {
            border-color: #f64747;
        }
        .flatpickr-day.today:hover,
        .flatpickr-day.today:focus {
            border-color: #f64747;
            background: #f64747;
            color: #fff;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #4f99ff;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #fff;
            border-color: #4f99ff;
        }
        .flatpickr-day.selected.startRange,
        .flatpickr-day.startRange.startRange,
        .flatpickr-day.endRange.startRange {
            border-radius: 50px 0 0 50px;
        }
        .flatpickr-day.selected.endRange,
        .flatpickr-day.startRange.endRange,
        .flatpickr-day.endRange.endRange {
            border-radius: 0 50px 50px 0;
        }
        .flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)),
        .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)),
        .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)) {
            -webkit-box-shadow: -10px 0 0 #4f99ff;
            box-shadow: -10px 0 0 #4f99ff;
        }
        .flatpickr-day.selected.startRange.endRange,
        .flatpickr-day.startRange.startRange.endRange,
        .flatpickr-day.endRange.startRange.endRange {
            border-radius: 50px;
        }
        .flatpickr-day.inRange {
            border-radius: 0;
            -webkit-box-shadow: -5px 0 0 #e9e9e9, 5px 0 0 #e9e9e9;
            box-shadow: -5px 0 0 #e9e9e9, 5px 0 0 #e9e9e9;
        }
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover,
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay,
        .flatpickr-day.notAllowed,
        .flatpickr-day.notAllowed.prevMonthDay,
        .flatpickr-day.notAllowed.nextMonthDay {
            color: rgba(64,72,72,0.3);
            background: transparent;
            border-color: #e9e9e9;
            cursor: default;
        }
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            cursor: not-allowed;
            color: rgba(64,72,72,0.1);
        }
        .flatpickr-day.week.selected {
            border-radius: 0;
            -webkit-box-shadow: -5px 0 0 #4f99ff, 5px 0 0 #4f99ff;
            box-shadow: -5px 0 0 #4f99ff, 5px 0 0 #4f99ff;
        }
        .flatpickr-day.hidden {
            visibility: hidden;
        }
        .rangeMode .flatpickr-day {
            margin-top: 1px;
        }
        .flatpickr-weekwrapper {
            float: left;
        }
        .flatpickr-weekwrapper .flatpickr-weeks {
            padding: 0 12px;
            -webkit-box-shadow: 1px 0 0 #eee;
            box-shadow: 1px 0 0 #eee;
        }
        .flatpickr-weekwrapper .flatpickr-weekday {
            float: none;
            width: 100%;
            line-height: 28px;
        }
        .flatpickr-weekwrapper span.flatpickr-day,
        .flatpickr-weekwrapper span.flatpickr-day:hover {
            display: block;
            width: 100%;
            max-width: none;
            color: rgba(64,72,72,0.3);
            background: transparent;
            cursor: default;
            border: none;
        }
        .flatpickr-innerContainer {
            display: block;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            overflow: hidden;
        }
        .flatpickr-rContainer {
            display: inline-block;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .flatpickr-time {
            text-align: center;
            outline: 0;
            display: block;
            height: 0;
            line-height: 40px;
            max-height: 40px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            overflow: hidden;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }
        .flatpickr-time:after {
            content: "";
            display: table;
            clear: both;
        }
        .flatpickr-time .numInputWrapper {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            width: 40%;
            height: 40px;
            float: left;
        }
        .flatpickr-time .numInputWrapper span.arrowUp:after {
            border-bottom-color: #404848;
        }
        .flatpickr-time .numInputWrapper span.arrowDown:after {
            border-top-color: #404848;
        }
        .flatpickr-time.hasSeconds .numInputWrapper {
            width: 26%;
        }
        .flatpickr-time.time24hr .numInputWrapper {
            width: 49%;
        }
        .flatpickr-time input {
            background: transparent;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 0;
            border-radius: 0;
            text-align: center;
            margin: 0;
            padding: 0;
            height: inherit;
            line-height: inherit;
            color: #404848;
            font-size: 14px;
            position: relative;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }
        .flatpickr-time input.flatpickr-hour {
            font-weight: bold;
        }
        .flatpickr-time input.flatpickr-minute,
        .flatpickr-time input.flatpickr-second {
            font-weight: 400;
        }
        .flatpickr-time input:focus {
            outline: 0;
            border: 0;
        }
        .flatpickr-time .flatpickr-time-separator,
        .flatpickr-time .flatpickr-am-pm {
            height: inherit;
            float: left;
            line-height: inherit;
            color: #404848;
            font-weight: bold;
            width: 2%;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-align-self: center;
            -ms-flex-item-align: center;
            align-self: center;
        }
        .flatpickr-time .flatpickr-am-pm {
            outline: 0;
            width: 18%;
            cursor: pointer;
            text-align: center;
            font-weight: 400;
        }
        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover,
        .flatpickr-time input:focus,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: #f1f1f1;
        }
        .flatpickr-input[readonly] {
            cursor: pointer;
        }
        @-webkit-keyframes fpFadeInDown {
            from {
                opacity: 0;
                -webkit-transform: translate3d(0, -20px, 0);
                transform: translate3d(0, -20px, 0);
            }
            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }
        @keyframes fpFadeInDown {
            from {
                opacity: 0;
                -webkit-transform: translate3d(0, -20px, 0);
                transform: translate3d(0, -20px, 0);
            }
            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }
        .flatpickr-calendar {
            width: 307.875px;
        }
        .dayContainer {
            padding: 0;
            border-right: 0;
        }
        span.flatpickr-day,
        span.flatpickr-day.prevMonthDay,
        span.flatpickr-day.nextMonthDay {
            border-radius: 0 !important;
            border: 1px solid #e9e9e9;
            max-width: none;
            border-right-color: transparent;
        }
        span.flatpickr-day:nth-child(n+8),
        span.flatpickr-day.prevMonthDay:nth-child(n+8),
        span.flatpickr-day.nextMonthDay:nth-child(n+8) {

        }
        span.flatpickr-day:nth-child(7n-6),
        span.flatpickr-day.prevMonthDay:nth-child(7n-6),
        span.flatpickr-day.nextMonthDay:nth-child(7n-6) {
            border-left: 0;
        }
        span.flatpickr-day:nth-child(n+36),
        span.flatpickr-day.prevMonthDay:nth-child(n+36),
        span.flatpickr-day.nextMonthDay:nth-child(n+36) {
            border-bottom: 0;
        }
        span.flatpickr-day:nth-child(-n+7),
        span.flatpickr-day.prevMonthDay:nth-child(-n+7),
        span.flatpickr-day.nextMonthDay:nth-child(-n+7) {
            margin-top: 0;
        }
        span.flatpickr-day.today:not(.selected),
        span.flatpickr-day.prevMonthDay.today:not(.selected),
        span.flatpickr-day.nextMonthDay.today:not(.selected) {
            border-color: #e9e9e9;
            border-right-color: transparent;
            border-top-color: transparent;
            border-bottom-color: #f64747;
        }
        span.flatpickr-day.today:not(.selected):hover,
        span.flatpickr-day.prevMonthDay.today:not(.selected):hover,
        span.flatpickr-day.nextMonthDay.today:not(.selected):hover {
            border: 1px solid #f64747;
        }
        span.flatpickr-day.startRange,
        span.flatpickr-day.prevMonthDay.startRange,
        span.flatpickr-day.nextMonthDay.startRange,
        span.flatpickr-day.endRange,
        span.flatpickr-day.prevMonthDay.endRange,
        span.flatpickr-day.nextMonthDay.endRange {
            border-color: #4f99ff;
        }
        span.flatpickr-day.today,
        span.flatpickr-day.prevMonthDay.today,
        span.flatpickr-day.nextMonthDay.today,
        span.flatpickr-day.selected,
        span.flatpickr-day.prevMonthDay.selected,
        span.flatpickr-day.nextMonthDay.selected {
            z-index: 2;
        }
        .rangeMode .flatpickr-day {
            margin-top: -1px;
        }
        .flatpickr-weekwrapper .flatpickr-weeks {
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .flatpickr-weekwrapper span.flatpickr-day {
            border: 0;
            margin: -1px 0 0 -1px;
        }
        .hasWeeks .flatpickr-days {
            border-right: 0;
        }

        @media screen and (min-width:0\0) and (min-resolution: +72dpi) {
            span.flatpickr-day {
                display: block;
                -webkit-box-flex: 1;
                -webkit-flex: 1 0 auto;
                -ms-flex: 1 0 auto;
                flex: 1 0 auto;
            }
        }

    </style>
    <script src="/js/jquery.min.js"></script>
    <script>
        let baseurl = "https://{{get_domain()}}/";
        let root    = "https://{{get_domain()}}/";
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    @stack('styles')
    <x-brand-colors />
    <style>
        body {
            background-color: {{ $site_settings->color_body_bg ?? '#F0F0F0' }};
        }
        .section-tab:focus {
            border: 0 !important;
        }
        .section-tab .nav-tabs:focus {
            border: 0 !important;
        }
        .section-tab .nav-tabs .nav-link:focus {
            border: 0 !important;
        }
        .disable-styles:focus {
            outline: 0 !important;
            box-shadow: none !important;
            color: inherit !important;
            background: inherit !important;
            border: inherit !important;
        }
        .disable-styles:active {
            outline: 0 !important;
            box-shadow: none !important;
            color: inherit !important;
            background: inherit !important;
            border: inherit !important;
        }
        .disable-styles:visited {
            outline: 0 !important;
            box-shadow: none !important;
            color: inherit !important;
            background: inherit !important;
            border: inherit !important;
        }
    </style>
    @livewireStyles
</head>

<body id="fadein" class="fixed-nav" style="padding-top:70px !important;">
@if($site_settings->tawk == 1)
    @php
        $email = auth()->user()->email;
        $api = $site_settings->tawk_api;
    @endphp
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {};
        Tawk_API.visitor = {
            name: '{{ auth()->user()->name }}',
            email: '{{ $email }}',
            hash: '{{ hash_hmac('sha256', $email, $api) }}'
        };
        var Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/{{ $site_settings->tawk_api }}/{{ $site_settings->tawk_widget_id }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif

@if(session('message'))
    <x-alert message="{{ session('message') }}" variant="success" role="alert"/>
@endif
@if(session('error'))
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger mt-4">{{ session('error') }}</div>
            </div>
        </div>
    </div>
@endif
<header class="header-area">
    <div class="header-menu-wrapper padding-right-100px padding-left-100px d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="menu-wrapper d-flex">
                        <div class="logo">
                            <a href="/" class="py-3">
                                @if($site_settings->logo->first())
                                    <img style="max-height:{{ $site_settings->max_logo_height ?? 45 }}px; background:transparent; padding:4px;" src="{{ $site_settings->logo->first()['url'] }}" alt="logo">
                                @else
                                    <h5>{{ $site_settings->name }}</h5>
                                @endif
                            </a>
                            <div class="menu-toggler">
                                <i class="la la-bars"></i>
                                <i class="la la-times"></i>
                            </div>
                        </div>
                        <div class="main-menu-content w-100">
                            <div class="align-items-center d-flex justify-content-lg-between flex-column flex-lg-row gap-3">
                                <div class="w-100">
                                    <nav class="px-5 bg-transparent">
                                        <ul style="padding-top:10px!important" class="justify-content-lg-around d-flex flex-column flex-lg-row w-100">
                                            @php $items = \App\Models\Menu::where('agency_id', get_agency_id())->get(); @endphp
                                            <li><a href="/" title="home">Home</a></li>
                                            @if(count($items))
                                                @foreach($items as $item)
                                                    <li><a class="" href="{{ $item->link }}" title="about">{{ $item->name }}</a></li>
                                                @endforeach
                                            @endif
                                            @if(config('features.autopricing') && $site_settings->autopricing == 1 && auth()->user()->agency_id == 1 && (auth()->user()->auto_pricing == 1 || auth()->user()->type == 'user' || auth()->user()->type == 'admin'))
                                                <li><a href="{{ route('frontend.auto-pricing') }}" title="auto pricing">Ticketing (Auto Price)</a></li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                                <div class="d-flex header-top-bar">
                                    <div class="header-right-action pt-1 pe-2">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="ACCOUNT"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg class="pe-1" xmlns="http://www.w3.org/2000/svg" width="20"
                                                     height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                Account<i class="la la-angle-down"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="ACCOUNT">
                                                @if(auth()->user())
                                                    @if(auth()->user()->type == 'agency' || auth()->user()->type == 'white' || auth()->user()->type == 'user')
                                                        <li><a class="dropdown-item text-left" href="{{ route('frontend.my-bookings') }}">My Bookings</a></li>
                                                        <li><a class="dropdown-item text-left" href="{{ route('frontend.my-profile') }}">My Account</a></li>
                                                    @endif
                                                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'white-admin' || auth()->user()->type == 'accounting')
                                                        <li><a class="dropdown-item text-left " href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                                    @endif
                                                @endif
                                                <li class="text-left">
                                                    <form action="{{route('logout')}}" method="post">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item justify-content-start">Logout</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
{{ $slot }}
<style>
    .footer {
        background-color: {{ $site_settings->color_footer_bg ?? '#000000' }};
        margin-top: 100px;
    }
    .footer-logo {
        width: 160px;
        margin-bottom: 15px;
    }
    .footer .widget .widget-title {
        font-size: 20px;
        display: inline-block;
        position: relative;
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
    }
    /* first footer */
    .first-footer {
        padding: 90px 0 0 0;
        /*    border-bottom: 1px solid rgba(255, 255, 255, 0.1);*/
    }
    /* footer icon */
    .icon-footer {
        width: 65px;
        max-height: 65px;
        min-height: 65px;
        min-width: 65px;
        background-color: {{ $site_settings->color_footer_icons_bg }};
        color: {{ $site_settings->color_footer_icons_color ?? '#ffffff' }};
        font-size: 35px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .icon-footer i {
        width: 100%;
        text-align: center;
    }
    /* footer link */
    .links.dark {
        border-style: solid;
        border-width: 1px;
        border-color: rgba(255, 255, 255, 0.1);
        background-color: {{ $site_settings->color_footer_bg_light ?? $site_settings->color_footer_bg }};
        box-shadow: none;
        border-radius: 0px;
    }
    .links.dark.footer-contact-links {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 45px;
        padding: 20px 20px 20px;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
    }
    .footer-contact-links-wrapper {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        width: 100%;
        max-width: 1032px;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }
    .footer-contact-links-divider {
        width: 1px;
        min-height: 100%;
        margin-right: 20px;
        margin-left: 20px;
        background-color: {{ $site_settings->color_footer_bg ?? '#ffffff' }};
    }
    .footer-contact-link-wrapper {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        color: #fff;
        text-decoration: none;
    }
    .image-wrapper.footer-contact-link-icon {
        margin-right: 22px;
    }
    .footer-contact-link-wrapper h6 {
        color: {{ $site_settings->color_footer_icons_color }};
        font-size: 16px;
        margin-bottom: 0px;
    }
    .footer-contact-link-wrapper p {
        color: {{ $site_settings->color_footer_icons_color }};
        font-size: 16px;
        margin-bottom: 0px;
    }
    /* second footer */
    .second-footer {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    .second-footer .widget-area .widget {
        padding: 45px 0;
    }
    .second-footer .widget-area .widget p {
        color: rgba(0,0,0,0.8);
    }
    /* usefull links */
    .footer .usful-links ul {
        margin: 0;
        padding: 0;
    }
    .footer .usful-links ul li {
        margin-bottom: 5px;
    }
    .footer .usful-links ul li:hover {
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
    }
    .footer .usful-links ul li i {
        font-size: 7px;
        margin-right: 3px;
    }
    .footer .usful-links ul li a {
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
        position: relative;
        padding-left: 0px;
    }
    .footer .usful-links ul li a:hover {
        color: #2095AE;
    }
    /* bottom footer */
    .copyright {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .copyright p {
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
        font-size: 14px;
    }
    .copyright p a {
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
    }
    .copyright p a:hover {
        color: #fff;
    }
    .copyright p span {
        color: {{ $site_settings->color_footer_text ?? 'rgba(0,0,0,0.8)' }};
        padding: 0 5px;
    }
    /* social icons */
    .social-icons li {
        display: inline-block;
        border: none;
        z-index: 1;
        position: relative;
        width: 33px;
        height: 33px;
        line-height: 35px;
        text-align: center;
        background: {{ $site_settings->color_footer_icons_bg ?? '#000000' }};
        margin-right: 5px;
        -webkit-transition: all .4s;
        -o-transition: all .4s;
        transition: all .4s;
        border-radius: 100%;
    }
    .social-icons li a {
        display: block;
        text-align: center;
        color: {{ $site_settings->color_footer_icons_color ?? '#ffffff' }};
    }
    .footer .social-icons li > a {
        font-size: 22px;
    }
    .social-icons li a:hover {
        background: {{ $site_settings->color_footer_bg }};
        color: {{ $site_settings->color_footer_text }};
        border-radius: 100%;
    }
    /* newsletter */
    .widget-newsletter {
        position: relative;
    }
    .widget-newsletter input {
        height: 60px;
        width: 100%;
        border: none;
        background: #fff;
        font-size: 16px;
        color: {{ $site_settings->primary_color ?? 'rgba(0,0,0,0.8)' }};
        outline: none;
        padding: 0 90px 0 20px;
    }
    .widget-newsletter button {
        height: 50px;
        border: none;
        background: {{ $site_settings->color_footer_icons_bg ?? '#000000' }};
        font-size: 16px;
        color: {{ $site_settings->color_footer_icons_color ?? '#fff' }};
        outline: none;
        padding: 0 25px;
        cursor: pointer;
        display: inline-block;
        position: absolute;
        top: 5px;
        right: 5px;
    }
    .widget-newsletter button:hover {
        background: #1288a2;
    }
    @media screen and (max-width: 767px) {
        .footer-contact-links-wrapper {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }
        .footer-contact-links-divider {
            width: auto;
            min-height: 1px;
            margin: 20px 0px;
        }
    }
</style>
<footer class="footer clearfix">
    <div class="container">
        <!-- First footer -->
        <div class="first-footer">
            <div class="row">
                <div class="col-md-12">
                    <div class="links dark footer-contact-links">
                        <div class="footer-contact-links-wrapper">
                            <div class="footer-contact-link-wrapper">
                                <div class="image-wrapper footer-contact-link-icon">
                                    <div class="icon-footer">
                                        <i class="las la-phone"></i>
                                    </div>
                                </div>
                                <div class="footer-contact-link-content">
                                    <h6>Phone</h6>
                                    <p style="white-space: nowrap;">{{ $site_settings->contact_phone }}</p>
                                </div>
                            </div>
                            <div class="footer-contact-links-divider"></div>
                            <div class="footer-contact-link-wrapper">
                                <div class="image-wrapper footer-contact-link-icon">
                                    <div class="icon-footer"> <i class="las la-at"></i> </div>
                                </div>
                                <div class="footer-contact-link-content">
                                    <h6>Email</h6>
                                    <p style="white-space: nowrap;">{{ $site_settings->contact_email }}</p>
                                </div>
                            </div>
                            <div class="footer-contact-links-divider"></div>
                            <div class="footer-contact-link-wrapper">
                                <div class="image-wrapper footer-contact-link-icon">
                                    <div class="icon-footer"> <i class="las la-map"></i> </div>
                                </div>
                                <div class="footer-contact-link-content">
                                    <h6>Address</h6>
                                    <p>{{ $site_settings->contact_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Second footer -->
        <div class="second-footer">
            <div class="row">
                <!-- about & social icons -->
                <div class="col-md-4 widget-area">
                    <div class="widget clearfix">
                        <div class="footer-logo">
                            @if($site_settings->logo->first())
                            <img class="img-fluid" src="{{ $site_settings->logo->first()['url'] }}" alt="">
                            @endif
                        </div>
                        <div class="widget-text">
                            <p></p>
                            <div class="social-icons mt-3">
                                <ul class="list-inline">
                                    @if($site_settings->facebook)
                                    <li><a href="{{ $site_settings->facebook }}"><i class="lab la-facebook"></i></a></li>
                                    @endif
                                    @if($site_settings->instagram)
                                    <li><a href="{{ $site_settings->instagram }}"><i class="lab la-instagram"></i></a></li>
                                    @endif
                                    @if($site_settings->twitter)
                                    <li><a href="{{ $site_settings->twitter }}"><i class="lab la-twitter"></i></a></li>
                                    @endif
                                    @if($site_settings->linkedin)
                                    <li><a href="{{ $site_settings->linkedin }}"><i class="lab la-linkedin"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- quick links -->
                <div class="col-md-3 offset-md-1 widget-area">
                    <div class="widget clearfix usful-links">
                        @if(count($items))
                        <h3 class="widget-title">Quick Links</h3>
                        @endif
                        <ul>
                            @foreach($items as $item)
                                <li class="my-2">
                                    <a href="{{ $item->link }}" title="{{ $item->name }}">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- subscribe -->
                <div class="col-md-4 widget-area">
                    <div class="widget clearfix">
                        <h3 class="widget-title">Subscribe</h3>
                        <p class="my-2">Sign up to our newsletter</p>
                        <div class="widget-newsletter">
                            <form action="#">
                                <input type="email" placeholder="Email Address" required="">
                                <button type="submit">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom footer -->
        <div class="bottom-footer-text">
            <div class="row copyright">
                <div class="col-12 col-md-6">
                    <p class="mb-0">©{{ date('Y') }} <a href="#">{{ $site_settings->name }}</a>. All rights reserved.</p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="text-right small">Powered by &nbsp;<a href="https://www.virtuosi.com" target="_blank" class=" waves-effect"><strong>&nbsp;VIRTUOSI TRAVEL - BETA</strong></a></div>
                </div>
            </div>
        </div>
    </div>
</footer>

@livewireScripts
<script>
    // A form control named "children" shadows form.children, which breaks Livewire's DOM
    // morphing, so those inputs carry data-form-name and get their name back on submit.
    document.addEventListener('formdata', function (e) {
        e.target.querySelectorAll('[data-form-name]').forEach(function (input) {
            if (!input.disabled) e.formData.set(input.dataset.formName, input.value);
        });
    }, true);
</script>

<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/animated-headline.js"></script>
<script src="/js/select2.js"></script>
<script src="/js/main.js"></script>
<link rel="stylesheet" href="/css/app.css">
@stack('scripts')
</body>
</html>
