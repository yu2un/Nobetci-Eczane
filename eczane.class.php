<?php
  header('Content-Type: text/html; charset=utf-8');

  /**
   *
   * Türkiye il ve ilçe nöbetçi eczaneler
   * Yalçın Uzun
   * 16.12.2017
   * 23:00
   *
   * İl'e göre nöbetçi eczaneler
   * İlçeye göre nobetçi eczaneler
   *
   */
    class Eczaneler
    {

        private $url = "http://www.11818.com.tr/Nobetci_eczaneler.aspx?";
        private $ilUrl = "CityId=";
        private $ilceUrl = "&TownId=";
        private $sayfaUrl = "&p=";


        private function curl($url)
        {
            $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            $icerik = curl_exec($ch);
            curl_close($ch);
            return $icerik;
        }

        private function ara($bas, $son, $yazi)
        {
            @preg_match_all('/' . preg_quote($bas, '/') .
            '(.*?)'. preg_quote($son, '/').'/si', $yazi, $m);
            return @$m[1];
        }

        private function sayfaSayisi($il, $ilce=null)
        {
            $url = $this->curl($this->url.$this->ilUrl.$il.$this->ilceUrl.$ilce);
            $sayfa = $this->ara('<div class="guncelpager">','</div>',$url);
            $sayfaSayisi = explode('<a',$sayfa[0]);
            $sayfaSayisi = count($sayfaSayisi); $sayfaSayisi = $sayfaSayisi - 2;
            return $sayfaSayisi;
        }

        private function bosluk($deger){
            $deger = str_replace("&nbsp;" , " " , $deger);
            $deger = trim($deger);
            return $deger;
        }

        private function replaceSpace($string)
        {
            $string = preg_replace("/\s+/", " ", $string);
            $string = trim($string);
            return $string;
        }

        private function eczaneGetir($il , $ilce=null , $sayfa=null)
        {
            $url = $this->url.$this->ilUrl.$il;

            if(!empty($ilce)){
                $url .= $this->ilceUrl.$ilce;
            }

            if(!empty($sayfa)){
                $a = 0;
                $url .= $this->sayfaUrl;
                for ($i=1; $i <= $sayfa; $i++) {
                $sayfaUrl = $this->curl($url.$i);

                $eczaneAd = $this->ara('<div class="resultheader">','</div>',$sayfaUrl);
                $eczaneDetay = $this->ara('<div class="resultdetail">','</div>',$sayfaUrl);

                $b = 0;
                    foreach ($eczaneAd as $eczane) {
                        $eczaneList[$a]["ad"]	= $this->bosluk($eczane);
                        $eczaneList[$a]["adres"] = $this->bosluk($eczaneDetay[$b]);$b++;
                        $eczaneList[$a]["tel"] = $this->bosluk($eczaneDetay[$b]);$b++;
                        $a++;
                    }
                }
            }else{
                $eczaneAd = $this->ara('<div class="resultheader">','</div>',$sayfaUrl);
                $eczaneDetay = $this->ara('<div class="resultdetail">','</div>',$sayfaUrl);
                $a = 0;
                $b = 0;
                foreach ($eczaneAd as $eczane) {
                    $eczaneList[$a]["ad"]	= $this->bosluk($eczane);
                    $eczaneList[$a]["adres"] = $this->bosluk($eczaneDetay[$b]);$b++;
                    $eczaneList[$a]["tel"] = $this->bosluk($eczaneDetay[$b]);$b++;
                    $a++;
                }
            }
            return $eczaneList;
        }

        public function listele($il , $ilce=null)
        {
            $sayfaSayisi = $this->sayfaSayisi($il , $ilce);
            return $this->eczaneGetir($il , $ilce , $sayfaSayisi);
        }

        public function ilce($il)
        {
            $url = $this->curl($this->url.$this->ilUrl.$il);
            $ilce = $this->ara('<select id="TownId" name="TownId"','</select>',$url);
            $ilce = $ilce[0];
            $ilce = ltrim($ilce,"&#65279; >");
            $ilceExp = $this->ara('<option value="' , '</option>' , $ilce);
            $ilceExp = str_replace('">','-',$ilceExp);

            foreach($ilceExp as $key => $value){
                $explode = explode("-" , $value);
                if(!$key == 0){
                    $ilceler[$key]["ilceID"] = $explode[0];
                    $ilceler[$key]["ilceAD"] = $explode[1];
                }
            }
            return $ilceler ;
        }

    }

    $eczane = new Eczaneler();
    $eczane->ilce("34"); // İstanbulda bulunan ilçe ID bilgileri.
    $eczane->listele("34"); // İstanbul'da mevcut olan nöbetçi eczaneler
    $eczane->listele("34" , "425"); // İstanbul(34) Ataşehir(425) ilçesinde mevcut olan nöbetçi eczaneler




?>
