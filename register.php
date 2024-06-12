<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = $_POST['isim'];
    $soyisim = $_POST['soyisim'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $dogumTarihi = $_POST['dogumTarihi'];
    $cinsiyet = $_POST['cinsiyet'];

    // Sunucu tarafında validasyon
    if (empty($isim) || empty($soyisim) || empty($email) || empty($sifre) || empty($dogumTarihi) || empty($cinsiyet)) {
        die("Tüm alanlar doldurulmalıdır.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Geçerli bir e-posta adresi girin.");
    }

    if (strlen($sifre) < 6) {
        die("Şifre en az 6 karakter uzunluğunda olmalıdır.");
    }

    // Veritabanı bağlantısı
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kullanici_veritabani";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // E-posta benzersizlik kontrolü
        $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            die("Bu e-posta adresi zaten kullaniliyor.");
        }

        // Veritabanına veri ekleme
        $stmt = $conn->prepare("INSERT INTO kullanicilar (isim, soyisim, email, sifre, dogum_tarihi, cinsiyet) VALUES (:isim, :soyisim, :email, :sifre, :dogumTarihi, :cinsiyet)");
        $stmt->bindParam(':isim', $isim);
        $stmt->bindParam(':soyisim', $soyisim);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':sifre', password_hash($sifre, PASSWORD_DEFAULT));
        $stmt->bindParam(':dogumTarihi', $dogumTarihi);
        $stmt->bindParam(':cinsiyet', $cinsiyet);
        $stmt->execute();

        echo "Kayit başarili!";
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }

    $conn = null;
}
?>
