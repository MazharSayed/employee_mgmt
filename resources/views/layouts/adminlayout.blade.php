<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile</title>

    <!-- Required CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow" style="background: #fff!important">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="ml-auto header__pane">
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
            <div class="app-header__menu">
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </button>
            </div>
            <div class="app-header__content">
                <div class="app-header-right">
                    <div class="header-btn-lg">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="40" class="rounded-circle" src="{{ asset('svg/logo1.png') }}" alt="">
                                            <i class="ml-2 fa fa-angle-down opacity-8" style="color: #000;"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                                <i class="mr-2 fas fa-user-circle"></i> Profile
                                            </a>
                                            <form method="post" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="mr-2 fas fa-sign-out-alt"></i> Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li>
                                <a href="" class="mt-4">
                                    <i class="metismenu-icon fa fa-user"></i> Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Required JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                order: [[0, "desc"]]
            });
        });

        // Year Picker Modal
        document.addEventListener("DOMContentLoaded", function () {
            const yearPickerInput = document.getElementById('yearPickerInput');
            const startYear = 1900;
            const endYear = new Date().getFullYear();
            let yearOptions = '';

            for (let year = endYear; year >= startYear; year--) {
                yearOptions += `<option value="${year}">${year}</option>`;
            }

            const yearPickerModal = document.createElement('div');
            yearPickerModal.classList.add('modal', 'fade');
            yearPickerModal.id = 'yearPickerModal';
            yearPickerModal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Year</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-select" id="yearSelect" size="10">
                                ${yearOptions}
                            </select>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(yearPickerModal);
            const yearPickerModalInstance = new bootstrap.Modal(yearPickerModal);

            yearPickerInput.addEventListener('click', function () {
                yearPickerModalInstance.show();
            });

            document.getElementById('yearSelect').addEventListener('change', function () {
                yearPickerInput.value = this.value;
                yearPickerModalInstance.hide();
            });
        });
    </script>
</body>
</html>
