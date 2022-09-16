@extends('layouts.app')
@section('styles')
    <style>
        td input {
            max-width: 30px;
        }
    </style>
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if(isset($view_data['active_edition']))
                    <div class="card-header"><h4>{{$view_data['active_edition']->name}}</h4></div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p class="ml-3"><strong>Fecha de cierre:</strong>  {{\Carbon\Carbon::now()->toDateString()}}</p>
                            </div>
                            <div class="col-6">
                                <button type="button" disabled class="btn btn-success float-right mr-2" id="top_five">
                                    Top 5
                                </button>
                                <button type="button" class="btn btn-success mr-2 float-right" id="general_table">
                                    General
                                </button>
                            </div>
                        </div>

                        <div class="tab-content">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#ranking_five"
                                    data-tab="ranking_five"
                                    class="nav-link has-no-table {{ $view_data['active_tab'] == "ranking_five" ? "active":"" }}"
                                    data-toggle="tab"
                                    >
                                        Generales
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#five_details"
                                    data-tab="five_details"
                                    class="nav-link has-no-table {{ $view_data['active_tab'] == "five_details" ? "active":"" }}"
                                    data-toggle="tab"
                                    >
                                        Detalles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#inactive"
                                    data-tab="inactive"
                                    class="nav-link has-no-table {{ $view_data['active_tab'] == "inactive" ? "active":"" }}"
                                    data-toggle="tab"
                                    >
                                        Inactivos
                                    </a>
                                </li>
                            </ul>
                            @include('ranking.tabs.ranking_five')
                            @include('ranking.tabs.five_details')
                            @include('ranking.tabs.inactive')
                        </div>
                    </div>
                @else
                    <div class="card-header"><h4>COMPETICION INACTIVA</h4></div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                SE ACTIVARA PROXIMAMENTE
                            </div>
                        </div>
                    </div>

                @endif

                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="img-out"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            var tab = $(this).data('tab');

            $.each($('div.tab-pane'), function(){
                $(this).addClass('d-none');
            })
            $('#'+tab).removeClass('d-none');
        })

        $('a[data-toggle="tab"]').on('click', function(){
            var tab = $(this).data('tab');

            if(tab == 'ranking_five') {
                $('#top_five').prop('disabled', true);
                $('#general_table').prop('disabled', false);
            } else if(tab == 'five_details'){
                $('#top_five').prop('disabled', false);
                $('#general_table').prop('disabled', true);
            } else {
                $('#top_five').prop('disabled', true);
                $('#general_table').prop('disabled', true);
            }
        })

        /*$('#save_table').on('click', function(){
            html2canvas(document.querySelector('#five_details')).then(function(canvas) {

                console.log(canvas);
                saveAs(canvas.toDataURL(), 'file-name.png');
            });
        })*/

        $('#general_table').on('click', function(){
            html2canvas(document.getElementById("ranking_all"),
                    {
                        scrollX: -window.scrollX,
                        scrollY: -window.scrollY,
                        windowWidth: document.documentElement.offsetWidth,
                        windowHeight: document.documentElement.offsetHeight
                    }
                ).then(function(canvas) {
                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "Tabla general.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();
                document.body.removeChild(link);
            });
        });

        $('#top_five').on('click', function(){
            html2canvas(document.getElementById("five_details"),
                    {
                        scrollX: -window.scrollX,
                        scrollY: -window.scrollY,
                        windowWidth: document.documentElement.offsetWidth,
                        windowHeight: document.documentElement.offsetHeight
                    }
                ).then(function(canvas) {
                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "Top 5.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();
                document.body.removeChild(link);
            });
        });

    });

    function saveAs(uri, filename) {

        var link = document.createElement('a');

        if (typeof link.download === 'string') {

            link.href = uri;
            link.download = filename;

            //Firefox requires the link to be in the body
            document.body.appendChild(link);

            //simulate click
            link.click();

            //remove the link when done
            document.body.removeChild(link);

        } else {

            window.open(uri);

        }
    }
</script>
@endsection
