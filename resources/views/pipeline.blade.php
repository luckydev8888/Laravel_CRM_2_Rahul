@extends('layouts.app')

@section('custom_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">




<style>
    .pipeline-container {
        display: flex;
        overflow-x: auto;
        gap: 2px;
        margin-top: 20px;
    }

    .filter-section {
        display: flex;
        flex-wrap: wrap; /* Allow filters to wrap on smaller screens */
        gap: 10px; /* Reduce gap between filters */
        align-items: center;
        padding: 10px;
        justify-content: flex-start; /* Align items to the left */
    }

    .filter-group {
        display: flex;
        flex-direction: column; /* Stack label and select/input */
        align-items: flex-start; /* Align text to the left */
        gap: 5px; /* Reduce space between label and input */
        min-width: 180px; /* Set a minimum width for filters */
    }

    .filter-label {
        font-size: 14px;
        font-weight: bold;
        white-space: nowrap; /* Prevent label text from wrapping */
    }

    /* Adjust input and select width to prevent overflow */
    .filter-select,
    .filter-input {
        width: 100%; /* Make filters take full width inside their container */
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        max-width: 250px; /* Prevent excessive stretching */
    }

    .status-column {
        flex: 1;
        min-width: 300px;
        background-color: #f8f9fa;
        /* border: 1px solid #ddd; */
        /* border-radius: 5px; */
        padding: 5px;
        overflow-y: auto;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        scrollbar-width: none; /* For Firefox */
        -ms-overflow-style: none; /* For IE and Edge */
    }

    .status-column::-webkit-scrollbar {
        display: none; /* For Chrome, Safari, and Opera */
    }

    .status-column h5 {
        display: flex;
        align-items: center;
        justify-content: center; /* Center horizontally */
        text-align: center; /* Align text inside the header */
        background: transparent;  /* Set background to none */
        color: black; /* Ensure the text color is visible */
        padding: 10px;
        border-radius: 3px;
        margin: 0;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .contact-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        padding: 5px 20px 5px 5px;
        font-size: 14px;
        display: flex;
        flex-wrap: wrap;
        width: 100%; /* Ensure the card takes up the full width of the container */
        height: 120px; /* Set a fixed height for uniformity */
        box-sizing: border-box; /* Include padding and border in the dimensions */
        position: relative; /* For positioning child elements like checkbox and edit button */
    }

    .contact-card .checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        transform: scale(1.2); /* Make the checkbox slightly larger */
    }

    .contact-card .row {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Two equal columns */
        grid-gap: 0; /* Add spacing between rows and columns */
        padding-left: 40px; /* Leave space for the checkbox */
        align-items: center; /* Vertically align items */
    }

    .contact-card p {
        margin: 0; /* Remove unnecessary margin */
        /* word-wrap: break-word; Handle long text gracefully */
    }

    .contact-card i {
        margin-left: 5px;
        color: green; /* Set icon color */
    }

    .contact-card .edit-button {
        position: absolute;
        bottom: 10px;
        left: 10px; /* Ensure it's positioned at the bottom-left of the card */
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%; /* Make the button circular */
        width: 25px; /* Set equal width and height */
        height: 25px;
        cursor: pointer;
        font-size: 16px; /* Increase the icon size */
        display: flex;
        justify-content: center; /* Center icon horizontally */
        align-items: center; /* Center icon vertically */
        z-index: 1; /* Ensure it stays above other content */
    }

    .contact-card .edit-button i {
        margin-right: 0; /* Remove any unwanted margin */
        font-size: 16px; /* Adjust icon size to fit the circular button */
    }

    .contact-card .fa-pen {
        margin-left: 5px;
        color: yellow;
    }

    .contact-card .fa-phone-volume {
        position: absolute; /* Position relative to the parent container */
        bottom: 0; /* Align to the bottom */
        right: 0; /* Align to the right */
        font-size: 14px; /* Adjust icon size */
        background-color: #28a745; /* Green background for icon */
        color: white; /* White icon color */
        width: 20px; /* Circle size */
        height: 20px; /* Circle size */
        border-radius: 50%; /* Make it circular */
        display: flex; /* Center the icon inside */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Optional shadow */
        cursor: pointer; /* Make it interactive */
    }

    .contact-card .fa-calendar{
        color:blue;
    }

    .contact-card #contact-id {
        position: absolute;
        bottom: 10px;
        right: 10px;
        font-size: 14px;
        color: #555;
    }


    .filter-section {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
        padding: 10px;
        justify-content: space-between; /* Align filters to the left */
    }


    /* Container to group country & city filters */
    .filter-group-container {
        display: flex;
        align-items: center;
        gap: 10px; /* Reduce space between country and city filter */
    }

    /* Fix width of individual filters */
    .filter-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        min-width: 180px;
        max-width: 220px;
    }

    /* Ensure search box is aligned correctly */
    .search-box-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-width: 220px;
        max-width: 250px;
    }


    /* Ensure all select inputs take the same width */
    .filter-select, 
    .filter-input {
        width: 100%;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
    }

    .action-buttons-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .action-buttons-container button {
        padding: 10px 20px;
        font-size: 14px;
    }

    .status-buttons-container {
        display: flex;
        gap: 5px;
        margin-left: 10px;
    }

    .status-buttons-container button {
        padding: 5px 10px;
        font-size: 12px;
    }

    

    /* General styling for other fields */
    .contact-card p {
        overflow: hidden;
        white-space: nowrap; /* Prevent wrapping */
        text-overflow: ellipsis; /* Show "..." for truncated text */
        word-break: break-word; /* Handle long words */
    }

    /* Specific styling for phone numbers (tel1 and tel2) */
    .contact-card p[data-field="tel1"],
    .contact-card p[data-field="tel2"] {
        position: relative; /* Set parent container to relative */
        padding-right: 20px; /* Add space for the icon on the right */
        white-space: normal; /* Allow wrapping if necessary */
        word-break: break-word; /* Break long phone numbers if needed */
        font-size: 14px; /* Ensure consistent font size */
    }

    /* Fix responsiveness */
    @media (max-width: 768px) {
        .filter-section {
            justify-content: center; /* Center filters on smaller screens */
        }

        .filter-group-container {
            flex-direction: column; /* Stack country & city filters on small screens */
            align-items: center;
        }

        .search-box-group {
            width: 100%;
            align-items: center;
        }
    }

</style>
@endsection

@section('content')

<div style="background-color:#f8f9fa">
    <!-- Filter and Action Buttons -->
    <div class="filter-section">
        <div class="filter-group-container">
            <!-- Country Filter -->
            <div class="filter-group">
                <label for="country-filter" class="filter-label">Country:</label>
                <select id="country-filter" class="filter-select">
                    <option value="">All</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ $selectedCountry === $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter -->
            <div class="filter-group">
                <label for="city-filter" class="filter-label">City:</label>
                <select id="city-filter" class="filter-select">
                    <option value="">All</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}" {{ $selectedCity === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Area Filter -->
            <div class="filter-group">
                <label for="area-filter" class="filter-label">Area:</label>
                <select id="area-filter" class="filter-select">
                    <option value="">All</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area }}" {{ $selectedArea === $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <!-- Search Box -->
        <div class="filter-group search-box-group">
            <label for="search-box" class="filter-label">Search:</label>
            <input type="text" id="search-box" value="{{ $searchQuery }}" placeholder="Search..." class="filter-input">
        </div>
        <div class="action-buttons-container">
            <!-- <button id="btn-whatsapp" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> WhatsApp</button> -->
            <button id="btn-email" class="btn btn-primary"><i class="fa-solid fa-envelope"></i> Email</button>
            <button id="btn-move-left" class="btn btn-warning"><i class="fa-solid fa-arrow-left"></i> Move</button>
            <button id="btn-move-right" class="btn btn-warning">Move <i class="fa-solid fa-arrow-right"></i></button>

            <!-- New Buttons for Changing Status -->
            <div class="status-buttons-container" style="margin-right:30px;">
                <button class="btn btn-secondary change-status" data-status="Customer">Customer</button>
                <button class="btn btn-secondary change-status" data-status="Not interested">Not interested</button>
                <button class="btn btn-secondary change-status" data-status="Not interesting">Not interesting</button>
                <button class="btn btn-secondary change-status" data-status="COCKROACH">COCKROACH</button>
            </div>
        </div>
    </div>

    <!-- Pipeline Container -->
    <div class="pipeline-container">
        @foreach ($statuses as $status => $contacts)
        <div class="status-column">
            <h5>
                <label> {{ $status }}</label>
            </h5>
            @if ($contacts->isEmpty())
            <p style="text-align: center; color: #aaa;">No data available</p>
            @else
            @foreach ($contacts as $contact)
            <div class="contact-card" data-contact-id="{{ $contact->id }}"  data-status="{{ $status }}">
                <div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox">
                    </div>
                    <!-- Edit Button -->
                    <button class="edit-button" onclick="editContact({{ $contact->id }})">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </div>

                <div class="row">
                    <p data-field="contact">
                        @if (!empty($contact->contact))
                            {{ $contact->contact }}
                        @else
                            
                        @endif
                    </p>
                    <p data-field="company">
                        @if (!empty($contact->company))
                            {{ $contact->company }}
                        @else
                            
                        @endif
                    </p>
                    <p data-field="tel1">
                        @if (!empty($contact->tel1))
                            {{ $contact->tel1 }}
                            <i class="fa-solid fa-phone-volume"></i>
                        @else
                            
                        @endif
                    </p>
                    <p data-field="tel2">
                        @if (!empty($contact->tel2))
                            {{ $contact->tel2 }}
                            <i class="fa-solid fa-phone-volume"></i>
                        @else
                            
                        @endif
                    </p>
                    <p data-field="town">
                        @if (!empty($contact->town))
                            {{ $contact->town }}
                        @else
                            
                        @endif
                    </p>
                    <p data-field="area">
                        @if (!empty($contact->area))
                            {{ $contact->area }}
                        @else
                            
                        @endif
                    </p>
                    <p style="text-align:left;">#{{ $contact->id }}</p>
                    <p>
                        {{ $contact->formatted_date ?? 'N/A' }} 
                        <i class="fa fa-calendar calendar-icon" 
                        style="cursor: pointer;" 
                        onclick="openDatePicker({{ $contact->id }})"></i>
                    </p>
                </div>

            </div>

            @endforeach
            @endif
        </div>
        @endforeach
    </div>
</div>

<div class="modal fade" tabindex="-1" id="edit_contacts">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5">
                <form action="{{ route('pipeline.contact.edit') }}" method="POST" enctype="multipart/form-data">
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
                    </div>
                    <!-- Add fields for contact details -->
                    <div class="row justify-content-between">
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
                    <!-- Additional fields -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="emailForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="from_email" class="form-label">From Email</label>
                        <input type="email" class="form-control" id="from_email" name="from_email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="to_email" class="form-label">To Email</label>
                        <input type="email" class="form-control" id="to_email" name="to_email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment (Optional)</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                    <!-- Button container -->
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Send Email</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whatsappModalLabel">Send WhatsApp Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="whatsappForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="from_whatsapp" class="form-label">From WhatsApp Number</label>
                        <input type="text" class="form-control" id="from_whatsapp" name="from_whatsapp" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="to_whatsapp" class="form-label">To WhatsApp Number</label>
                        <input type="text" class="form-control" id="to_whatsapp" name="to_whatsapp" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp_message" class="form-label">Message</label>
                        <textarea class="form-control" id="whatsapp_message" name="whatsapp_message" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp_attachment" class="form-label">Attachment (Optional)</label>
                        <input type="file" class="form-control" id="whatsapp_attachment" name="whatsapp_attachment">
                    </div>
                    <!-- Button container -->
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Send Message</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to proceed?</p>
                <div class="d-flex justify-content-center mt-4">
                    <button id="confirmNo" class="btn btn-danger me-2" data-bs-dismiss="modal">NO</button>
                    <button id="confirmYes" class="btn btn-success" data-bs-dismiss="modal">YES</button>
                </div>
            </div>
        </div>
    </div>
</div>







@endsection

@section('custom_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    $(document).ready(function () {

        const edit_tel1Input = document.querySelector("#edit_tel1");
        const edit_tel2Input = document.querySelector("#edit_tel2");

        const options = {
            separateDialCode: false,
            initialCountry: "es",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        };
        window.intlTelInput(edit_tel1Input, options);
        window.intlTelInput(edit_tel2Input, options);

        $('#edit_samples').datepicker({ dateFormat: "yy-mm-dd" });
        $('#edit_date').datepicker({ dateFormat: "yy-mm-dd" });

        const cityFilter = $('#city-filter');
        const searchBox = $('#search-box');


        // Filter by city
        cityFilter.on('change', function () {
            const city = cityFilter.val();
            const search = searchBox.val();
            window.location.href = `/pipeline?city=${city}&search=${search}`;
        });

        // Search functionality
        searchBox.on('input', function () {
            const city = cityFilter.val();
            const search = searchBox.val();
            window.location.href = `/pipeline?city=${city}&search=${search}`;
        });

        // Function to open and populate the edit modal
        window.editContact = function (contactId) {
            // Fetch contact details using the PipelineController route
            $.ajax({
                url: `/pipeline/${contactId}/getdata`, // Route defined in web.php
                method: 'GET',
                success: function (response) {
                    // Populate modal fields with contact data
                    $('#edit_id').val(response.id);
                    $('#edit_contact').val(response.contact);
                    $('#edit_country').val(response.country);
                    $('#edit_assigned').val(response.assigned);
                    $('#edit_status').val(response.status);
                    $('#edit_date').val(response.date);
                    $('#edit_company').val(response.company);
                    $('#edit_tel1').val(response.tel1);
                    $('#edit_tel2').val(response.tel2);
                    $('#edit_town').val(response.town);
                    $('#edit_email').val(response.email);
                    $('#edit_area').val(response.area);
                    $('#edit_brand').val(response.brand);
                    $('#edit_score').val(response.score);
                    $('#edit_samples').val(response.samples);
                    $('#edit_comments').val(response.comments);

                    // Open the modal
                    $('#edit_contacts').modal('show');
                },
                error: function (xhr) {
                    console.error('Error fetching contact details:', xhr.responseText);
                    alert('Failed to load contact details.');
                },
            });
        };

        let actionType = null; // Track which action is being confirmed
        let selectedContacts = [];
        let statusToUpdate = null;

        // Event handler for Move buttons
        $('#btn-move-left, #btn-move-right').on('click', function () {
            actionType = $(this).attr('id'); // Determine which button was clicked
            selectedContacts = getSelectedContacts();

            if (selectedContacts.length === 0) {
                alert('Please select at least one contact.');
                return;
            }

            $('#confirmModal').modal('show'); // Show the confirmation modal
        });

        // Event handler for status buttons
        $('.change-status').on('click', function () {
            actionType = 'status-change';
            statusToUpdate = $(this).data('status');
            selectedContacts = getSelectedContacts();

            if (selectedContacts.length === 0) {
                alert('Please select at least one contact.');
                return;
            }

            $('#confirmModal').modal('show'); // Show the confirmation modal
        });

        // Confirm button logic
        $('#confirmYes').on('click', function () {
            if (actionType === 'btn-move-left' || actionType === 'btn-move-right') {
                moveContacts(actionType === 'btn-move-left' ? 'left' : 'right');
            } else if (actionType === 'status-change') {
                updateStatus(statusToUpdate);
            }
        });

        function getSelectedContacts() {
            return $('.contact-card .checkbox:checked')
                .map(function () {
                    return $(this).closest('.contact-card').data('contactId');
                })
                .get();
        }

        function moveContacts(direction) {
            $.ajax({
                url: '/pipeline/update',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                contentType: 'application/json',
                data: JSON.stringify({ contact_ids: selectedContacts, direction }),
                success: function (response) {
                    if (response.success) {
                        alert('Contacts moved successfully!');
                        window.location.reload();
                    } else {
                        alert(response.message || 'Failed to move contacts.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while moving contacts.');
                },
            });
        }

        function updateStatus(status) {
            $.ajax({
                url: '/pipeline/update-status',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                contentType: 'application/json',
                data: JSON.stringify({ contact_ids: selectedContacts, status }),
                success: function (response) {
                    if (response.success) {
                        alert('Status updated successfully!');
                        window.location.reload();
                    } else {
                        alert(response.message || 'Failed to update status.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while updating the status.');
                },
            });
        }

        // Open email modal
        $('#btn-email').on('click', function () {
            const selectedContacts = $('.contact-card .checkbox:checked')
                .map(function () {
                    return $(this).closest('.contact-card').data('contactId');
                })
                .get();

            if (selectedContacts.length !== 1) {
                alert('Please select exactly one contact to send an email.');
                return;
            }

            const contactId = selectedContacts[0];

            // Fetch contact details
            $.ajax({
                url: `/pipeline/${contactId}/getdata`,
                method: 'GET',
                success: function (response) {
                    $('#from_email').val("{{ config('mail.from.address') }}"); // From email from .env
                    $('#to_email').val(response.email); // To email from client data
                    $('#emailModal').modal('show');
                },
                error: function (xhr) {
                    alert('Failed to load contact details. Please try again.');
                },
            });
        });

        // Handle email form submission
        $('#emailForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/pipeline/send-email',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response.message);
                    $('#emailModal').modal('hide');
                },
                error: function (xhr) {
                    alert('Failed to send email. Please try again.');
                },
            });
        });

         // Function to open the date picker when clicking the calendar icon
        function openDatePicker(contactId, calendarIcon) {
            // Create a temporary input element for the date picker
            const input = document.createElement("input");
            input.type = "text";
            input.style.position = "absolute";
            input.style.opacity = 0;

            // Append the input to the body
            document.body.appendChild(input);

            // Get the position of the calendar icon
            const iconOffset = $(calendarIcon).offset();

            // Initialize the date picker at the position of the calendar icon
            $(input).datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function (dateText) {
                    // Update the date via AJAX
                    updateContactDate(contactId, dateText);
                    $(this).datepicker("destroy");
                    document.body.removeChild(input);
                },
            });

            // Position the input at the calendar icon's position
            $(input).css({
                top: iconOffset.top,
                left: iconOffset.left,
            });

            $(input).datepicker("show");
        }

        // Function to send an AJAX request to update the contact date
        function updateContactDate(contactId, selectedDate) {
            $.ajax({
                url: "/pipeline/update-date",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    contact_id: contactId,
                    date: selectedDate,
                },
                success: function (response) {
                    if (response.success) {
                        alert("Date updated successfully!");
                        window.location.reload(); // Reload to reflect the updated date
                    } else {
                        alert(response.message || "Failed to update the date.");
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("An error occurred while updating the date.");
                },
            });
        }

        // Attach the event listener to the calendar icons
        $('.fa-calendar').on('click', function () {
            const contactId = $(this).closest('.contact-card').data('contactId');
            openDatePicker(contactId, this); // Pass the clicked icon to position the date picker
        });

        $('#btn-whatsapp').on('click', function () {
            const selectedContacts = $('.contact-card .checkbox:checked')
                .map(function () {
                    return $(this).closest('.contact-card').data('contactId');
                })
                .get();

            if (selectedContacts.length !== 1) {
                alert('Please select exactly one contact to send a WhatsApp message.');
                return;
            }

            const contactId = selectedContacts[0];

            // Fetch contact details
            $.ajax({
                url: `/pipeline/${contactId}/getdata`, // Replace with the route to fetch contact data
                method: 'GET',
                success: function (response) {
                    // $('#from_whatsapp').val("{{ config('whatsapp.from') }}"); // Your WhatsApp sender number from the config
                    const fromWhatsApp = "{{ config('services.twilio.whatsapp_from') }}"; // Directly use Blade to get the value
                    $('#from_whatsapp').val(fromWhatsApp); // Set the value in the modal
                    $('#to_whatsapp').val(response.tel2); // Client's WhatsApp number
                    // $('#whatsapp_subject').val(""); // Optional, clear previous subject
                    $('#whatsapp_message').val(""); // Optional, clear previous message
                    $('#whatsappModal').modal('show');
                },
                error: function (xhr) {
                    alert('Failed to load contact details. Please try again.');
                },
            });
        });

        $('#whatsappForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/pipeline/send-whatsapp', // Define the route to send WhatsApp messages
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response.message || 'Message sent successfully!');
                    $('#whatsappModal').modal('hide');
                },
                error: function (xhr) {
                    alert('Failed to send the WhatsApp message. Please try again.');
                },
            });
        });

        let originalContent = ''; // Store the original content for restoration
        
        // Detect button clicks
        let isButtonClick = false; // Flag to track button clicks
        $('.btn').on('mousedown', function () {
            isButtonClick = true; // Set the flag to true when a button is clicked
        });
        // Enable inline editing for contact-card fields
        $('.contact-card').on('click', 'p', function (e) {
            e.stopPropagation();

            const $this = $(this);
            const contactId = $this.closest('.contact-card').data('contact-id');
            const fieldName = $this.data('field'); // Use `data-field` for identifying the field

            if (!fieldName || $this.find('input').length) return; // Prevent nested editing

            originalContent = $this.text().trim();
            const $input = $(`<input type="text" class="form-control" />`)
                .val(originalContent)
                .data('field', fieldName)
                .data('contact-id', contactId)
                .on('keypress', function (e) {
                    if (e.which === 13) saveChanges($(this)); // Save on Enter
                });

            $this.html($input); // Replace the content with input
            $input.focus(); // Focus on the input field
        });


        // Save changes on input blur or pressing Enter
        $(document).on('blur', '.contact-card input', function () {
            const $input = $(this);
            // Check if the blur event originated from the checkbox
            const isCheckbox = $input.closest('.contact-card').find('.checkbox:focus').length > 0;
            if (isCheckbox || isButtonClick) {
                isButtonClick = false; // Reset the flag
                return; // Exit if the checkbox or button is interacted with
            }
            saveChanges($(this));
        });

        // Function to save changes via AJAX
        function saveChanges($input) {
            const newValue = $input.val().trim();
            const fieldName = $input.data('field');
            const contactId = $input.data('contact-id');
            const $parent = $input.closest('p');

            if (newValue === originalContent) {
                // Restore original content if unchanged
                $parent.text(originalContent);
                return;
            }

            $.ajax({
                url: `/pipeline/contact/${contactId}/edit-inline`, // Define route in the controller
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    field: fieldName,
                    value: newValue,
                },
                success: function (response) {
                    if (response.success) {
                        $parent.text(newValue); // Update content
                        location.reload(); // Reload to reflect updated data
                    } else {
                        alert('Failed to save changes. Please try again.');
                        $parent.text(originalContent); // Revert on failure
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred while saving. Please try again.');
                    $parent.text(originalContent); // Revert on failure
                },
            });
        }

        const statusColors = {
            "No contact": "#fffef9", // Blanched Almond
            "Call 1": "#fefcea", // Light Blue
            "Send Sample": "#e8f5f8", // Light Green
            "Sample Testing": "#eaf1d8", // Light Coral
            "Standby": "#f8f4ad", // Peach Puff
            "Almost": "#fcf3f3" // Light Grey
        };

        $('.contact-card').each(function () {
            const status = $(this).data('status');
            if (statusColors[status]) {
                $(this).css('background-color', statusColors[status]);
            }
        });

        // Initialize Select2 for country and city filters
        $('#country-filter, #city-filter, #area-filter').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // Handle country filter change
        $('#country-filter').on('change', function () {
            const country = $(this).val();
            const city = $('#city-filter').val();
            const area = $('#area-filter').val();
            const search = $('#search-box').val();
            window.location.href = `/pipeline?country=${country}&city=${city}&area=${area}&search=${search}`;
        });

        // Handle city filter change
        $('#city-filter').on('change', function () {
            const country = $('#country-filter').val();
            const area = $('#area-filter').val();
            const city = $(this).val();
            const search = $('#search-box').val();
            window.location.href = `/pipeline?country=${country}&city=${city}&area=${area}&search=${search}`;
        });

        // Handle area filter change
        $('#area-filter').on('change', function () {
            const country = $('#country-filter').val();
            const city = $('#city-filter').val();
            const area = $(this).val();
            const search = $('#search-box').val();
            window.location.href = `/pipeline?country=${country}&city=${city}&area=${area}&search=${search}`;
        });

        // Handle search input (Press Enter to trigger search)
        $('#search-box').on('keypress', function (e) {
            if (e.which === 13) { // 13 is Enter key
                const country = $('#country-filter').val();
                const city = $('#city-filter').val();
                const area = $('#area-filter').val();
                const search = $(this).val();
                window.location.href = `/pipeline?country=${country}&city=${city}&area=${area}&search=${search}`;
            }
        });
});

</script>
@endsection
