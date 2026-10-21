<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ $site_settings->name }} - B2B Booking Engine</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/line-awesome.css">
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/animated-headline.css">
    <link rel="stylesheet" href="/css/jquery-ui.css">
    <link rel="stylesheet" href="/css/flag-icon.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/default.css">
    <link rel="stylesheet" href="/css/mobile.css">
    <style>
        .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            width: 100%!important
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 28px;
            user-select: none;
            -webkit-user-select: none
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .select2-container .select2-selection--single .select2-selection__clear {
            position: relative
        }

        .select2-container[dir=rtl] .select2-selection--single .select2-selection__rendered {
            padding-right: 8px;
            padding-left: 20px
        }

        .select2-container .select2-selection--multiple {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            min-height: 32px;
            user-select: none;
            -webkit-user-select: none
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered {
            display: inline-block;
            overflow: hidden;
            padding-left: 8px;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .select2-container .select2-search--inline {
            float: left
        }

        .selectdiv .select2-container .select2-search--inline {
            float: left;
            padding: 12px 6px;
            font-size: 18px
        }

        .select2-container .select2-search--inline .select2-search__field {
            box-sizing: border-box;
            border: 0;
            font-size: 100%;
            margin-top: 5px;
            padding: 0
        }

        .select2-container .select2-search--inline .select2-search__field::-webkit-search-cancel-button {
            -webkit-appearance: none
        }

        .select2-dropdown {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            z-index: 1051
        }

        .select2-results {
            border-color: #9e9e9e!important;
            display: block;
            border: 1px solid #9e9e9e;
            border-top: transparent;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px
        }

        .select2-results__options {
            list-style: none;
            margin: 0;
            padding: 0
        }

        .select2-results__option {
            padding: 6px;
            user-select: none;
            -webkit-user-select: none
        }

        .select2-results__option[aria-selected] {
            cursor: pointer
        }

        .select2-container--open .select2-dropdown {
            left: 0
        }

        .select2-container--open .select2-dropdown--above {
            background: #fff;
            padding: 0;
            width: 100%;
            z-index: 1;
            top: 0;
            left: 0;
            box-sizing: border-box;
            min-height: 60px;
            border-radius: 3px;
            box-shadow: #bac7d5 0 0 0 1px inset;
            background-color: #fff;
            font-size: 14px;
            transition: all .15s ease-in-out 0s;
            cursor: text
        }

        .select2-container--open .select2-dropdown--below {
            border-top: 0;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-radius: 4px
        }

        .select2-search--dropdown {
            display: block;
            padding: 4px
        }

        .select2-search--dropdown .select2-search__field {
            padding: 4px;
            width: 100%;
            box-sizing: border-box
        }

        .select2-search--dropdown .select2-search__field::-webkit-search-cancel-button {
            -webkit-appearance: none
        }

        .select2-search--dropdown.select2-search--hide {
            display: none
        }

        .select2-close-mask {
            border: 0;
            margin: 0;
            padding: 0;
            display: block;
            position: fixed;
            left: 0;
            top: 0;
            min-height: 100%;
            min-width: 100%;
            height: auto;
            width: auto;
            opacity: 0;
            z-index: 99;
            background-color: #fff;
            filter: alpha(opacity=0)
        }

        .select2-hidden-accessible {
            border: 0!important;
            clip: rect(0 0 0 0)!important;
            -webkit-clip-path: inset(50%)!important;
            clip-path: inset(50%)!important;
            height: 1px!important;
            overflow: hidden!important;
            padding: 0!important;
            position: absolute!important;
            width: 1px!important;
            white-space: nowrap!important
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 16px;
            color: rgb(0 0 0/68%);
            line-height: 24px;
            text-transform: capitalize;
            margin-top: -5px;
            text-align: left;
            font-size: 16px
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: 700
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #999
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 5px;
            right: 40px;
            width: 18px
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0
        }

        .select2-container--default[dir=rtl] .select2-selection--single .select2-selection__clear {
            float: left
        }

        .select2-container--default[dir=rtl] .select2-selection--single .select2-selection__arrow {
            left: 1px;
            right: auto
        }

        .select2-container--default.select2-container--disabled .select2-selection--single {
            background-color: #eee;
            cursor: default
        }

        .select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__clear {
            display: none
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 4px 5px
        }

        .select2-container--default .select2-selection--multiple {
            width: 100%;
            z-index: 1;
            top: 0;
            left: 0;
            box-sizing: border-box;
            min-height: 60px;
            border-radius: 3px;
            box-shadow: #bac7d5 0 0 0 1px inset;
            background-color: #fff;
            font-size: 14px;
            transition: all .15s ease-in-out 0s;
            cursor: text
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            box-sizing: border-box;
            list-style: none;
            margin: 0;
            padding: 0 5px;
            width: 100%
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            list-style: none
        }

        .select2-container--default .select2-selection--multiple .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: 700;
            margin-top: 5px;
            margin-right: 10px;
            padding: 1px
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            border: solid transparent 1px;
            background-color: #f5f7f9;
            border: 1px solid #e9ecf0;
            box-shadow: #eff2f5 0 0 0 1px;
            border-radius: 2px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin: 11px 6px 0;
            padding: 7px 15px;
            font-weight: 700;
            font-size: 14px;
            transition: color .15s ease-in-out 0s,box-shadow .15s ease-in-out 0s,background .15s ease-in-out 0s
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
            background: #e5eaef;
            box-shadow: none
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-weight: 700;
            margin-right: 2px;
            background: #bac7d5;
            border-radius: 10px;
            padding: 4px;
            height: 16px;
            line-height: 7px;
            margin-right: 8px
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #333
        }

        .select2-container--default[dir=rtl] .select2-selection--multiple .select2-selection__choice,.select2-container--default[dir=rtl] .select2-selection--multiple .select2-search--inline {
            float: right
        }

        .select2-container--default[dir=rtl] .select2-selection--multiple .select2-selection__choice {
            margin-left: 5px;
            margin-right: auto
        }

        .select2-container--default[dir=rtl] .select2-selection--multiple .select2-selection__choice__remove {
            margin-left: 2px;
            margin-right: auto
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            box-shadow: #0035c9 0 0 0 2px inset;
            outline: 0
        }

        .select2-container--default.select2-container--disabled .select2-selection--multiple {
            background-color: #eee;
            cursor: default
        }

        .select2-container--default.select2-container--disabled .select2-selection__choice__remove {
            display: none
        }

        .select2-container--default.select2-container--open.select2-container--below .select2-selection--single,.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #aaa
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            background: 0 0;
            border: 0;
            outline: 0;
            box-shadow: none;
            -webkit-appearance: textfield
        }

        .select2-container--default .select2-results>.select2-results__options {
            max-height: 200px;
            overflow-y: auto
        }

        .select2-container--default .select2-results__option[role=group] {
            padding: 0
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #999
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #ddd
        }

        .select2-container--default .select2-results__option .select2-results__option {
            padding-left: 1em
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__group {
            padding-left: 0
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__option {
            margin-left: -1em;
            padding-left: 2em
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
            margin-left: -2em;
            padding-left: 3em
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
            margin-left: -3em;
            padding-left: 4em
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
            margin-left: -4em;
            padding-left: 5em
        }

        .select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
            margin-left: -5em;
            padding-left: 6em
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #5897fb;
            color: #fff
        }

        .select2-container--default .select2-results__group {
            cursor: default;
            display: block;
            padding: 6px
        }

        .select2-container--classic .select2-selection--single {
            background-color: #f7f7f7;
            border: 1px solid #aaa;
            border-radius: 4px;
            outline: 0;
            background-image: -webkit-linear-gradient(top,#fff 50%,#eee 100%);
            background-image: -o-linear-gradient(top,#fff 50%,#eee 100%);
            background-image: linear-gradient(to bottom,#fff 50%,#eee 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF',endColorstr='#FFEEEEEE',GradientType=0)
        }

        .select2-container--classic .select2-selection--single:focus {
            border: 1px solid #5897fb
        }

        .select2-container--classic .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 28px
        }

        .select2-container--classic .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: 700;
            margin-right: 10px
        }

        .select2-container--classic .select2-selection--single .select2-selection__placeholder {
            color: #999
        }

        .select2-container--classic .select2-selection--single .select2-selection__arrow {
            background-color: #ddd;
            border: 0;
            border-left: 1px solid #aaa;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            height: 26px;
            position: absolute;
            top: 1px;
            right: 1px;
            width: 20px;
            background-image: -webkit-linear-gradient(top,#eee 50%,#ccc 100%);
            background-image: -o-linear-gradient(top,#eee 50%,#ccc 100%);
            background-image: linear-gradient(to bottom,#eee 50%,#ccc 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE',endColorstr='#FFCCCCCC',GradientType=0)
        }

        .select2-container--classic .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0
        }

        .select2-container--classic[dir=rtl] .select2-selection--single .select2-selection__clear {
            float: left
        }

        .select2-container--classic[dir=rtl] .select2-selection--single .select2-selection__arrow {
            border: 0;
            border-right: 1px solid #aaa;
            border-radius: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
            left: 1px;
            right: auto
        }

        .select2-container--classic.select2-container--open .select2-selection--single {
            border: 1px solid #5897fb
        }

        .select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow {
            background: 0 0;
            border: 0
        }

        .select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 4px 5px
        }

        .select2-container--classic.select2-container--open.select2-container--above .select2-selection--single {
            border-top: 0;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            background-image: -webkit-linear-gradient(top,#fff 0,#eee 50%);
            background-image: -o-linear-gradient(top,#fff 0,#eee 50%);
            background-image: linear-gradient(to bottom,#fff 0,#eee 50%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF',endColorstr='#FFEEEEEE',GradientType=0)
        }

        .select2-container--classic.select2-container--open.select2-container--below .select2-selection--single {
            border-bottom: 0;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            background-image: -webkit-linear-gradient(top,#eee 50%,#fff 100%);
            background-image: -o-linear-gradient(top,#eee 50%,#fff 100%);
            background-image: linear-gradient(to bottom,#eee 50%,#fff 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE',endColorstr='#FFFFFFFF',GradientType=0)
        }

        .select2-container--classic .select2-selection--multiple {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: text;
            outline: 0
        }

        .select2-container--classic .select2-selection--multiple:focus {
            border: 1px solid #5897fb
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__rendered {
            list-style: none;
            margin: 0;
            padding: 0 5px
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__clear {
            display: none
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice {
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
            color: #888;
            cursor: pointer;
            display: inline-block;
            font-weight: 700;
            margin-right: 2px
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #555
        }

        .select2-container--classic[dir=rtl] .select2-selection--multiple .select2-selection__choice {
            float: right;
            margin-left: 5px;
            margin-right: auto
        }

        .select2-container--classic[dir=rtl] .select2-selection--multiple .select2-selection__choice__remove {
            margin-left: 2px;
            margin-right: auto
        }

        .select2-container--classic.select2-container--open .select2-selection--multiple {
            border: 1px solid #5897fb
        }

        .select2-container--classic.select2-container--open.select2-container--above .select2-selection--multiple {
            border-top: 0;
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }

        .select2-container--classic.select2-container--open.select2-container--below .select2-selection--multiple {
            border-bottom: 0;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0
        }

        .select2-container--classic .select2-search--dropdown .select2-search__field {
            border: 1px solid #aaa;
            outline: 0
        }

        .select2-container--classic .select2-search--inline .select2-search__field {
            outline: 0;
            box-shadow: none
        }

        .select2-container--classic .select2-dropdown {
            background-color: #fff;
            border: 1px solid transparent
        }

        .select2-container--classic .select2-dropdown--above {
            border-bottom: 0
        }

        .select2-container--classic .select2-dropdown--below {
            border-top: 0
        }

        .select2-container--classic .select2-results>.select2-results__options {
            max-height: 200px;
            overflow-y: auto
        }

        .select2-container--classic .select2-results__option[role=group] {
            padding: 0
        }

        .select2-container--classic .select2-results__option[aria-disabled=true] {
            color: grey
        }

        .select2-container--classic .select2-results__option--highlighted[aria-selected] {
            background-color: #3875d7;
            color: #fff
        }

        .select2-container--classic .select2-results__group {
            cursor: default;
            display: block;
            padding: 6px
        }

        .select2-container--classic.select2-container--open .select2-dropdown {
            border-color: #5897fb
        }

        .categories .select2-selection__rendered {
            text-indent: 14px!important;
            font-size: 16px!important
        }

        .categories .select2-selection__arrow {
            top: 16px!important;
            right: 12px!important
        }

        .locations .select2 {
            margin-top: -76px
        }

        .input-items .select2-selection {
            letter-spacing: .5px;
            letter-spacing: 0;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            width: 100%;
            border: 1px solid #eee;
            outline: 0;
            color: #000;
            font-size: 14px;
            padding: 20px;
            height: 55px
        }

        .input-items .select2-selection:hover {
            background: rgba(206,206,206,.31);
            border-color: var(--theme)
        }

        .input-items .select2-selection:focus {
            background: rgba(206,206,206,.31);
            border-color: var(--theme)
        }

        .input-items .select2-selection:disabled {
            color: transparent
        }

        .select2-container--open .select2-dropdownz--below {
            top: -54px
        }

        .select2-search--dropdown {
            padding: 0
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-bottom: transparent!important;
            border-bottom: #fff;
            border-bottom-right-radius: 0!important;
            border-bottom-left-radius: 0!important;
            border-radius: 4px;
            outline: none;
            text-transform: capitalize;
            border-top: 1px solid #9e9e9e!important;
            height: 64px;
            background: #fff;
            -webkit-transition: all .3s;
            -moz-transition: all .3s;
            -ms-transition: all .3s;
            -o-transition: all .3s;
            transition: all .3s;
            padding: 24px;
            font-size: 16px!important;
            color: #000
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #000
        }

        .select2-dropdown {
            top: -64px;
            background-color: #fff;
            border: 0 solid #aaa;
            border-radius: 3px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            z-index: 1051
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border: 0;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNCcgaGVpZ2h0PScxNCcgdmlld0JveD0nMCAwIDI0IDI0JyBmaWxsPSdub25lJyBzdHJva2U9JyMwMDAwMDAnIHN0cm9rZS13aWR0aD0nMicgc3Ryb2tlLWxpbmVjYXA9J3JvdW5kJyBzdHJva2UtbGluZWpvaW49J3JvdW5kJz48cGF0aCBkPSdNNiA5bDYgNiA2LTYnIC8+PC9zdmc+)!important;
            font-size: 10px;
            position: absolute;
            top: 16px;
            right: 20px;
            width: 30px;
            height: 30px;
            padding: 12px;
            background: #eee;
            text-align: center;
            border-radius: 18px;
            background-repeat: no-repeat;
            background-position: center
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #000
        }

        .select2-container--default .select2-results>.select2-results__options {
            max-height: 250px;
            overflow-y: auto;
            -webkit-box-shadow: rgba(0,0,0,.37) 0 50px 100px,rgba(0,0,0,.22) 0 5px 50px;
            box-shadow: rgb(0 0 0/1%) 0 50px 100px,rgb(0 0 0/17%) 0 5px 50px
        }

        .select2-container--default .select2-results__option {
            transition: background-color .2s ease;
            display: flex;
            align-items: center;
            padding: 6px 15px 6px 16px;
            height: 54px;
            font-size: 14px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap
        }

        .select2-container--default .loading-results:before {
            right: 14px;
            content: "";
            position: absolute;
            top: 16px;
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #000;
            width: 22px;
            height: 22px;
            -webkit-animation: spin .3s linear infinite;
            animation: spin .3s linear infinite
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg)
            }

            100% {
                -webkit-transform: rotate(360deg)
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        .select2-results {
            will-change: height;
            transition: height .1s ease-in;
            overflow: hidden
        }

        .select2--fadein {
            will-change: opacity;
            animation: select2FadeIn .2s ease-in
        }

        @keyframes select2FadeIn {
            0% {
                opacity: 0
            }

            25% {
                opacity: .25
            }

            50% {
                opacity: .5
            }

            75% {
                opacity: .75
            }

            100% {
                opacity: 1
            }
        }

        .select2--fadeout {
            will-change: opacity;
            animation: select2FadeOut .1s ease-out
        }

        @keyframes select2FadeOut {
            0% {
                opacity: 1
            }

            25% {
                opacity: .75
            }

            50% {
                opacity: .5
            }

            75% {
                opacity: .25
            }

            100% {
                opacity: 0
            }
        }

        .select2custom .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 10px 16px
        }

        .input-items .select2-selection {
            letter-spacing: .5px;
            letter-spacing: 0;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            border-bottom-left-radius: 6px;
            width: 100%;
            border: 1px solid #c3c3c3;
            outline: 0;
            color: #000;
            font-size: 16px;
            padding: 24px 20px;
            height: 64px
        }

        .loading_home {
            content: "";
            border: 1.5px solid #cacaca;
            border-radius: 50%;
            border-top: 1.5px solid #000;
            width: 32px;
            height: 32px;
            -webkit-animation: spin .3s linear infinite;
            animation: spin .3s linear infinite
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg)
            }

            100% {
                -webkit-transform: rotate(360deg)
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        .bootstrap-select .bs-searchbox {
            padding: 0 10px!important
        }

        .bootstrap-select>select.bs-select-hidden,select.bs-select-hidden,select.selectpicker {
            display: none!important
        }

        .bootstrap-select {
            vertical-align: middle
        }

        .bootstrap-select>.dropdown-toggle {
            font-weight: 300!important;
            border-radius: 8px!important;
            box-shadow: none!important;
            color: #000!important;
            text-transform: capitalize!important;
            font-size: 16px;
            background: #fff;
            border: 1px solid #d4d4d4;
            height: 58px;
            position: relative;
            width: 100%;
            text-align: right;
            white-space: nowrap;
            display: -webkit-inline-box;
            display: -webkit-inline-flex;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        .bootstrap-select>.dropdown-toggle:after {
            margin-top: -1px
        }

        .bootstrap-select>.dropdown-toggle.bs-placeholder,.bootstrap-select>.dropdown-toggle.bs-placeholder:active,.bootstrap-select>.dropdown-toggle.bs-placeholder:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            color: #999
        }

        .bootstrap-select>.dropdown-toggle.bs-placeholder.btn-danger,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-danger:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-danger:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-danger:hover,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-dark,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-dark:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-dark:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-dark:hover,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info:hover,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-primary,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-primary:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-primary:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-primary:hover,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-secondary,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-secondary:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-secondary:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-secondary:hover,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-success,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-success:active,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-success:focus,.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-success:hover {
            color: rgba(255,255,255,.5)
        }

        .bootstrap-select>select {
            position: absolute!important;
            bottom: 0;
            left: 50%;
            display: block!important;
            width: .5px!important;
            height: 100%!important;
            padding: 0!important;
            opacity: 0!important;
            border: none;
            z-index: 0!important
        }

        .bootstrap-select>select.mobile-device {
            top: 0;
            left: 0;
            display: block!important;
            width: 100%!important;
            z-index: 2!important
        }

        .bootstrap-select.is-invalid .dropdown-toggle,.error .bootstrap-select .dropdown-toggle,.has-error .bootstrap-select .dropdown-toggle,.was-validated .bootstrap-select select:invalid+.dropdown-toggle {
            border-color: #b94a48
        }

        .bootstrap-select.is-valid .dropdown-toggle,.was-validated .bootstrap-select select:valid+.dropdown-toggle {
            border-color: #28a745
        }

        .bootstrap-select.fit-width {
            width: auto!important
        }

        .bootstrap-select.form-control {
            margin-bottom: 0;
            padding: 0;
            border: none;
            height: auto
        }

        :not(.input-group)>.bootstrap-select.form-control:not([class*=col-]) {
            width: 100%
        }

        .bootstrap-select.form-control.input-group-btn {
            float: none;
            z-index: auto
        }

        .form-inline .bootstrap-select,.form-inline .bootstrap-select.form-control:not([class*=col-]) {
            width: auto
        }

        .bootstrap-select:not(.input-group-btn),.bootstrap-select[class*=col-] {
            float: none;
            display: inline-block;
            margin-left: 0
        }

        .bootstrap-select.dropdown-menu-right,.bootstrap-select[class*=col-].dropdown-menu-right,.row .bootstrap-select[class*=col-].dropdown-menu-right {
            float: right
        }

        .form-group .bootstrap-select,.form-horizontal .bootstrap-select,.form-inline .bootstrap-select {
            margin-bottom: 0
        }

        .form-group-lg .bootstrap-select.form-control,.form-group-sm .bootstrap-select.form-control {
            padding: 0
        }

        .form-group-lg .bootstrap-select.form-control .dropdown-toggle,.form-group-sm .bootstrap-select.form-control .dropdown-toggle {
            height: 100%;
            font-size: inherit;
            line-height: inherit;
            border-radius: inherit
        }

        .bootstrap-select.form-control-lg .dropdown-toggle,.bootstrap-select.form-control-sm .dropdown-toggle {
            font-size: inherit;
            line-height: inherit;
            border-radius: inherit
        }

        .bootstrap-select.form-control-sm .dropdown-toggle {
            padding: .25rem .5rem
        }

        .bootstrap-select.form-control-lg .dropdown-toggle {
            padding: .5rem 1rem
        }

        .form-inline .bootstrap-select .form-control {
            width: 100%
        }

        .bootstrap-select.disabled,.bootstrap-select>.disabled {
            cursor: not-allowed
        }

        .bootstrap-select.disabled:focus,.bootstrap-select>.disabled:focus {
            outline: 0!important
        }

        .bootstrap-select.bs-container {
            position: absolute;
            top: 0;
            left: 0;
            height: 0!important;
            padding: 0!important
        }

        .bootstrap-select.bs-container .dropdown-menu {
            z-index: 1060
        }

        .bootstrap-select .dropdown-toggle .filter-option {
            display: flex;
            align-items: center;
            position: static;
            top: 0;
            left: 0;
            float: left;
            height: 100%;
            width: 100%;
            text-align: left;
            overflow: hidden;
            -webkit-box-flex: 0;
            -webkit-flex: 0 1 auto;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto
        }
    </style>
    <link rel="stylesheet" href="/css/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="/css/childstyle.css">
    <style>
        :focus:not(:focus-visible) {
            outline: 0;
            box-shadow: none;
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
    </style>
    <script src="/js/jquery.min.js"></script>
    <script>
        let baseurl = "https://{{get_domain()}}/";
        let root    = "https://{{get_domain()}}/";
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    @livewireStyles
</head>

<body id="fadein" class="fixed-nav" style="padding-top:70px !important;">
@if(session('message'))
    {{ session('message') }}
    <x-alert message="{{ session('message') }}" variant="success" role="alert" />
@endif
@if(session('error'))
    {{ session('error') }}
    <x-alert message="{{ session('error') }}" variant="danger" role="alert" />
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
                            <div class="align-items-center d-flex justify-content-between gap-3">
                                <div class="w-100">
                                    <nav class="px-5">
                                        <ul style="padding-top:10px!important" class="justify-content-around d-flex w-100">
                                            @php $items = \App\Models\Menu::where('agency_id', get_agency_id())->get(); @endphp
                                            <li><a href="/" title="home">Home</a></li>
                                            @if(count($items))
                                                @foreach($items as $item)
                                                    <li><a class="" href="{{ $item->link }}" title="about">{{ $item->name }}</a></li>
                                                @endforeach
                                            @endif
                                            @if(config('features.autopricing') && $site_settings->autopricing == 1 && auth()->user()->agency_id == 1)
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
                                                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'white-admin')
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
@yield('content')
@php $items = \App\Models\Menu::where('agency_id', get_agency_id())->get(); @endphp
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
                                    <a target="_blank" href="{{ $item->link }}" title="{{ $item->name }}">{{ $item->name }}</a>
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
<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/owl.carousel.min.js"></script>
<script src="/js/jquery.fancybox.min.js"></script>
<script src="/js/jquery.countTo.min.js"></script>
<script src="/js/animated-headline.js"></script>
<script src="/js/quantity-input.js"></script>
<script src="/js/select2.js"></script>
<script src="/js/main.js"></script>
<script src="/js/app.js"></script>
<link rel="stylesheet" href="/css/app.css">
@stack('scripts')
</body>
</html>
