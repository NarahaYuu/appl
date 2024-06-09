<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keamanan Rumah</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .sticky-dropdown {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .image-group {
            margin-bottom: 40px;
        }
        .image-item {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .image-item img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .image-item p {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
            color: #555;
        }
        .logout-btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Gambar Tertangkap</h1>
    </div>
    <!-- Tombol Logout -->
    <form action="{{ route('logout') }}" method="POST" class="logout-btn">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>

    <!-- Sticky Dropdown untuk memilih tanggal -->
    <div class="form-group sticky-dropdown">
        <label for="dateDropdown">Pilih Tanggal:</label>
        <select id="dateDropdown" class="form-control">
            <option value="">-- Pilih Tanggal --</option>
            @foreach($images as $date => $imageGroup)
                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</option>
            @endforeach
        </select>
    </div>

    <!-- Kontainer untuk menampilkan gambar -->
    <div id="imageContainer">
        @foreach($images as $date => $imageGroup)
            <div class="image-group" data-date="{{ $date }}" style="display: none;">
                <h2>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h2>
                <div class="row">
                    @foreach($imageGroup as $image)
                        <div class="col-md-4">
                            <div class="image-item">
                                <img src="{{ asset('images/' . $image->name) }}" alt="Gambar" class="img-thumbnail">
                                <p>Pukul {{ \Carbon\Carbon::parse($image->created_at)->setTimezone('Asia/Jakarta')->format('H:i:s') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.getElementById('dateDropdown').addEventListener('change', function() {
        var selectedDate = this.value;
        var imageGroups = document.querySelectorAll('.image-group');

        imageGroups.forEach(function(group) {
            if (group.getAttribute('data-date') === selectedDate) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>

