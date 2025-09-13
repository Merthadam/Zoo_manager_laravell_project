<nav class="navbar">
    <div class="nav-left">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('enclosures.index') }}">Enclosures</a>

        @auth
            @if(auth()->user()->admin)
                <a href="{{ route('animals.index') }}">Archived Animals</a>
                <a href="{{ route('animals.create') }}">New Animal</a>
            @endif
        @endauth
    </div>

    <div class="nav-right">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        @endauth
    </div>
</nav>
