<?php use App\Http\Controllers\CartController; $id = Auth::id()?>


@guest
{{-- Not logged in --}}
  @if (Route::has('login'))
  <nav class="navbar navbar-expand-lg bg-light border-top border-3 border-dark mt-2 " style="display:10vh;">
        <div class="container-fluid my-2">
          <a class="navbar-brand justify-content-center w-25 text-center" href="/main"><span class="h2 fw-bold">House of Parfum<span class="text-danger">.</span></span></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse " id="navbarNav">
            <ul class="navbar-nav nav-fill w-100">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/main"><span class="h4 text-muted">Home</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><span class="h4 text-muted">About</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/perfume"><span class="h4 text-muted">Fragrance</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/type"><span class="h4 text-muted">Types</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/season"><span class="h4 text-muted">Season</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/contact"><span class="h4 text-muted">Contact Us</span></a>
              </li>
            </ul>
          </div>
          <div class="me-5">
            <a class="navbar-brand justify-content-center  text-center" href="{{ route('login') }}">
              <button class="btn btn-danger rounded-pill status-width" ><span class="h5 fw-bold">Login</span></button>
            </a>
            <a class="navbar-brand justify-content-center text-center" href="{{ route('register') }}">
              <button class="btn btn-danger rounded-pill status-width"><span class="h5 fw-bold">Register</span></button>
            </a>
          </div>
        </div>
      </nav>
    @endif

{{-- Logged in --}}
  @else
    <nav class="navbar navbar-expand-lg bg-light border-top border-3 border-dark mt-2 " style="display:10vh;">
        <div class="container-fluid my-2">
          <a class="navbar-brand justify-content-center w-25 text-center" href="/main"><span class="h2 fw-bold">House of Parfum<span class="text-danger">.</span></span></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          @if(Session::get('role') === '1')
          <div class="collapse navbar-collapse " id="navbarNav">
            <ul class="navbar-nav nav-fill w-100">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/main"><span class="h4 text-muted">Home</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><span class="h4 text-muted">About</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/perfume"><span class="h4 text-muted">Perfumes</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/brand"><span class="h4 text-muted">Brands</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/order"><span class="h4 text-muted">Orders</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/transaction"><span class="h4 text-muted">Transactions</span></a>
              </li>

            </ul>
          </div>

          @else
          <div class="collapse navbar-collapse " id="navbarNav">
            <ul class="navbar-nav nav-fill w-100">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/main"><span class="h4 text-muted">Home</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><span class="h4 text-muted">About</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/perfume"><span class="h4 text-muted">Fragrance</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/type"><span class="h4 text-muted">Types</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/season"><span class="h4 text-muted">Season</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/contact"><span class="h4 text-muted">Contact Us</span></a>
              </li>
            </ul>
          </div>
          @endif

          <div class="me-5">
            @if(Session::get('role') === '0')
            <a href="/cart" class="btn btn-outline-danger rounded-pill position-relative ">
              <i class="fa-solid fa-cart-shopping "></i>
              @if(count(CartController::countCart($id))>0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{count(CartController::countCart($id))}}
                <span class="visually-hidden">cart items</span>
              @endif
              </span>
            </a>
            @endif
            <div class="btn-group">
              <button type="button" class="btn btn-danger rounded-pill fs-5 fw-bold ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="mx-4">{{strtok(Session::get('name'), " ");}}  </span>
              </button>
              <ul class="dropdown-menu">
                @if(Session::get('role') === '1')
                <li><a class="dropdown-item" href="#">Manage Perfumes</a></li>
                <li><a class="dropdown-item" href="#">Manage Brands</a></li>
                <li><a class="dropdown-item" href="/order">Manage Orders</a></li>
                <li><a class="dropdown-item" href="#">Manage Transactions</a></li>
                @endif
                
                @if(Session::get('role') === '0')
                <li><a class="dropdown-item" href="/profile">My Profile</a></li>
                <li><a class="dropdown-item" href="/purchase">My Purchase</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Separated link</a></li>
                @endif
              </ul>
            </div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-secondary rounded-pill ms-2">
      
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

              <i class="fa-solid fa-right-from-bracket"></i>
            </a>
          </div>
        </div>
      </nav>
@endguest


