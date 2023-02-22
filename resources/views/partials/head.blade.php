<head>
    <meta charset="utf-8">
    <link href="{{asset('dist/images/logo.png')}}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="sistemasdev.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pensudev</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon1.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon2.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon3.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{asset('dist/css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('font/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('toastify/toastify.min.css')}}">
    <style>
        .scrollbar-medium::-webkit-scrollbar {
            width: 3px;
            background-color: rgb(213, 208, 208)
        }
        .scrollbar-medium::-webkit-scrollbar-track {
            background: rgb(213, 208, 208);    /* color of the tracking area */
        }
        .scrollbar-medium::-webkit-scrollbar-thumb {
            background-color: blue;    /* color of the scroll thumb */
            border-radius: 3px;       /* roundness of the scroll thumb */
            border: 3px solid orange;  /* creates padding around scroll thumb */
        }
        .badge{
            padding: 2px 6px;
            border-radius: 5px;
        }
        .bg-danger{
            background-color: rgb(185,28, 28);
            color: white;
            font-size: 11px;
        }
        .bg-info{
            background-color: rgb(13,148,136);
            color: white;
            font-size: 11px;
        }
        .pagination {
            display: block !important;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: var(--artical-color-dark);
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        .page-link:hover {
            z-index: 2;
            color: #ae69f5;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(174, 105, 245, 0.25);
        }

        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #ae69f5;
            border-color: #ae69f5;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-lg .page-link {
            padding: 0.75rem 1.5rem;
            font-size: 1.25rem;
            line-height: 1.5;
        }

        .pagination-lg .page-item:first-child .page-link {
            border-top-left-radius: 0.3rem;
            border-bottom-left-radius: 0.3rem;
        }

        .pagination-lg .page-item:last-child .page-link {
            border-top-right-radius: 0.3rem;
            border-bottom-right-radius: 0.3rem;
        }

        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .pagination-sm .page-item:first-child .page-link {
            border-top-left-radius: 0.2rem;
            border-bottom-left-radius: 0.2rem;
        }

        .pagination-sm .page-item:last-child .page-link {
            border-top-right-radius: 0.2rem;
            border-bottom-right-radius: 0.2rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            background: var(--artical-background-page) !important;
            color: var(--artical-color-body);
            border-color: var(--artical-color-border) !important;
            outline: none !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 1.4em !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.755em !important;
        }
        .dark table.dataTable.display tbody tr > .sorting_1,
        .dark table.dataTable.order-column.stripe tbody tr > .sorting_1 {
            background-color: var(--artical-background-page) !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--artical-color-body) !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dark
            .dataTables_wrapper
            .dataTables_paginate
            .paginate_button.disabled:active {
            color: var(--artical-color-body) !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--artical-background-module) !important;
            color: var(--artical-color-body) !important;
        }
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            border-top-color: var(--artical-color-border) !important;
            color: var(--artical-color-body);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--artical-color-body) !important;
        }

        table.dataTable.row-border tbody th,
        table.dataTable.row-border tbody td,
        table.dataTable.display tbody th,
        table.dataTable.display tbody td {
            border-top-color: var(--artical-color-border) !important;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 10px 18px !important;
        }


        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 0px !important;
            margin: 0px !important;
        }

        div.dataTables_wrapper div.dataTables_processing {
            top: 150;
            left: 250;
            padding: 5px !important;
            background-color: #f5f8fa !important;
            color: blue !important;
            border: 1px dotted darkgrey;
            border-radius: 1px !important;
            font-size: 14 !important;
            opacity: 1 !important;
            text-decoration: none;
        }

        .dataTable tbody tr {
            background-color: var(--artical-background-panel) !important;
        }

        table.dataTable tbody tr.selected {
            background-color: #b0bed9 !important;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom-color: var(--artical-color-border) !important;
        }

        table.dataTable.no-footer {
            border-bottom-color: var(--artical-color-border) !important;
        }
        .page-item:first-child .page-link {
            margin-left: 0 !important;
            border-top-left-radius: 0.25rem !important;
            border-bottom-left-radius: 0.25rem !important;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem !important;
            border-bottom-right-radius: 0.25rem !important;
        }

        .page-item.active .page-link {
            z-index: 3 !important;
            color: #fff !important;
            background-color: #ae69f5 !important;
            border-color: #ae69f5 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
            background: transparent !important;
        }

        .page-link {
            margin-right: 2px !important;
        }

        .page-item.disabled .page-link {
            color: #6c757d !important;
            pointer-events: none !important;
            cursor: auto !important;
            background-color: #fff !important;
            border-color: #dee2e6 !important;
        }

        .pagination-lg .page-link {
            padding: 0.75rem 1.5rem !important;
            font-size: 1.25rem !important;
            line-height: 1.5 !important;
        }

        .pagination-lg .page-item:first-child .page-link {
            border-top-left-radius: 0.3rem !important;
            border-bottom-left-radius: 0.3rem !important;
        }

        .pagination-lg .page-item:last-child .page-link {
            border-top-right-radius: 0.3rem !important;
            border-bottom-right-radius: 0.3rem !important;
        }

        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
        }

        .pagination-sm .page-item:first-child .page-link {
            border-top-left-radius: 0.2rem !important;
            border-bottom-left-radius: 0.2rem !important;
        }

        .pagination-sm .page-item:last-child .page-link {
            border-top-right-radius: 0.2rem !important;
            border-bottom-right-radius: 0.2rem !important;
        }

        .page-link {
            position: relative !important;
            display: block !important;
            padding: 0.5rem 0.75rem !important;
            margin-left: -1px !important;
            line-height: 1.25 !important;
            color: var(--artical-color-dark) !important;
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
        }
        .page-link:hover {
            z-index: 2 !important;
            color: #ae69f5 !important;
            text-decoration: none !important;
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
        }
        .page-link:focus {
            z-index: 3 !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.2rem rgba(174, 105, 245, 0.25) !important;
        }
        #spinner {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 0.2s;
        }

        /* (B) CENTER LOADING SPINNER */
        #spinner img {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%);
        }
        #spinner p {
            position: absolute;
            top: 60%; left: 50%;
            transform: translate(-50%);
        }

        /* (C) SHOW & HIDE */
            #spinner {
            visibility: hidden;
            opacity: 0;
        }
        #spinner.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
    @yield('css')
</head>
