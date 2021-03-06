@extends('layouts.admin')
@section('title')
    Create Category
@stop
@section('content')
    <h2>Create Category</h2>
    <hr>
    <form method="POST" action="{{ url('/admin/category') }}" id="submit" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        @if(config('globalSettings.multi-level-cats'))
        <div class="form-group">
            <label for="description">Parent</label>
            <select class="form-control" name="parent" id="parent">
                <option value="" selected>Top Level Category</option>
                @foreach($cat as $theCat)
                    <option value="{{$theCat->id}}">{{$theCat->name}}</option>

                    @foreach($theCat->subcats as $subCat)
                        @include('layouts.categories.categoryLooperCreate', array(
                        'subCat' => $subCat,
                        'offset' => '-'
                        ))
                    @endforeach
                @endforeach
            </select>
        </div>
        @endif

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="6"></textarea>
        </div>

        <div class="form-group">
            <label for="marker">Marker Image</label>
            <input type="file" class="form-control-file" name="marker" id="marker" required>
        </div>

        @if(config('globalSettings.orderable-categories'))
            <div class="form-group">
                <label for="orderable">Orderable?</label>
                <input class="form-check-input" type="checkbox" name="orderable">

            </div>
        @endif
        <hr>
        <h4>Specifications (Click to Select)</h4>
        <div class="specs">
            @foreach($specs as $spec)
                @if($spec->required == true)
                    <div class="specification active" data-id="{{$spec->id}}" data-name="{{$spec->name}}">
                @else
                     <div class="specification" data-id="{{$spec->id}}" data-name="{{$spec->name}}">
                @endif
                    <strong>Name: </strong> {{$spec->name}}
                    <br>
                    <strong>Type: </strong> {{$spec->type}}
                    <br>
                    <strong>Options: </strong><br>
                    @foreach ( json_decode($spec->options) as $tag)
                        <span class="optionData">{{$tag->value}}</span>
                    @endforeach
                    <br><strong class="clearfix">Default: </strong><br>
                    <input type="text" class="form-control default" />
                    <br>
                    @if($spec->required != true)
                        <div class="btn btn-success addSpec">Select</div>
                    @endif

                </div>
            @endforeach
        </div>

        <input type="hidden" name="specifications" id="specifications" value='[{"id":0,"name":"","defaultVal":""}]'>

        <br style="clear:both;" />
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
        <br>
        <br>
        @include('layouts.errors')

    </form>

    <script>
        $(document).ready(function() {
            $('div.addSpec').on('click', function() {
                let elm = $(this).parent();
                $(elm).toggleClass('active');
                $('#specifications').val(getSpecs());
            });

            $('#submit').submit(function() {
                try {
                    let val = getSpecs();
                    if(val) {
                        $('#specifications').val(getSpecs());
                    }
                    else {
                        alert("Selected Specifications must have a value");
                        return false;
                    }

                }
                catch(err) {
                    return false;
                }
            });

        });

        function getSpecs() {
            let obj = [];
            let error = false;
            $('div.specification').each(function() {
                if($(this).hasClass("active")) {
                    let name = $(this).data("name");
                    let id = $(this).data("id");
                    let thisElm = $(this);
                    let defaultVal = $('input.default', thisElm).val();
                    let item = {};
                    if(defaultVal === "" || defaultVal === null) {
                        error = true;
                    }
                    item["id"] = id;
                    item["name"] = name;
                    item["defaultVal"] = defaultVal;
                    obj.push(item);
                }
            });
            if(error) {
                return false;
            }
            return JSON.stringify(obj);
        }
    </script>
@stop