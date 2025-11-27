// FOOTER laden
fetch("../src/components/footer.php")
    .then(res => res.text())
    .then(html => document.getElementById("footer").innerHTML = html);