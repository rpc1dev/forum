<!DOCTYPE html>
<html>
<head>
<title>Download Firmware</title>
</head>
<body>
  <h1>Download ID <span id="download">N/A</span> is not available.</h1>
  <script>
  var download_id = location.search.split('download_id=')[1];
  document.getElementById("download").innerHTML = download_id;
  </script>
</body>
</html>
