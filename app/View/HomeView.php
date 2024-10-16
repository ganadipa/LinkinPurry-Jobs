<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Name - Lowongan Kerja</title>
    <link rel="stylesheet" href="/public/css/style.css">  <!-- Link ke file CSS -->
</head>
<body>
    <nav class="navbar">
        <div class="logo">LinkinPurry</div>
        <div>WBD Corp.</div>
    </nav>
    
    <div class="container">
        <h1>Lowongan Kerja</h1>
        <button class="add-job">+ Tambah Lowongan Baru</button>
        
        <div class="search-sort-filter">
            <input type="text" placeholder="Cari lowongan...">
            <select>
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
            <select>
                <option value="all">Semua Status</option>
                <option value="open">Terbuka</option>
                <option value="closed">Tertutup</option>
            </select>
        </div>
        
        <div class="job-list">
            <div class="job-item">
                <span class="job-title">Software Engineer</span>
                <div class="job-actions">
                    <button>Edit</button>
                    <button>Hapus</button>
                    <button>Tutup</button>
                </div>
            </div>
            <div class="job-item">
                <span class="job-title">Product Manager</span>
                <div class="job-actions">
                    <button>Edit</button>
                    <button>Hapus</button>
                    <button>Tutup</button>
                </div>
            </div>
            <div class="job-item">
                <span class="job-title">Data Analyst</span>
                <div class="job-actions">
                    <button>Edit</button>
                    <button>Hapus</button>
                    <button>Buka</button>
                </div>
            </div>
        </div>
        
        <div class="pagination">
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
        </div>
    </div>

    <script src="/public/js/script.js"></script>  <!-- Link ke file JavaScript -->
</body>
</html>
