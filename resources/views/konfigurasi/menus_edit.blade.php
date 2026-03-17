@extends('layouts.materialize')
@push('css')


@endpush

        
@section('content')<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <h2>
                        Edit Menu Item
                        <span class="float-right">
                            <a class="btn btn-outline-danger" href="{{route('menus')}}">Back</a>
                        </span>
                    </h2>
                </div>

                <div class="card-body">
                    {{  $item->id }}
                      <form action="/menustop/{{  $item->id }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" id="title" 
                                value="{{$item->title}}">
                            </div>

                            <div class="col-md-12 mt-3 text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                              
                            </div>
                    </form>
                    {{-- {{ html()->form('PUT', '/topnew')->open() }} --}}
                    {{ html()->modelForm($item, 'PUT', '/topeditupdate', $item->id)->open() }}
                    {{-- {{ Form::model($item, array('route' => array('footeditupdate', $item->id), 'method' => 'PUT')) }} --}}
                  
                        @method('PUT')
                        <div class="row">
                        <div class="col-md-12 mt-3">
                            
                            {{ html()->label('title') }}
                            {{ html()->text('title')->class('form-control') }}
                        </div>
                        <div class="col-md-12 mt-3">
                            
                            {{ html()->label('Slug') }}
                            {{ html()->text('slug')->class('form-control') }}
                        </div>
                        <div class="col-md-12 mt-3 text-center">
                            {{-- {{ Form::submit('Update', array('class' => 'btn btn-primary')) }} --}}
                            {{ html()->submit('Update')->class('btn btn-primary') }}
                        </div>
                    </div>
                {{-- </form> --}}
                    {{-- {{Form::close()}} --}}
                    {{ html()->closeModelForm() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')


@endpush