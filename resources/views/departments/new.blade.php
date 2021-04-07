@extends('layouts.app', [
    'namePage' => 'Departments',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'departments',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Add Department</h2></div>
    

    <br>

    <div class=" justify-content-center align-items-center container " style="width:70% " >
        <br> <br>
        <div class="card">
            <div class="card-body">
                
                {{ Form::open(array('url' => 'save-department', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}
                    <div class="form-group">
                        {{form::label('name', 'Name')}}
                        {{form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Name', 'required'])}}
                    </div>

                    <div class="form-group">
                        {{form::label('description', 'Description')}}
                        {{form::textarea('description', '', ['id'=>'article-ckeditor', 'class'=>'form-control', 'placeholder'=>'Add Description'])}}
                    </div>
                    
                    {{form::submit('Create', ['class'=>'btn btn-primary', 'onclick'=>"load_preloader('form-1')"])}}           
                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection