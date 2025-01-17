@extends('layouts.app')

@section('custom_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>



<style>
    .pipeline-container {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        margin-top: 20px;
    }

    .filter-section {
        display: flex;
        gap: 20px; /* Adjust spacing between filter groups */
        align-items: center;
        padding: 10px 0;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px; /* Space between label and select/input */
    }

    .filter-label {
        font-size: 14px;
        font-weight: bold;
        white-space: nowrap; /* Prevent label text from wrapping */
    }

    .filter-select, .filter-input {
        width: 200px; /* Adjust width as needed */
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
    }

    .status-column {
        flex: 1;
        min-width: 280px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        overflow-y: auto;
        max-height: calc(100vh - 120px);
    }

    .status-column h5 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #007bff;
        color: white;
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
        padding: 10px;
        font-size: 14px;
        display: flex;
        flex-wrap: wrap;
        width: 100%; /* Ensure the card takes up the full width of the container */
        height: 250px; /* Set a fixed height for uniformity */
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
        grid-gap: 10px; /* Add spacing between rows and columns */
        padding-left: 40px; /* Leave space for the checkbox */
        align-items: center; /* Vertically align items */
    }

    .contact-card p {
        margin: 0; /* Remove unnecessary margin */
        word-wrap: break-word; /* Handle long text gracefully */
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

    .contact-card i {
        margin-left: 5px;
        color: yellow;
    }

    .contact-card .fa-whatsapp,
    .contact-card .fa-calendar {
        color: green; /* Set color for WhatsApp and Calendar icons to green */
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
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        position: sticky;
        top: 0;
        background-color: white;
        padding: 10px 0;
        z-index: 20;
        border-bottom: 1px solid #ddd;
    }

    .filter-group {
        margin-left: 30px;
    }

    .filter-section select,
    .filter-section input {
        padding: 5px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
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

    .status-column h5 {
        display: flex;
        align-items: center;
        justify-content: center; /* Center horizontally */
        text-align: center; /* Align text inside the header */
        background: #007bff;
        color: white;
        padding: 10px;
        border-radius: 3px;
        margin: 0;
        position: sticky;
        top: 0;
        z-index: 10;
    }

</style>
@endsection

@section('content')

<div>
    <!-- Filter and Action Buttons -->
    <div class="filter-section">
        <div class="filter-group">
            <label for="city-filter" class="filter-label">City:</label>
            <select id="city-filter" class="filter-select">
                <option value="">All</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}" {{ $selectedCity === $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="search-box" class="filter-label">Search:</label>
            <input type="text" id="search-box" value="{{ $searchQuery }}" placeholder="Search..." class="filter-input">
        </div>
        <div class="action-buttons-container">
            <button id="btn-whatsapp" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> WhatsApp</button>
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
                <label>
                    <input type="radio" name="status-filter" value="{{ $status }}"> {{ $status }}
                </label>
            </h5>
            @if ($contacts->isEmpty())
            <p style="text-align: center; color: #aaa;">No data available</p>
            @else
            @foreach ($contacts as $contact)
            <div class="contact-card" data-contact-id="{{ $contact->id }}">
                <!-- Checkbox -->
                <div>
                    <input type="checkbox" class="checkbox">
                </div>
                

                <div class="row">
                    <p>{{ $contact->contact }}</p>
                    <p>{{ $contact->company }}</p>
                    <p>{{ $contact->tel1 }} <i class="fa-brands fa-whatsapp"></i></p>
                    <p>{{ $contact->tel2 }} <i class="fa-brands fa-whatsapp"></i></p>
                    <p>{{ $contact->town }}</p>
                    <p>{{ $contact->area }}</p>
                    <p>#{{ $contact->id }}</p>
                    <p>
                        {{ $contact->formatted_date ?? 'N/A' }} 
                        <i class="fa-solid fa-calendar" 
                        style="cursor: pointer;" 
                        onclick="openDatePicker({{ $contact->id }})"></i>
                    </p>
                </div>

                <!-- Edit Button -->
                <button class="edit-button" onclick="editContact({{ $contact->id }})">
                    <i class="fa-solid fa-pen"></i>
                </button>
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

                    <button type="submit" class="btn btn-primary">Send Email</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
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

        // Initialize Select2 for the city filter with direct input
        $('#city-filter').select2({
            tags: true, // Enable custom input
            placeholder: "Select or type a city", // Placeholder text
            allowClear: true, // Clear button option
            width: '100%' // Adjust width to fit the container
        });

        // Handle changes in the city select box
        $('#city-filter').on('change', function () {
            const city = $(this).val();
            const search = $('#search-box').val();
            window.location.href = `/pipeline?city=${city}&search=${search}`;
        });

        // Search box - Press Enter to display results
        $('#search-box').on('keypress', function (e) {
            if (e.which === 13) { // 13 is the keycode for Enter
                const city = $('#city-filter').val();
                const search = $(this).val();
                window.location.href = `/pipeline?city=${city}&search=${search}`;
            }
        });

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

     // Handle "Move Left" and "Move Right" buttons
     $('#btn-move-left').on('click', function () {
        moveContacts('left');
    });

    $('#btn-move-right').on('click', function () {
        moveContacts('right');
    });

    function moveContacts(direction) {
        const selectedContacts = $('.contact-card .checkbox:checked')
            .map(function () {
                return $(this).closest('.contact-card').data('contactId');
            })
            .get();

        if (selectedContacts.length === 0) {
            alert('Please select at least one contact to move.');
            return;
        }

        const confirmation = confirm(
            `Are you sure you want to move the selected contacts?`
        );
        if (!confirmation) {
            return; // Exit if the user cancels
        }

        $.ajax({
            url: '/pipeline/update', // Endpoint for updating the contact's status
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            contentType: 'application/json',
            data: JSON.stringify({ contact_ids: selectedContacts, direction }),
            success: function (response) {
                if (response.success) {
                    alert('Contacts moved successfully!');
                    window.location.reload(); // Reload the page to reflect updated statuses
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                alert('An error occurred while moving contacts. Check the console for details.');
            },
        });
    }

    $('.change-status').on('click', function () {
        const status = $(this).data('status');
        const selectedContacts = $('.contact-card .checkbox:checked')
            .map(function () {
                return $(this).closest('.contact-card').data('contactId');
            })
            .get();

        if (selectedContacts.length === 0) {
            alert('Please select at least one contact to change the status.');
            return;
        }

        const confirmation = confirm(`Are you sure you want to update the status to "${status}" for the selected contacts?`);
        if (!confirmation) {
            return; // Exit if the user cancels
        }

        // AJAX call to update the status
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
                    window.location.reload(); // Reload the page to reflect updated statuses
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                alert('An error occurred while updating the status. Check the console for details.');
            },
        });
    });

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

});

</script>
@endsection
