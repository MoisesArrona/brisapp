@extends('layouts.template')

@section('style')
    <!-- FullCalendar -->
    <link href='{{ asset('assets/vendor/fullcalendar-3.10.0/fullcalendar.css') }}' rel='stylesheet' media="all" />

    <style>
        nav.flex.items-center.justify-between div:nth-child(2) {
            display: none !important
        }

        .fc-day-grid-event {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <!-- Error -->
    @include('components.messages')

    <!-- Contenido Principal -->
    <section class="statistic">
        <div class="section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">Fiestas</h3>
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="table-data__tool-left">
                                    <div class="table-data__tool-left">
                                        <a href="{{ route('package.index') }}" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                            Paquete
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#add">
                                    <i class="zmdi zmdi-plus"></i>Agregar
                                </button>

                                <a href="{{ route('parties.all') }}" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                    Ver todos
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Paquete</th>
                                        <th>Fecha del evento</th>
                                        <th>Niños Extras</th>
                                        <th>Alta</th>
                                        <th>Estatus</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parties as $party)
                                        <tr class="tr-shadow">
                                            <td>
                                                @isset($party->customer->name)
                                                    {{ $party->customer->name }} {{ $party->customer->lastname }}
                                                @endisset
                                            </td>
                                            <td>
                                                @isset($party->package->name)
                                                    {{ $party->package->name }}
                                                @endisset
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($party->date)->toFormattedDateString() }}</td>
                                            <td>{{ $party->kid }}</td>
                                            <td>{{ $party->created_at->diffForHumans() }}</td>
                                            <td>
                                                @if ($party->status == false)
                                                    <span class="status--denied">En Agenda</span>
                                                @else
                                                    <span class="status--process">Finalizada</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="table-data-feature">
                                                    <!-- Edit -->
                                                    <button class="item" data-toggle="modal" data-target="#edit{{ $party->id }}">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="edit{{ $party->id }}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('party.update', $party) }}" method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <!-- Head -->
                                                                    <div class="card-header">
                                                                        <strong>Agregar Fiesta</strong>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                
                                                                    <!-- Inputs -->
                                                                    <div class="modal-body">
                                                                        <div class="card-body card-block">
                                                                            <div class="row">
                                                                                <div class="form-group col-6">
                                                                                    <label for="customer_id" class=" form-control-label">Cliente</label>
                                                                                    <div>
                                                                                        <select name="customer_id" id="customer_id" class="form-control">
                                                                                            <option value="true" disabled="disable">Selecciona la CLiente</option>
                                                                                            @foreach ($customers as $customer)
                                                                                            <option value="{{ $customer->id }}" {{ $customer->id == $party->customer_id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @error('customer_id')
                                                                                            <code>{{ $message }}</code>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-6">
                                                                                    <label for="package_id" class=" form-control-label">Paquete</label>
                                                                                    <div>
                                                                                        <select name="package_id" id="package_id" class="form-control">
                                                                                            <option value="true" disabled="disable">Selecciona al Paquete</option>
                                                                                            @foreach ($packages as $package)
                                                                                            <option value="{{ $package->id }}" {{ $package->id == $party->package_id ? 'selected' : '' }}>{{ $package->name }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @error('package_id')
                                                                                            <code>{{ $message }}</code>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                
                                                                            <div class="row">
                                                                                <div class="form-group col-6">
                                                                                    <label for="date" class=" form-control-label">Fecha</label>
                                                                                    <input type="datetime-local" id="date" name="date" class="form-control" value="{{Carbon\Carbon::parse($party->date)->format('Y-m-d\TH:i') }}">
                                                                                    @error('date')
                                                                                        <code>{{ $message }}</code>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="form-group col-6">
                                                                                    <label for="kid" class=" form-control-label">Numero de Niños</label>
                                                                                    <input type="number" id="kid" name="kid" class="form-control" value="{{ $party->kid }}">
                                                                                    @error('kid')
                                                                                        <code>{{ $message }}</code>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                
                                                                    <!-- Buttons -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete -->
                                                    <form action="{{ route('party.destroy', $party) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="item" type="submit">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button>
                                                    </form>

                                                    <!-- More -->
                                                    <a href="{{ route ('party.show', $party) }}" class="item">
                                                        <i class="zmdi zmdi-more"></i>
                                                    </a>

                                                    <!-- Cambiar status -->
                                                    @if (Carbon\Carbon::parse($party->date)->format('d-m-Y h') == Carbon\Carbon::parse($now)->format('d-m-Y h') && $party->status == false )
                                                        <form action="{{ route('party.status') }}" method="POST">
                                                            @csrf

                                                            <input type="hidden" value="{{ $party->id  }}" name="id">
                                                            <button class="item" type="submit">
                                                                <i class="zmdi zmdi-rotate-right"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="spacer"></tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $parties->links() }}
                        </div>
                    </div>

                    <div class="col-md-10 offset-md-1 mt-5">
                        <h3 class="title-5 m-b-35">Fechas disponibles</h3>

                        <div class="au-card">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Add -->
    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('party.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <!-- Head -->
                    <div class="card-header">
                        <strong>Agregar Fiesta</strong>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Inputs -->
                    <div class="modal-body">
                        <div class="card-body card-block">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="customer_id" class=" form-control-label">Cliente</label>
                                    <div>
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option selected="true" disabled="disabled">Selecione el Cliente</option>
                                            @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" >{{ $customer->name }} {{ $customer->lastname }}</option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="package_id" class=" form-control-label">Paquete</label>
                                    <div>
                                        <select name="package_id" id="package_id" class="form-control">
                                            <option selected="true" disabled="disabled">Selecione el Paquete</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}">{{ $package->name }} - paquete: {{ $package->price_lm }} {{ $package->price_jv }} {{ $package->price_sd}} - precio extra: {{ $package->price_e }}</option>
                                            @endforeach
                                        </select>
                                        @error('package_id')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="date" class=" form-control-label">Fecha</label>
                                    <input type="datetime-local" id="date" name="date" class="form-control">
                                    @error('date')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="kid" class=" form-control-label">Numero de Niños</label>
                                    <input type="number" id="kid" name="kid" class="form-control">
                                    @error('kid')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Calendar -->
    <script src=" {{ asset('assets/vendor/fullcalendar-3.10.0/lib/moment.min.js') }}"></script>
    <script src=" {{ asset('assets/vendor/fullcalendar-3.10.0/fullcalendar.js') }} "></script>
    <script src=" {{ asset('assets/vendor/fullcalendar-3.10.0/locale/es.js') }} "></script>
    <script type="text/javascript">
        $(function() {
            // setup a few events
            $('#calendar').fullCalendar({

                locale: 'es',

                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },

                events : [
                    @foreach($partiess as $party)
                    {
                        title : '{{ $party->customer->name }}',
                        start : '{{ $party->date }}',

                        @if ($party->status == false)
                            color: 'red',
                        @else
                            color: 'green',
                        @endif

                        
                        
                    },
                    @endforeach
                ],

                
            });
        });
    </script>

@endsection