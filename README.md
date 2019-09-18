# Nobetci-Eczane
Türkiye il ve ilçe günlük nöbetçi eczane listesi

Not:Tüm Türkiyeyi %100 olarak göstermemekte.

## Kullanımı

``` $eczane = new Eczaneler();  ```

### İlçe Öğrenme; 

``` $eczane->ilce("34"); // İstanbulda bulunan ilçe ID bilgileri. ```

Çıktısı :

```
[1] => Array
    (
        [ilceID] => 423
        [ilceAD] => Adalar
    )

[2] => Array
    (
        [ilceID] => 424
        [ilceAD] => Arnavutköy
    )
```

### İl'e göre tüm nöbetçi eczaneler

``` $eczane->listele("34"); // İstanbul'da mevcut olan nöbetçi eczaneler ```

Çıktısı : 

```
[0] => Array
    (
        [ad] => Bülent Eczanesi
        [adres] => Burgazadası Mah. Yalı Cad. No:17
        [tel] => (216) 311 81 15
    )

[1] => Array
    (
        [ad] => Heybeliada Eczanesi
        [adres] => Heybeliada Mah. Ayyıldız Cad. No:28
        [tel] => (216) 351 84 15
    )
```

### İlçe'ye göre tüm nöbetçi eczaneler

``` $eczane->listele("34" , "425"); // İstanbul(34) Ataşehir(425) ilçesinde mevcut olan nöbetçi eczaneler ``` 

Çıktısı : 

```
[0] => Array
    (
        [ad] => Ece Eczanesi
        [adres] => Atatürk Mah. Girne Cad. No:42
        [tel] => (216) 455 49 21
    )

[1] => Array
    (
        [ad] => Evren Eczanesi
        [adres] => İçerenköy Mah. Mezarlık Sok. Evren Sitesi İçi C Blok  No:1-14/H
        [tel] => (216) 573 58 57
    )
```
