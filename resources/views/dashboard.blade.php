@extends('layouts.adminlayout')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fas fa-user"></i>
            </div>
            <div> Employee Profile </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Profile Section --}}
    <div class="col-md-6">
        <div class="mb-3 main-card card">
            <div class="card-body">
                <h5 class="card-title">Update Profile</h5>

                <!-- Profile Update Feedback Message -->
                <div id="profileMessage" class="alert d-none"></div>

                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <div id="profileError" class="alert alert-danger d-none"></div>

                    {{-- Profile Picture --}}
                    <div class="form-group">
                        <label for="profile_image">Profile Picture</label>
                        <div class="mb-3 profile-img-wrapper">
                            @if($employee->profile_picture)
                                <!-- Display Profile Picture -->
                                <img src="{{asset('uploads/profile_picture/'.$employee->profile_picture)}}" alt="Profile Picture" class="profile-picture">
                            @else
                                <!-- Display Initials if no profile picture -->
                                <div class="profile-initials">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    {{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="profile_picture" class="form-control-file">
                    </div>

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control" required>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" required>
                    </div>

                    {{-- Phone --}}
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" value="{{ $employee->phone ?? '' }}" class="form-control">
                    </div>

                    {{-- Skills --}}
                    <div class="form-group">
                        <label for="skills">Skills</label>
                        <input type="text" name="skills" value="{{ $employee->skills ?? '' }}" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Employment History Section --}}
    <div class="col-md-6">
        <div class="mb-3 main-card card">
            <div class="card-body">
                <h5 class="card-title">Employment History</h5>

                <!-- Employment Feedback Message -->
                <div id="employmentMessage" class="alert d-none"></div>

                <!-- Add Employment Button -->
                <button id="addEmploymentBtn" class="mb-3 btn btn-primary">Add Employment</button>

                <!-- Employment Form (Initially Hidden) -->
                <form id="employmentForm" method="POST" action="{{ route('employment.store') }}" class="d-none">
                    @csrf
                    <!-- Employer Name -->
                    <div class="form-group">
                        <label for="employer_name">Employer Name</label>
                        <input type="text" name="employer_name" id="employer_name" class="form-control" required>
                    </div>

                    <!-- Position -->
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" name="position" id="position" class="form-control" required>
                    </div>

                    <!-- Occupation -->
                    <div class="form-group">
                        <label for="occupation">Occupation</label>
                        <input type="text" name="occupation" id="occupation" class="form-control" required>
                    </div>

                    <!-- Manager Name -->
                    <div class="form-group">
                        <label for="manager_name">Manager Name</label>
                        <input type="text" name="manager_name" id="manager_name" class="form-control" required>
                    </div>

                    <!-- Manager Email -->
                    <div class="form-group">
                        <label for="manager_email">Manager Email</label>
                        <input type="email" name="manager_email" id="manager_email" class="form-control" required>
                    </div>

                    <!-- Manager Phone -->
                    <div class="form-group">
                        <label for="manager_phone">Manager Phone</label>
                        <input type="text" name="manager_phone" id="manager_phone" class="form-control" required>
                    </div>

                    <!-- Employment Start Date -->
                    <div class="form-group">
                        <label for="start_date">Employment Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Add Employment</button>
                </form>

                <!-- Employment History Table (Initially Shown) -->
                <table class="table mt-4" id="employmentTable">
                    <thead>
                        <tr>
                            <th>Employer Name</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dynamic Employment History Rows --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle Profile Form Submission
        $('#profileForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Reset message area
            $('#profileMessage').removeClass('alert-success alert-danger').addClass('d-none');

            // Create a FormData object to handle file uploads
            let formData = new FormData(this);

            // AJAX request
            $.ajax({
                url: '{{ route("profile.update") }}', // The route for updating the profile
                type: 'POST',
                data: formData,
                processData: false, // Important for FormData
                contentType: false, // Important for FormData
                success: function (response) {
                    // Success feedback
                    $('#profileMessage').removeClass('d-none').addClass('alert-success').text('Profile updated successfully!');
                    location.reload(); // Reload the page to reflect the updated profile picture
                },
                error: function (xhr) {
                    // Error handling
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    for (let key in errors) {
                        errorHtml += `<li>${errors[key]}</li>`;
                    }
                    errorHtml += '</ul>';
                    $('#profileMessage').removeClass('d-none').addClass('alert-danger').html(errorHtml);
                }
            });
        });

        // Load Employment History
        function loadEmploymentHistory() {
            $.get('{{ route("employment.index") }}', function (data) {
                let rows = '';
                data.forEach(function (entry) {
                    rows += `
                        <tr>
                            <td>${entry.employer_name}</td>
                            <td>${entry.position}</td>
                            <td>
                                <a href="{{ url('employment-history/details/${entry.id}') }}" class="btn btn-sm btn-primary">Details</a>
                                <button class="btn btn-sm btn-danger deleteEmployment" data-id="${entry.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $('#employmentTable tbody').html(rows);
            });
        }

        loadEmploymentHistory();

        // Toggle form visibility when "Add Employment" button is clicked
        $('#addEmploymentBtn').on('click', function() {
            $('#employmentForm').toggleClass('d-none'); // Toggle the form visibility
            $('#employmentTable').toggleClass('d-none'); // Toggle the table visibility
            $(this).text($(this).text() === 'Add Employment' ? 'Back to Employment History' : 'Add Employment');
        });

        // Handle Employment Form Submission
        $('#employmentForm').on('submit', function(e) {
            e.preventDefault();

            // Reset message area
            $('#employmentMessage').removeClass('alert-success alert-danger').addClass('d-none');

            $.post($(this).attr('action'), $(this).serialize(), function(response) {
                $('#employmentTable').removeClass('d-none'); // Show the table
                $('#employmentForm').addClass('d-none'); // Hide the form
                loadEmploymentHistory(); // Reload employment history
                $('#addEmploymentBtn').text('Add Employment'); // Reset button text

                // Success message
                $('#employmentMessage').removeClass('d-none').addClass('alert-success').text('Employment record added successfully!');
            }).fail(function() {
                $('#employmentMessage').removeClass('d-none').addClass('alert-danger').text('Error adding employment. Please try again.');
            });
        });

        // Delete Employment
        $(document).on('click', '.deleteEmployment', function () {
            if (confirm('Are you sure?')) {
                let id = $(this).data('id');

                // Reset message area
                $('#employmentMessage').removeClass('alert-success alert-danger').addClass('d-none');

                $.ajax({
                    url: `{{ url('employment-history/delete') }}/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        loadEmploymentHistory();
                        $('#employmentMessage').removeClass('d-none').addClass('alert-success').text('Employment record deleted successfully!');
                    },
                    error: function () {
                        $('#employmentMessage').removeClass('d-none').addClass('alert-danger').text('Error deleting employment record. Please try again.');
                    }
                });
            }
        });
    });
</script>
@endsection
