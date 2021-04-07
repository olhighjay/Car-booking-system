@extends('layouts.app', [
    'namePage' => 'Vehicles',
    'activePage' => 'vehicles',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Add A Car</h2></div>
    <br>

    <div class=" justify-content-center align-items-center container"  >
        <div class="card" style="width:80%; margin-left:10%">
            <div class="card-body" style="padding-left: 10%;padding-right: 10%">
                <br> <br>

                <p class="required">Required *</p>
                {{-- <div class="justify-content-center"> --}}
                {{ Form::open(array('url' => 'save-car', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}
                    <div class="form-group">
                        {{form::label('name', 'Name')}} <span class="required">*</span>
                        {{form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Name', 'required'])}}
                    </div>

                    <div class="form-group jayjay">
                        {{form::label('description', 'Description')}}
                        {{form::textarea('description', '', ['id'=>'article-ckeditor', 'class'=>'form-control', 'placeholder'=>'Add Description'])}}
                    </div>

                    <div class="form-group jayjay">
                        {{form::label('properties', 'Properties')}} <span class="required">*</span>
                        {{form::textarea('properties', '', ['id'=>'article-ckeditor', 'class'=>'form-control', 'placeholder'=>'Add Properties', 'required'])}}
                    </div>

                    <div class="form-group jayjay">
                        {{form::label('plate_number', 'Plate number')}} <span class="required">*</span>
                        {{form::textarea('plate_number', '', ['id'=>'article-ckeditor', 'class'=>'form-control', 'placeholder'=>'Add Plate Number', 'required'])}}
                    </div>

                    <div class="form-group">
                        <label for="department">Choose a Department</label> <span class="required">*</span>
                        <select id="department" class="custom-select form-control" name="department" required>
                            <option value = ""> Select Department </option>
                            @foreach ($departments as $department)
                                <option value = "{{$department->id}}"> {{$department->name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <b>Car Health Status </b>
                            <br>
                        @foreach($healths as $health)
                        <input id="health" class="form-radio-input" type="radio" name="health" value="{{$health->id}}" @if($health->name === 'Excellent') checked @endif>
                        <label for="health" class="form-radio-label">{{$health->name}}</label> &nbsp &nbsp &nbsp &nbsp
                        <br>
                        @endforeach
                    </div> 
                    
                    <div class="form-group">
                        <b>Availabilities</b>
                            <br>
                        @foreach($availabilities as $availability)
                        <input id="availability" class="form-radio-input" type="radio" name="availability" value="{{$availability->id}}" @if($availability->name === 'Available') checked @endif>
                        <label for="availability" class="form-radio-label">{{$availability->name}}</label> &nbsp &nbsp &nbsp &nbsp
                        <br>
                        @endforeach
                    </div>  

                    <div class="">
                        <label for="exampleInputFile"><b>Choose Image</b></label> <br>
                        <input type="file" class="image" name="images" id="exampleInputFile" style="height: 10% !important; width:120%" >
                    </div> <br>
                    {{-- <div class="">
                        <b>{{form::label('image', 'Select Images')}}</b> <br>
                        {!! Form::file('image', array('class' => 'image')) !!}
                    </div> <br> --}}

                    
                    {{form::submit('Create', ['class'=>'btn btn-primary', 'onclick'=>"load_preloader('form-1')"])}}
                
                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection