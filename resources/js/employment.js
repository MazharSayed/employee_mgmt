$(document).ready(function () {
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

    $('#addEmploymentBtn').on('click', function () {
        $('#employmentForm').toggleClass('d-none');
        $('#employmentTable').toggleClass('d-none');
        $(this).text($(this).text() === 'Add Employment' ? 'Back to Employment History' : 'Add Employment');
    });

    $('#employmentForm').on('submit', function (e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function (response) {
            $('#employmentTable').removeClass('d-none');
            $('#employmentForm').addClass('d-none');
            loadEmploymentHistory();
            $('#addEmploymentBtn').text('Add Employment');
        }).fail(function () {
            $('#employmentMessage').removeClass('d-none').addClass('alert-danger').text('Error adding employment.');
        });
    });

    $(document).on('click', '.deleteEmployment', function () {
        let id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: `{{ url('employment-history/delete') }}/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    loadEmploymentHistory();
                    $('#employmentMessage').removeClass('d-none').addClass('alert-success').text('Employment deleted.');
                },
                error: function () {
                    $('#employmentMessage').removeClass('d-none').addClass('alert-danger').text('Error deleting.');
                }
            });
        }
    });
});
