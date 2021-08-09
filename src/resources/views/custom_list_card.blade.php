@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('backpack::crud.list') => false,
];
// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
<div class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
</div>
@endsection

@section('content')
<!-- Default box -->
<div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">

        <div class="row mb-0">
            <div class="col-sm-6">
                @if ( $crud->buttons()->where('stack', 'top')->count() || $crud->exportButtons())
                <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">

                    @include('crud::inc.button_stack', ['stack' => 'top'])

                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none">
                    <div id="crudTable_filter" class="dataTables_filter"><label><input type="search"
                                class="form-control" placeholder="Search..." aria-controls="crudTable"></label></div>
                </div>
            </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
        @include('dcard::inc.custom_filters_navbar')
        @endif

        <div id="content" class="row d-none"></div>
        <div id="crud-loader" class="card loading"><img
                src="http://localhost:8000/packages/backpack/crud/img/ajax-loader.gif" alt="Processing..."></div>
        <nav>
            <ul class="pagination justify-content-end" id="pagination-card">

            </ul>
        </nav>
    </div>
</div>

</div>

</div>

@endsection

@section('after_styles')
<!-- DATA TABLES -->
<link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

<link rel="stylesheet"
    href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet"
    href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet"
    href="{{ asset('packages/backpack/crud/css/list.css').'?v='.config('backpack.base.cachebusting_string') }}">

<!-- CRUD LIST CONTENT - crud_list_styles stack -->
@stack('crud_list_styles')
@endsection

@section('after_scripts')
@include('dcard::inc.custom_datatables_logic')
<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}">
</script>
<script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}">
</script>
<script src="{{ asset('packages/backpack/crud/js/list.js').'?v='.config('backpack.base.cachebusting_string') }}">
</script>
<script src="{{ asset('assets/js/DataCard.js') }}"></script>

<!-- CRUD LIST CONTENT - crud_list_scripts stack -->
@stack('crud_list_scripts')
@endsection