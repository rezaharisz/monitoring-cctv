<!-- Sidebar -->
@php
    $routename = request()->route()->getName();
@endphp
<div class="sidebar sidebar-style-2" data-background-color="{{ $sidebarColor }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-item ml-3 {{ $routename == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Master</h4>
                </li>
                <li class="nav-item ml-3 {{ $routename == 'building' ? 'active' : '' }}">
                    <a href="{{ route('building') }}">
                        <i class="fas fa-building"></i>
                        <p>Gedung</p>
                    </a>
                </li>
                <li class="nav-item ml-3 {{ $routename == 'floor' ? 'active' : '' }}">
                    <a href="{{ route('floor') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Lantai</p>
                    </a>
                </li>
                <li class="nav-item ml-3 {{ $routename == 'cctv' ? 'active' : '' }}">
                    <a href="{{ route('cctv') }}">
                        <i class="fas fa-video"></i>
                        <p>Cctv</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
