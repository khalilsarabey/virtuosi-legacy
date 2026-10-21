<style>
    :focus:not(:focus-visible) {
        outline: 0;
        box-shadow: none;
    }

    .ui-datepicker table {
        background: rgba(255, 255, 255, 1);
    }

    #search-results {
        scroll-margin-top: 100px;
    }
    .text-primary {
        color: {{ $site_settings->primary_color ?? '#000000' }} !important;
    }
    .text-success {
        color: {{ $site_settings->color_success ?? '#000000' }} !important;
    }
    .bg-primary {
        background-color: {{ $site_settings->primary_color ?? '#000000' }} !important;
    }
    .btn-primary {
        background-color: {{ $site_settings->primary_color ?? '#000000' }} !important;
        border-color: {{ $site_settings->primary_color ?? '#000000' }} !important;
    }
    .btn-success {
        background-color: {{ $site_settings->color_success ?? '#000000' }} !important;
        border-color: {{ $site_settings->color_success ?? '#000000' }} !important;
    }
    .btn-secondary {
        background-color: {{ $site_settings->secondary_color ?? '#000000' }} !important;
        border-color: {{ $site_settings->secondary_color ?? '#000000' }} !important;
    }
    .btn-danger {
        background-color: {{ $site_settings->color_danger ?? '#000000' }} !important;
        border-color: {{ $site_settings->color_danger ?? '#000000' }} !important;
    }
    .bg-success {
        background-color: {{ $site_settings->color_success ?? '#000000' }} !important;
    }
    .bg-success:hover {
        background-color: {{ $site_settings->color_success ?? '#000000' }} !important;
    }
    .btn-outline-primary {
        color: {{ $site_settings->primary_color ?? '#000000' }} !important;
        border-color: {{ $site_settings->primary_color ?? '#000000' }} !important;
    }
    .btn-outline-primary:hover {
        color: white !important;
        background: {{ $site_settings->primary_color ?? '#000000' }};
        border-color: {{ $site_settings->primary_color ?? '#000000' }} !important;
    }
    :root {
        --blue: {{ $site_settings->color_blue ?? '#158da0' }};
        --indigo: {{ $site_settings->color_indigo ?? '#6610f2' }};
        --purple: {{ $site_settings->color_purple ?? '#6f42c1' }};
        --pink: {{ $site_settings->color_pink ?? '#c9245f' }};
        --red: {{ $site_settings->color_red ?? '#c9245f' }};
        --orange: {{ $site_settings->color_orange ?? '#fd7e14' }};
        --yellow: {{ $site_settings->color_yellow ?? '#f4d449' }};
        --green: {{ $site_settings->color_green ?? '#869e3e' }};
        --teal: {{ $site_settings->color_teal ?? '#20c997' }};
        --cyan: {{ $site_settings->color_cyan ?? '#17a2b8' }};
        --white: {{ $site_settings->color_white ?? '#fff' }};
        --gray: {{ $site_settings->color_gray ?? '#6c757d' }};
        --gray-dark: {{ $site_settings->color_gray_dark ?? '#343a40' }};
        --primary: {{ $site_settings->primary_color ?? '#000000' }};
        --secondary: {{ $site_settings->secondary_color1 ?? '#000000' }};
        --success: {{ $site_settings->color_success ?? '#869e3e' }};
        --info: {{ $site_settings->color_info ?? '#17a2b8' }};
        --warning: {{ $site_settings->color_warning ?? '#f4d449' }};
        --danger: {{ $site_settings->color_danger ?? '#c9245f' }};
        --light: {{ $site_settings->color_light ?? '#f8f9fa' }};
        --theme: {{ $site_settings->primary_color ?? '#000000' }};
    }

    .dropdown-menu {
        background: var(--primary);
        border-radius: 3px !important;
    }
    .dropdown-menu .dropdown-item {
        color: #fff
    }
    textarea:focus,
    textarea.form-control:focus,
    input.form-control:focus,
    input[type=text]:focus,
    input[type=password]:focus,
    input[type=email]:focus,
    input[type=color]:focus,
    input[type=number]:focus,
    button:focus,
    select:focus,
    [type=text].form-control:focus,
    [type=password].form-control:focus,
    [type=email].form-control:focus,
    [type=tel].form-control:focus,
    [contenteditable].form-control:focus {
        box-shadow: inset 0 -1px 0 #ddd !important;
        border: none !important;
    }
    .form-control:focus {
        box-shadow: inset 0 -1px 0 #ddd !important;
        color: black !important;
    }
</style>
