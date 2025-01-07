@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6">Employee Profile</h2>

    <!-- Profile Form -->
    <form id="profileForm" class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        @csrf
        <div class="space-y-4">
            <!-- Phone -->
            <div>
                <label for="phone" class="block text-lg font-medium text-gray-700">Phone</label>
                <input
                    type="text"
                    name="phone"
                    value="{{ $employee->phone ?? '' }}"
                    class="mt-2 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter your phone number"
                >
            </div>

            <!-- Skills -->
            <div>
                <label for="skills" class="block text-lg font-medium text-gray-700">Skills</label>
                <input
                    type="text"
                    name="skills"
                    value="{{ $employee->skills ?? '' }}"
                    class="mt-2 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter your skills"
                >
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Update Profile
                </button>
            </div>
        </div>
    </form>

    <!-- Employment History Section -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Employment History</h3>
    <div id="employmentHistoryList" class="space-y-4">
        <!-- Employment history entries will be dynamically loaded here -->
    </div>

    <button
        id="addEmploymentBtn"
        class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
        Add Employment
    </button>
</div>

<script>
    // Profile update via AJAX
    $('#profileForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("profile.update") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response.message);
            }
        });
    });

    // Load employment history
    function loadEmploymentHistory() {
        $.get('{{ route("employment.index") }}', function(data) {
            $('#employmentHistoryList').html(data.map(function(row) {
                return `
                    <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <h4 class="text-xl font-semibold">${row.employer_name} - ${row.position}</h4>
                            <p class="text-gray-600">Start Date: ${row.start_date}</p>
                        </div>
                        <button class="deleteEmploymentBtn text-red-600 hover:text-red-800" data-id="${row.id}">Delete</button>
                    </div>
                `;
            }).join(''));
        });
    }

    loadEmploymentHistory();

    // Handle adding employment history
    $('#addEmploymentBtn').click(function() {
        // Dummy employment data, replace with actual data submission logic
        const newEmployment = {
            employer_name: 'New Employer',
            position: 'New Position',
            start_date: '2024-01-01'
        };

        $.ajax({
            url: '{{ route("employment.store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ...newEmployment
            },
            success: function(response) {
                alert('Employment history added');
                loadEmploymentHistory();  // Reload employment history
            },
            error: function(xhr, status, error) {
                alert('Failed to add employment history');
            }
        });
    });

    // Handle deleting employment history
    $(document).on('click', '.deleteEmploymentBtn', function() {
        const employmentId = $(this).data('id');
        $.ajax({
            url: '{{ route("employment.destroy", ":id") }}'.replace(':id', employmentId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Employment history deleted');
                loadEmploymentHistory();
            },
            error: function(xhr, status, error) {
                alert('Failed to delete employment history');
            }
        });
    });
</script>
@endsection
