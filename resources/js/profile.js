$(document).ready(function () {
    $('#profileForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("profile.update") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#profileMessage').removeClass('d-none').addClass('alert-success').text('Profile updated successfully!');
                location.reload();
            },
            error: function (xhr) {
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
});
