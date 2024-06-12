<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kullanici_veritabani";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT isim, soyisim, email, dogum_tarihi, cinsiyet FROM kullanicilar");
    $stmt->execute();

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kullanicilar = $stmt->fetchAll();

    echo "<h2>Kayıtlı Kullanıcılar</h2>";
    echo "<table border='1'>
        <tr>
            <th>İsim</th>
            <th>Soyisim</th>
            <th>E-posta</th>
            <th>Doğum Tarihi</th>
            <th>Cinsiyet</th>
        </tr>";
    foreach ($kullanicilar as $kullanici) {
        echo "<tr>";
        echo "<td>" . $kullanici['isim'] . "</td>";
        echo "<td>" . $kullanici['soyisim'] . "</td>";
        echo "<td>" . $kullanici['email'] . "</td>";
        echo "<td>" . $kullanici['dogum_tarihi'] . "</td>";
        echo "<td>" . $kullanici['cinsiyet'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

$conn = null;
?>
