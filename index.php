<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weidmüller CD Library</title>
</head>
<body>
<script>
function showCDCatalog() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            prepareCDCatalog(this);
        }
    };
    xmlHttp.open("GET", "result.xml" , true);
    xmlHttp.send();
}

function prepareCDCatalog(xml) {
    var xmlDoc = xml.responseXML;
    var table = "<tr><th>Artist</th><th>Title</th></tr>";
    var cdNodes = xmlDoc.getElementsByTagName("cd")
    for (var i = 0; i < cdNodes.length; i++) {
        table += "<tr><td>" +
            cdNodes[i].getElementsByTagName("artist")[0].childNodes[0].nodeValue +
            "</td><td>" +
            cdNodes[i].getElementsByTagName("title")[0].childNodes[0].nodeValue +
            "</td></tr>";
    }
    var cdTable = document.getElementById("cdcatalogtable");
    cdTable.innerHTML = table;
    cdTable.style.visibility = "visible";
}

function showCDForm() {
    var form = document.getElementById("cdform");
    form.style.visibility = "visible";
}

function hideCDForm() {
    var form = document.getElementById("cdform");
    form.style.visibility = "hidden";
}

function addTrack() {
    var form = document.getElementById("cdform");
    var input = document.createElement("input");
    input.type = "text";
    input.name = "Track[]";
    var br = document.createElement("br");
    form.appendChild(input);
    form.appendChild(br);
}
</script>
    <img src="logo.png" alt="Logo Icon" style="width:1220px;height:113px;">
    <h1 align="center" style="background-color: lightcyan">Weidmüller CD Library</h1>
    <div id="container" style="width:100%;">
        <div id="left" style="float:left; width:50%;">
            <button id="viewcdcatalog" style="background-color: darkorange; padding: 15px 32px; border-radius: 4px; font-size: 16px;" onclick="showCDCatalog();">View CD Catalog</button><br>
            <table id="cdcatalogtable" border="1" style="visibility: hidden; background-color: lightcyan">
            <tr><th>Artist</th><th>Title</th></tr>
            </table>
        </div>
        <div id="right" style="float:right; width:50%;">
            <button id="addcd" style="background-color: darkorange; padding: 15px 32px; border-radius: 4px; font-size: 16px;" onclick="showCDForm();">Add CD</button>
            <form id="cdform" action="" method="post" name="form" style="visibility: hidden">
                <input id="save" type="submit" name="save" value="Save" style="background-color: darkorange; padding: 12px 28px; border-radius: 4px; font-size: 16px;" onclick="hideCDForm()"><br>
                Artist:<br>
                <input id="artist" type="text" name="Artist" value=""><br>
                Title:<br>
                <input id="title" type="text" name="Title" value=""><br>
                Tracks:<br>
                <button id="addtrack" type="button" style="background-color: darkorange; padding: 12px 28px; border-radius: 4px; font-size: 16px;" onclick="addTrack()">Add Track</button><br>
            </form>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST['save'])) {
    $outfile="result.xml";
    $xml = new DOMDocument("1.0","UTF-8");
    $xml->load($outfile);

    $catalogTag = $xml->getElementsByTagName("cdcatalog")->item(0);

    $cdTag = $xml->createElement("cd");

    $artistTag = $xml->createElement("artist",$_POST['Artist']);
    $cdTag->appendChild($artistTag);

    $titleTag = $xml->createElement("title",$_POST['Title']);
    $cdTag->appendChild($titleTag);

    $tracksTag = $xml->createElement("tracks");
    $tracks = $_POST['Track'];
    foreach ($tracks as $value) {
        $trackTag = $xml->createElement("track",$value);
        $tracksTag->appendChild($trackTag);
    }
    $cdTag->appendChild($tracksTag);
    $catalogTag->appendChild($cdTag);

    $xml->save($outfile);
}
?>
