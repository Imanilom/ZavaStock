<!-- resources/views/layouts/app.blade.php -->

@include('layouts.header')

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        @include('layouts.navbar')  <!-- Menyertakan navbar -->
        @include('layouts.sidebar')  <!-- Menyertakan sidebar -->
        
        <div class="container-fluid" style="margin-left: 0px; padding-top: 60px;">
            @yield('content')
        </div>
    </div>
</div>

@include('layouts.footer')

<script>
    // Toggle sidebar on mobile
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.createElement('button');
    toggleButton.innerText = '☰';
    toggleButton.className = 'btn btn-primary d-lg-none'; // Hanya tampil di layar kecil
    toggleButton.style.position = 'fixed';
    toggleButton.style.top = '10px';
    toggleButton.style.left = '10px';
    
    document.body.appendChild(toggleButton);
    
    toggleButton.addEventListener('click', () => {
        if (sidebar.style.display === 'none' || sidebar.style.display === '') {
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
        }
    });
</script>