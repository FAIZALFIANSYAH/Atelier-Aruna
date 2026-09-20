@php
$user = auth()->user();
@endphp

<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">

    <ul class="navbar-nav">

        <li class="nav-item">

            <a class="nav-link" data-widget="pushmenu" href="#">

                <i class="fas fa-bars"></i>

            </a>

        </li>

        <li class="nav-item d-flex align-items-center">

            <span class="h5 mb-0 ml-2">

                Atelier Aruna

            </span>

        </li>

    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item">

            <span class="nav-link">

                {{ $user->name }}

                |

                {{ ucfirst($user->role->name) }}

            </span>

        </li>

    </ul>

</nav>
