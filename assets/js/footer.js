// FOOTER laden
fetch("../src/components/footer.html")
    .then(res => res.text())
    .then(html => document.getElementById("footer").innerHTML = html);