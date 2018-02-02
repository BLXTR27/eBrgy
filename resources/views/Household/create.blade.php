@extends('dashboard')

@section('style')
<link href="{{ asset('css/toastr.css') }}" rel="stylesheet">
@stop

@section('content')
<script src="{{  asset('js/jquery.min.js')  }}"></script>
<script src="{{  asset('js/toastr.js')  }}"></script>
@if ($errors->any())
<script type="text/javascript">
    toastr.error(' <?php echo implode('', $errors->all(':message')) ?>', "There's something wrong!")
</script>             
@endif
@if(session('error'))
<script type="text/javascript">
    toastr.error(' <?php echo session('error'); ?>', "There's something wrong!")
</script>
@endif
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">New Household</h3>
    </div>
    <div class="box-body">
        <div class="row" style="padding:20px;">
            <form action="{{ url('/Resident/Store') }}" method="post" files="true" enctype="multipart/form-data">
            <div class="col-sm-6">
                <div class="" style="padding:10px; background:#252525; color:white;">
                    Household Information
                </div>
                <div class="form-group" style="margin-top:20px;">
                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Household No.">
                </div>
                <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="text" class="col-sm-6 form-control" id="exampleInputEmail1" placeholder="Street" name="street" >
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="col-sm-6 form-control" id="exampleInputEmail1" placeholder="Brgy" name="brgy">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="col-sm-6 form-control" id="exampleInputEmail1" placeholder="City" name="city">
                                </div>
                            </div>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Inhabitants</label>
                    <select class="form-control select2" name="states[]" multiple="multiple">
                        @foreach($resident as $res)
                            <option value="{{$res->id}}">{{$res->firstName}} {{$res->middleName}} {{$res->lastName}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                {{ csrf_field() }}
                <div class="" style="padding:10px; background:#252525; color:white;">
                    Household Inhabitants
                </div>
                <div class="pull-right">
                    <a href="#" id="remove" class="btn btn-xs btn-danger" style="margin:10px;" title="Remove inhabitant"><i class="fa fa-trash"></i></a>
                </div>
                <div style="margin-top:20px;">
                <table id="inhabit" class="display in" cellspacing="0" style="" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Civil Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
    
                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                                reader.onload = function (e) {
                                    $('#pic')
                                    .attr('src', e.target.result)
                                    .width(300);
                                };
                            reader.readAsDataURL(input.files[0]);
                        }
                         }

                         
                var table = $('#inhabit').DataTable();

                $('#inhabit tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );

                $('#remove').click( function () {
                    table.row('.selected').remove().draw( false );
                } );

                $('select').on('select2:select', function(e){

                    var data = e.params.data;
                    inhabitant(data.id);
                });


                

                function inhabitant(id)
                {
                  
                    $.ajax({
                        type: "GET",
                        url: '/Household/Inhabitant/id='+id,
                        dataType: "JSON",
                        success:function(data){
                            for(var x=0;x<data.length;x++)
                            {
                                var sex = data[x].gender;
                                if(sex == 1)
                                {
                                    sex = "Male";
                                }
                                else
                                {
                                    sex = "Female";
                                }
                                table.row.add([
                                    data[x].firstName+data[x].middleName+data[x].lastName,
                                    sex,
                                    data[x].civilStatus
                                ]).draw();
                            }
                           
                        }
                     });
                }

               
    </script>
@stop