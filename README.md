#Webservice Client for PDDIKTI Feeder



##Kebutuhan sistem:


* Aplikasi ini dikembangkan menggunakan PHP versi 5.6.3. Untuk versi dibawahnya belum pernah diujicobakan.

* Pastikan folder upload dalam keadaan bisa ditulis (writeable).

* Pastikan folder temps dalam keadaan bisa ditulis (writeable).

* Pastikan file setting.ini dalam keadaan bisa ditulis (writeable).

##Petunjuk Installasi:


* **Menggunakan webserver Sendiri:** *Extract file hasil download dan tempatkan di folder utama webserver (contoh htdocs, root, www, etc.)*

* **Menggunakan webserver Feeder:** *Extract file hasil download dan tempatkan di folder C:\Program Files\PDDIKTI\dataweb*


##Petunjuk Menjalankan:

* **Menggunakan Webserver Sendiri:** *Ketikkan alamat [http://localhost/nama_folder_hasil_extract](http://localhost/nama_folder_hasil_extract) dibrowser Anda*

* **Menggunakan webserver Feeder:** *Ketikkan alamat [http://localhost:nomor_port_feeder/nama_folder_hasil_extract](http://localhost:nomor_port_feeder/nama_folder_hasil_extract) dibrowser Anda*

* Setelah halaman login muncul, masukkan alamat **Alamat Webservice Feeder, Username dan Password Feeder** Anda*

* Alamat webservice feeder bisa ditemui dialamat [http://localhost:8082/ws/live.php?wsdl] (http://localhost:8082/ws/live.php?wsdl) atau bisa juga menggunakan sandbox yang alamatnya [http://localhost:8082/ws/sandbox.php?wsdl] (http://localhost:8082/ws/sandbox.php?wsdl)*