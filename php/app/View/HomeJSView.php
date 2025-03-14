<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Cari lowongan...">
  </div>
  
  <div class="filter-container">
    <label for="jenisPekerjaan">Jenis Pekerjaan:</label>
    <select id="jenisPekerjaan">
      <option value="">Semua</option>
      <option value="full-time">Full-time</option>
      <option value="part-time">Part-time</option>
      <option value="internship">Internship</option>
    </select>

    <label for="jenisLokasi">Jenis Lokasi:</label>
    <select id="jenisLokasi">
      <option value="">Semua</option>
      <option value="on-site">On-site</option>
      <option value="hybrid">Hybrid</option>
      <option value="remote">Remote</option>
    </select>
  </div>

  <div id="jobList" class="job-list">
  </div>

  <div id="pagination">
  </div>

  <script src="/public/js/home.js"></script>
</body>
</html>
