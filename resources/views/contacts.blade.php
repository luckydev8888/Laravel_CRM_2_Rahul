@extends('layouts.app')

@section('custom_styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/select/1.6.2/css/select.dataTables.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"/>
<style>
    .dt-buttons {
        margin-left: 20px;
    }
    .Nocontact {
        background-color : #cac9c8;
    }
    .Call1 {
        background-color: #ffed00;
    }

    .Call2 {
        background-color: #ffde0e;
    }

    .Call3 {
        background-color: #eeca13;
    }
    .Almost {
        background-color: #ee7640;
    }
    .Customer {
        background-color: #99c771;
    }
    .Notinterested {
        background-color: #927256;
    }

    .Notinteresting {
        background-color: #998a76;
    }
    .Nocontact, .Call1, .Call2, .Call3, .Almost, .Customer, .Notinterested, .Notinteresting {
        color : white;
        vertical-align: baseline;
        text-align: center;
        align-items: center;
        justify-content: center;
    }

    .Email1sent {
        background-color: #5bcfc0;
    }

    .Email2sent {
        background-color: #1b9e8d;
    }

    .Wa1sent {
        background-color: #5ec25e;
    }

    .Wa2sent {
        background-color: #588a54;
        ;
    }

    .Undecided {
        background-color: #ffd757;
    }

    table.dataTable>tbody>tr>td.select-checkbox:before, table.dataTable>tbody>tr>td.select-checkbox:after, table.dataTable>tbody>tr>th.select-checkbox:before, table.dataTable>tbody>tr>th.select-checkbox:after {
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 12px;
        height: 12px;
        box-sizing: border-box;
    }
    table td div {
        padding: 0;
        margin: 0;
    }
    .contacts_status {
    }
    .my-input-class {
        width: 100%;
    }
    table thead tr th {
        word-wrap: break-word;
        font-size: 12px;
        font-weight: 500;
    }
    table tbody tr td {
        word-wrap: break-word;
    }
    table div {
    }
    .iti { 
        width: 100% !important; /* Ensure intl-tel-input matches other inputs */
    }

    .iti .iti__selected-flag {
        height: 100%; /* Make flag dropdown match input height */
    }

    .date-column, .sample-column {
        text-align: center; /* Change to left or right if needed */
        vertical-align: middle; /* Optional: Ensures alignment vertically */
    }
</style>
@endsection

@section('content')
<div class="content flex-row-fluid" id="kt_content" style="padding-left: 0px; padding-right: 0px">
    <!--begin::Index-->
    <div class="card card-page">
        <!--begin::Card body-->
        <div class="card-body pt-2 pb-2 ps-2 pe-2">
            <div class="country-container" style="">
                <span class="country"><i class="fas fa-globe-europe mb-1 me-1"></i> Country </span>
                <select {{ !Auth::user()->is_admin ? 'disabled':'' }}  id="country" class="form-control-sm" onchange="country_change()" style="display:inline-block; width: 300px">
                    <option value="SPAIN" {{ Auth::user()->country == 'SPAIN' ? 'selected' : ''}}>SPAIN</option>
                    <option value="PORTUGAL" {{ Auth::user()->country == 'PORTUGAL' ? 'selected' : ''}}>PORTUGAL</option>
                    <option value="USA" {{ Auth::user()->country == 'USA' ? 'selected' : ''}}>USA</option>
                    <option value="CANARIAS" {{ Auth::user()->country == 'CANARIAS' ? 'selected' : ''}}>CANARIAS</option>
                    <option value="" {{ Auth::user()->is_admin ? 'selected' : '' }}>ALL</option>
                </select>
            </div>
            <table id="contacts" class="display" style="width: 1900px;">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width: 20px;">User</th>
                        <th>Id</th>
                        <th style="min-width:65px;">Date</th>
                        <th style="min-width:60px;">Status</th>
                        <th>Score</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th style="min-width:70px;">Tel.1</th>
                        <th>Tel.2 (WhatsApp)</th>
                        <th>Town/City</th>
                        <th>Area/State</th>
                        <th style="min-width:20px;">Sam ple</th>
                        <th style="min-width:20px;">Dis play</th>
                        <th style="min-width:20px;">Pri ces</th>
                        <th>Brand</th>
                        <th style="min-width:70px;">Comments</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Index-->
</div>
<div class="modal fade" tabindex="-1" id="new_contacts">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Contact</h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body p-5">
                <form action="{{url('/contacts/new')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="country">COUNTRY</label>
                                <div class="col-sm-8">
                                    <select name="country" id="country" class="form-control" {{Auth::user()->is_admin ? '' : 'disabled'}}>
                                        <option value=""></option>
                                        <option value="SPAIN" {{Auth::user()->country == 'SPAIN' ? 'selected' : ''}}>SPAIN</option>
                                        <option value="PORTUGAL" {{Auth::user()->country == 'PORTUGAL' ? 'selected' : ''}}>PORTUGAL</option>
                                        <option value="USA" {{Auth::user()->country == 'USA' ? 'selected' : ''}}>USA</option>
                                        <option value="CANARIAS" {{Auth::user()->country == 'CANARIAS' ? 'selected' : ''}}>CANARIAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="assigned">Assigned</label>
                                <div class="col-sm-8">
                                    <select name="assigned" id="assigned" class="form-control">
                                        @foreach($users as $user)
                                            @if ($user->country == Auth::user()->country && !Auth::user()->is_admin)
                                                <option country="{{$user->country}}" value="{{ Auth::user()->id }}">{{ $user->email }}</option>
                                            @endif
                                            @if (Auth::user()->is_admin)
                                                <option country="{{$user->country}}" value="{{ Auth::user()->id }}">{{ $user->email }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="status">STATUS</label>
                                <div class="col-sm-8">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" ></option>
                                        <option value="No contact" >No Contact</option>
                                        <option value="Call 1" >Call 1</option>
                                        <option value="Send Sample" >Send Sample</option>
                                        <option value="Sample Testing" >Sample Testing</option>
                                        <option value="Standby" >Standby</option>
                                        <option value="Almost" >Almost</option>
                                        <option value="Customer" >Customer</option>
                                        <option value="Not interested" >Not interested</option>
                                        <option value="Not interesting" >Not interesting</option>
                                        <option value="COCKROACH" >COCKROACH</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">
                                <label for="date" class="col-sm-4 text-end m-auto">Date</label>
                                <div class="col-sm-8">
                                    <input type="text" id="date" name="date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="company">Company</label>
                                <div class="col-sm-8">
                                    <input type="text" id="company" name="company" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="tel1">Tel.1</label>
                                <div class="col-sm-8">
                                    <input type="text" id="tel1" 
                                        name="tel1" class="form-control">
                                </div>
                                @if($errors->has('tel1'))
                                    <div class="alert alert-danger">{{ $errors->first('tel1') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="contact">Contact</label>
                                <div class="col-sm-8">
                                    <input type="text" id="contact" name="contact" class="form-control" required >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="tel2">Tel.2</label>
                                <div class="col-sm-8">
                                    <input type="tel" id="tel2" 
                                        name="tel2" 
                                        class="form-control">
                                </div>
                                @if($errors->has('tel2'))
                                    <div class="alert alert-danger">{{ $errors->first('tel2') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                    
                                <label class="col-sm-4 text-end m-auto" for="town">Town</label>
                                <div class="col-sm-8">
                                    <input type="text" id="town" name="town" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="email">e-mail</label>
                                <div class="col-sm-8">
                                    <input type="text" id="email" name="email" class="form-control" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                   
                                <label class="col-sm-4 text-end m-auto" for="area">Area/State</label>
                                <div class="col-sm-8">
                                    <input type="text" id="area" name="area" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                    
                                <label class="col-sm-4 text-end m-auto" for="brand">Brand</label>
                                <div class="col-sm-8">
                                    <input type="text" id="brand" name="brand" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="score">Rank</label>
                                <div class="col-sm-8">
                                    <select name="score" id="score" class="form-control">
                                        <option value=""></option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="P">P</option>
                                        <option value="L">L</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">
                                <label for="samples" class="col-sm-4 text-end m-auto">Samples</label>
                                <div class="col-sm-8">
                                    <input type="text" id="samples" name="samples" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-center">
                            <!-- <label for="samples" class="ms-10">Samples: &nbsp; &nbsp;</label>
                            <input type="radio" name="samples" value="1" >Yes &nbsp;
                            <input type="radio" name="samples" value="0" checked>No &nbsp; -->
                            <label for="prices" class="ms-10">Price: &nbsp; &nbsp;</label>
                            <input type="radio" name="prices" value="1" >Yes &nbsp;
                            <input type="radio" name="prices" value="0" checked>No &nbsp;
                            <label for="display" class="ms-10">Display: &nbsp; &nbsp;</label>
                            <input type="radio" name="display" value="1"  >Yes &nbsp;
                            <input type="radio" name="display" value="0" checked>No &nbsp;
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-md-10 form-group">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="3" class="form-control" ></textarea>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- <div class="modal-footer">
            </div> --}}
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="edit_contacts">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contact</h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body p-5">
                <form action="{{url('/contacts/edit')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="country">COUNTRY</label>
                                <div class="col-sm-8">
                                    <select name="country" id="edit_country" class="form-control" {{Auth::user()->is_admin ? '' : 'disabled'}}>
                                        <option value=""></option>
                                        <option value="SPAIN" {{Auth::user()->country == 'SPAIN' ? 'selected' : ''}}>SPAIN</option>
                                        <option value="PORTUGAL" {{Auth::user()->country == 'PORTUGAL' ? 'selected' : ''}}>PORTUGAL</option>
                                        <option value="USA" {{Auth::user()->country == 'USA' ? 'selected' : ''}}>USA</option>
                                        <option value="CANARIAS" {{Auth::user()->country == 'CANARIAS' ? 'selected' : ''}}>CANARIAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="assigned">Assigned</label>
                                <div class="col-sm-8">
                                    <select name="assigned" id="edit_assigned" class="form-control">
                                        @foreach($users as $user)
                                            @if ($user->country == Auth::user()->country && !Auth::user()->is_admin)
                                                <option country="{{$user->country}}" value="{{ Auth::user()->id }}">{{ $user->email }}</option>
                                            @endif
                                            @if (Auth::user()->is_admin)
                                                <option country="{{$user->country}}" value="{{ Auth::user()->id }}">{{ $user->email }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="status">STATUS</label>
                                <div class="col-sm-8">
                                    <select name="status" id="edit_status" class="form-control">
                                        <option value="" ></option>
                                        <option value="No contact" >No Contact</option>
                                        <option value="Call 1" >Call 1</option>
                                        <option value="Send Sample" >Send Sample</option>
                                        <option value="Sample Testing" >Sample Testing</option>
                                        <option value="Standby" >Standby</option>
                                        <option value="Almost" >Almost</option>
                                        <option value="Customer" >Customer</option>
                                        <option value="Not interested" >Not interested</option>
                                        <option value="Not interesting" >Not interesting</option>
                                        <option value="COCKROACH" >COCKROACH</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">
                                <label for="date" class="col-sm-4 text-end m-auto">Date</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_date" name="date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="company">Company</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_company" name="company" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="tel1">Tel.1</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_tel1" 
                                        name="tel1" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="contact">Contact</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_contact" name="contact" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                      
                                <label class="col-sm-4 text-end m-auto" for="tel2">Tel.2</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_tel2" 
                                        name="tel2" 
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                    
                                <label class="col-sm-4 text-end m-auto" for="town">Town</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_town" name="town" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                     
                                <label class="col-sm-4 text-end m-auto" for="email">e-mail</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_email" name="email" class="form-control" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6 form-group">
                            <div class="row">                   
                                <label class="col-sm-4 text-end m-auto" for="area">Area/State</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_area" name="area" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                    
                                <label class="col-sm-4 text-end m-auto" for="brand">Brand</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_brand" name="brand" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">                       
                                <label class="col-sm-4 text-end m-auto" for="score">Rank</label>
                                <div class="col-sm-8">
                                    <select name="score" id="edit_score" class="form-control">
                                        <option value=""></option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="P">P</option>
                                        <option value="L">L</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row">
                                <label for="samples" class="col-sm-4 text-end m-auto">Samples</label>
                                <div class="col-sm-8">
                                    <input type="text" id="edit_samples" name="samples" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-center">
                            <!-- <label for="samples" class="ms-10">Samples: &nbsp; &nbsp;</label>
                            <input type="radio" name="samples" value="1" >Yes &nbsp;
                            <input type="radio" name="samples" value="0" checked>No &nbsp; -->
                            <label for="prices" class="ms-10">Price: &nbsp; &nbsp;</label>
                            <input type="radio" name="prices" value="1" >Yes &nbsp;
                            <input type="radio" name="prices" value="0" checked>No &nbsp;
                            <label for="display" class="ms-10">Display: &nbsp; &nbsp;</label>
                            <input type="radio" name="display" value="1"  >Yes &nbsp;
                            <input type="radio" name="display" value="0" checked>No &nbsp;
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-md-10 form-group">
                            <label for="comments">Comments</label>
                            <textarea id="edit_comments" name="comments" rows="3" class="form-control" ></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- <div class="modal-footer">
            </div> --}}
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{url('/js/dataTables.cellEdit.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<script>
var table, token = '{{csrf_token()}}', base_url = '{{url('')}}';

function country_change() {
    table.draw();
}
$(document).ready(function () {
    var myModal = new bootstrap.Modal(document.getElementById("new_contacts"), {});
    var editModal = new bootstrap.Modal(document.getElementById("edit_contacts"), {});
    const tel1Input = document.querySelector("#tel1");
    const tel2Input = document.querySelector("#tel2");
    const edit_tel1Input = document.querySelector("#edit_tel1");
    const edit_tel2Input = document.querySelector("#edit_tel2");

    const options = {
        separateDialCode: false,
        initialCountry: "es",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    };

    window.intlTelInput(tel1Input, options);
    window.intlTelInput(tel2Input, options);
    window.intlTelInput(edit_tel1Input, options);
    window.intlTelInput(edit_tel2Input, options);
    // Setup - add a text input to each footer cell
    filters = $('#contacts thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#contacts thead');
    filters.children().each(function (colIdx) {
        if (colIdx == 0) return;
        var title = $(this).text();
        $(this).css({ "padding": '5px' });
        input = $(this).html('<input class="form-control" style="width: 100%; padding: 0.2rem 0.4rem 0.2rem 0.4rem; font-size: 1rem" type="text" placeholder="' + title + '" />');
    })
    filters.find('input').each(function (colIdx) {
        $(this).on('change', function() {
            if (!table) return;
            table.draw();
        })
    })
    table = $('#contacts').DataTable({
        orderCellsTop: true,
        autoWidth: false,
        // fixedHeader: true,
        fixedColumns: {
            heightMatch: 'none'
        },
        scroller: true,
        scrollX: true,
        scrollY: '700px',
        pageResize: true,
        dom: 'lBftip',
        lengthMenu: [
            [10, 50, 100, -1],
            [10, 50, 100, 'All'],
        ],
        drawCallBack: function(settings) {
            console.log( 'DataTables has finished drawing.' );
            $(table).drawFinished();
        },
        pageLength: 50,
        buttons: [{
                extend : "copyHtml5",
                text : '<i class = "fas fa-copy mb-1 "></i>Copy',
                titleAttr : 'Copy'
            },{
                extend : "pdfHtml5",
                text : '<i class = "fas fa-file-pdf mb-1 text-danger"></i>To PDF',
                titleAttr : 'ToPDF'
            },{
                extend : "csvHtml5",
                text : '<i class = "fas fa-file-csv mb-1 "></i>To CSV',
                titleAttr : 'ToCSV'
            },{
                extend : "excelHtml5",
                text : '<i class = "fas fa-file-excel mb-1 text-success"></i>To Excel',
                titleAttr : 'ToExcel'
            },{
                text : '<i class = "fab fa-whatsapp mb-1 text-success"></i>WhatsApp',
                action: function() {
                    location.href = base_url + '/contacts/whatsapp';
                }
            },{
                text: '<i class="fas fa-trash mb-1 text-success"></i>Delete',
                action: function ( e, dt, node, config ) {
                    selected = dt.rows( { selected: true } ).data();
                    val = [];
                    for (i = 0; i < selected.count(); i++) val.push(selected[i].id);
                    window.$.ajax({
                        url: '{{url('/contacts/delete')}}',
                        type: 'post',
                        data: {
                            _token: "{{csrf_token()}}",
                            ids: val,
                        },
                        success: function (result) {
                            if (result == 'success') {
                                table.draw();
                            }
                        }
                    });
                }
            },{
                text: '<i class="fas fa-user-plus mb-1 text-success"></i>Add',
                action: function ( e, dt, node, config ) {
                    myModal.show();
                }
            },{
                text: '<i class="fas fa-edit mb-1 text-success"></i>Edit',
                action: function ( e, dt, node, config ) {
                    selected = dt.rows( { selected: true } ).data();
                    selected = selected[0];
                    for (const key in selected) {
                        if (Object.hasOwnProperty.call(selected, key)) {
                            const element = selected[key];
                            if ($('#edit_' + key).length) $('#edit_' + key).val(element);
                        }
                    }
                    editModal.show();
                }
            },  
        ],
        order: [[1, 'asc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{url('/contacts/get_list')}}",
            type: "post",
            data: function ( d ) {
                d.country = $('#country').val();
                d._token = '{{csrf_token()}}';
                filters.find('input').each(function (i) {
                    d.columns[i + 1].search.value = $(this).val();
                }).get();
                return d;
            }
        },
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 0,
            data: 'emp',
            width: '5px',
        },{
            targets: 1,
            data: "assigned",
            width: '8px',
        },{
            targets: 2,
            data: "key",
            width: '8px',
        },{
            targets: 3, // Index of the 'date' column
            data: "date",
            width: "100px",
            className: 'date-column',
            render: function (data, type, row) {
                if (!data) {
                    // Render a blank cell with the calendar icon if no data exists
                    return `
                        <span class="date-cell" data-id="${row.id}" data-date="${data || ''}">
                            <i class="fas fa-calendar-alt date-picker-icon" style="cursor: pointer; margin-left: 5px;"></i>
                        </span>
                    `;
                } else {
                    const dateParts = data.split('-'); // Assuming `data` is in `YYYY-MM-DD` format
                    const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]); // Month is 0-based

                    // Format the date to `03-Sep-2022`
                    const day = date.getDate().toString().padStart(2, '0');
                    const month = date.toLocaleString('en-US', { month: 'short' });
                    const year = date.getFullYear();
                    const formattedDate = `${day}-${month}-${year}`;

                    // Render the date with a calendar icon if data exists
                    return `
                        <span class="date-cell" data-id="${row.id}" data-date="${data}">
                            ${formattedDate}
                            <i class="fas fa-calendar-alt date-picker-icon" style="cursor: pointer; margin-left: 5px;"></i>
                        </span>
                    `;
                }
            },
        },{
            targets: 4,
            data: "status",
            width: '40px',
            contentPadding: 0,
            render: function (val) {
                if (!val) return val;
                status = val.toLocaleLowerCase();
                if (status == 'no contact') {
                    return '<div class=\'Nocontact\'>' + val + '</div>'
                } else if (status == 'call 1') {
                    return '<div class=\'Call1\'>' + val + '</div>'
                } else if (status == 'call 2') {
                    return '<div class=\'Call2\'>' + val + '</div>'
                } else if (status == 'call 3') {
                    return '<div class=\'Call3\'>' + val + '</div>'
                } else if (status == 'almost') {
                    return '<div class=\'Almost\'>' + val + '</div>'
                } else if (status == 'customer') {
                    return '<div class=\'Customer\'>' + val + '</div>'
                } else if (status == 'not interested') {
                    return '<div class=\'Notinterested\'>' + val + '</div>'
                } else if (status == 'not interesting') {
                    return '<div class=\'Notinteresting\'>' + val + '</div>'
                }
                return val;
            },
            className: "contacts_status"
        },{
            targets: 5,
            data: "score",
            width: '20px',
        },{
            targets: 6,
            data: "company",
            width: '50px',
        },{
            targets: 7,
            data: "contact",
            width: '50px',
        },{
            targets: 8,
            data: "tel1",
            width: '35px',
        },{
            targets: 9,
            data: "tel2",
            width: '40px',
        },{
            targets: 10,
            data: "town",
            width: '50px',
        },{
            targets: 11,
            data: "area",
            width: '60px',
        },{
            targets: 12, // Index of the 'samples' column
            data: "samples",
            width: "100px",
            className: 'sample-column',
            render: function (data, type, row) {
                if (!data) {
                    // Render a blank cell with the calendar icon if no data exists
                    return `
                        <span class="sample-cell" data-id="${row.id}" data-samples="${data || ''}">
                            <i class="fas fa-calendar-alt sample-date-picker" style="cursor: pointer; margin-left: 5px;"></i>
                        </span>
                    `;
                } else {
                    const dateParts = data.split('-'); // Assuming `data` is in `YYYY-MM-DD` format
                    const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]); // Month is 0-based

                    // Format the date to `03-Sep-2022`
                    const day = date.getDate().toString().padStart(2, '0');
                    const month = date.toLocaleString('en-US', { month: 'short' });
                    const year = date.getFullYear();
                    const formattedDate = `${day}-${month}-${year}`;
                    // Render the date with a calendar icon if data exists
                    return `
                        <span class="sample-cell" data-id="${row.id}" data-samples="${data}">
                            ${formattedDate}
                            <i class="fas fa-calendar-alt sample-date-picker" style="cursor: pointer; margin-left: 5px;"></i>
                        </span>
                    `;
                }
            },
        },{
            targets: 13,
            data: "display",
            width: '10px',
        },{
            targets: 14,
            data: "prices",
            width: '10px',
        },{
            targets: 15,
            data: "brand",
            width: '30px',
        },{
            targets: 16,
            data: "comments",
            width: '80px',
        }]
    });

    function myCallbackFunction (updatedCell, updatedRow, oldValue) {
        // console.log("The new value for the cell is: ", updatedCell.data());
        // console.log("The values for each cell in that row are: ", updatedRow.data());
    }

    table.MakeCellsEditable({
        onUpdate: myCallbackFunction,
        columns: [1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16],
        inputCss:'my-input-class',
        confirmationButton: { 
            confirmCss: 'my-confirm-class',
            cancelCss: 'my-cancel-class'
        },
        inputTypes: [
            {
                "column": 4, 
                "type": "list",
                "options":[
                    { "value": "No contact", "display": "No Contact" },
                    { "value": "Call 1", "display": "Call 1" },
                    { "value": "Call 2", "display": "Send Sample" },
                    { "value": "Call 3", "display": "Sample Testing" },
                    { "value": "Standby", "display": "Standby" },
                    { "value": "Almost", "display": "Almost" },
                    { "value": "Customer", "display": "Customer" },
                    { "value": "Not interested", "display": "Not interested" },
                    { "value": "Not interesting", "display": "Not interesting" },
                    { "value": "COCKROACH", "display": "COCKROACH" },
                ]
            }
        ],
    })
    $('#date').datepicker({ dateFormat: "yy-mm-dd" });
    $('#samples').datepicker({ dateFormat: "yy-mm-dd" });
    $('#edit_samples').datepicker({ dateFormat: "yy-mm-dd" });
    $('#edit_date').datepicker({ dateFormat: "yy-mm-dd" });

    function checkDuplicatePhoneNumber(phone, id, callback) {
        $.ajax({
            url: "{{ url('/contacts/check-phone') }}",
            type: "POST",
            data: {
                phone: phone,
                id: id, // Pass current ID for edit
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                callback(response.exists);
            }
        });
    }

    $('#new_contacts form, #edit_contacts form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const tel1 = form.find('[name="tel1"]').val();
        const tel2 = form.find('[name="tel2"]').val();
        const id = form.find('[name="id"]').val(); // Only for edit

        if (tel1) {
            checkDuplicatePhoneNumber(tel1, id, function(exists) {
                if (exists) {
                    alert('Tel.1 is already in use.');
                    return false;
                } else if (tel2) {
                    checkDuplicatePhoneNumber(tel2, id, function(exists) {
                        if (exists) {
                            alert('Tel.2 is already in use.');
                            return false;
                        }
                        form.off('submit').submit(); // Continue submission
                    });
                } else {
                    form.off('submit').submit(); // Continue submission
                }
            });
        }
    });

    $('#contacts').on('click', '.date-picker-icon', function () {
        const dateCell = $(this).closest('.date-cell');
        const rowId = dateCell.data('id'); // Get the row ID
        const currentDate = dateCell.data('date'); // Get the current date

        // Create a temporary input field for the date picker
        const dateInput = $('<input type="text" class="date-picker-temp form-control" />').val(currentDate);

        // Replace the current cell content with the input field
        dateCell.html(dateInput);

        // Initialize jQuery UI Datepicker
        dateInput.datepicker({
            dateFormat: "yy-mm-dd", // Match your backend's date format
            onClose: function (selectedDate) {
                if (!selectedDate) {
                    // Restore the original content if no date is selected
                    dateCell.html(`${currentDate || ''} <i class="fas fa-calendar-alt date-picker-icon" style="cursor: pointer; margin-left: 5px;"></i>`);
                    return;
                }

                // Update the cell content with the selected date
                dateCell.html(`${selectedDate} <i class="fas fa-calendar-alt date-picker-icon" style="cursor: pointer; margin-left: 5px;"></i>`);

                // Send the selected date to the backend via AJAX
                $.ajax({
                    url: '/contacts/update-date', // Endpoint for updating the 'date' column
                    method: 'POST',
                    data: {
                        id: rowId,
                        date: selectedDate,
                        _token: "{{ csrf_token() }}", // CSRF token for security
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Date updated successfully');
                            // Optionally reload the DataTable
                            table.ajax.reload();
                        } else {
                            alert('Failed to update date');
                        }
                    },
                    error: function (xhr) {
                        alert('Error updating date');
                        console.error(xhr.responseText);
                    },
                });
            },
        });

        // Open the date-picker
        dateInput.datepicker('show');
    });


    $('#contacts').on('click', '.sample-date-picker', function () {
        const dateCell = $(this).closest('.sample-cell');
        const rowId = dateCell.data('id'); // Get the row ID
        const currentDate = dateCell.data('samples'); // Get the current sample date

        // Create a temporary input field for the date picker
        const dateInput = $('<input type="text" class="date-picker-temp form-control" />').val(currentDate);

        // Replace the current cell content with the input field
        dateCell.html(dateInput);

        // Initialize jQuery UI Datepicker
        dateInput.datepicker({
            dateFormat: "yy-mm-dd", // Match your backend's date format
            onClose: function (selectedDate) {
                if (!selectedDate) {
                    // Restore the original content if no date is selected
                    dateCell.html(`${currentDate || ''} <i class="fas fa-calendar-alt sample-date-picker" style="cursor: pointer; margin-left: 5px;"></i>`);
                    return;
                }

                // Update the cell content with the selected date
                dateCell.html(`${selectedDate} <i class="fas fa-calendar-alt sample-date-picker" style="cursor: pointer; margin-left: 5px;"></i>`);

                // Send the selected date to the backend via AJAX
                $.ajax({
                    url: '/contacts/update-sample', // Endpoint for updating the 'samples' column
                    method: 'POST',
                    data: {
                        id: rowId,
                        date: selectedDate,
                        _token: "{{ csrf_token() }}", // CSRF token for security
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Sample date updated successfully');
                            // Optionally reload the DataTable
                            table.ajax.reload();
                        } else {
                            alert('Failed to update sample date');
                        }
                    },
                    error: function (xhr) {
                        alert('Error updating sample date');
                        console.error(xhr.responseText);
                    },
                });
            },
        });

        // Open the date-picker
        dateInput.datepicker('show');
    });


});
</script>
@endsection
