@extends('layouts.master')

@section('css')
    <!--Internal Font Awesome -->
    <link href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!--Internal Treeview -->
    <link href="{{ asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('title', 'عرض الصلاحيات - تك سوفت للادارة القانونية')

    @section('page-header')
        <!-- Breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">الصلاحيات</h4>
                    <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض الصلاحيات</span>
                </div>
            </div>
        </div>
        <!-- Breadcrumb -->
    @endsection

    @section('content')
        <!-- Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="pull-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}">رجوع</a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Column -->
                            <div class="col-lg-4">
                                <ul id="treeview1">
                                    <li>
                                        <a href="#">{{ $role->name }}</a>
                                        <ul>
                                            @foreach($rolePermissions as $permission)
                                                <li>{{ $permission->name }}</li>
                                            @if(empty($permission->name))
                                                    <li>لا توجد صلاحيات مرتبطة</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Column -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Row -->
    @endsection

    @section('js')
        <!-- Internal Treeview JS -->
        <script src="{{ asset('assets/plugins/treeview/treeview.js') }}"></script>
    @endsection
