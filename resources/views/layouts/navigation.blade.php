<nav class="navbar navbar-expand-lg wb-nav sticky-top">

    <div class="container">

        <a class="navbar-brand wb-brand" href="/">
            Smart Mental Health Chatbot
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                @auth

                @if(Auth::user()->role === 'student')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('chat.index') }}">
                            Chatbot
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/mood">
                            Mood Tracking
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            Admin
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('chat.index') }}">
                            Chatbot
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/mood">
                            Mood Tracking
                        </a>
                    </li>
                @endif

                <li class="nav-item dropdown ms-3">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                        {{ Auth::user()->name }}

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="/profile">
                                Profile
                            </a>
                        </li>

                        <li>

                            <form method="POST" action="{{ route('logout') }}">

                                @csrf

                                <button type="submit" class="dropdown-item">
                                    Logout
                                </button>

                            </form>

                        </li>

                    </ul>

                </li>

                @else

                <li class="nav-item ms-3">
                    <a class="btn btn-wb-primary btn-sm" href="/login">
                        Login
                    </a>
                </li>

                @endauth

            </ul>

        </div>

    </div>

</nav>
