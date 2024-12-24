<?php
namespace Ipol\Fivepost\Fivepost\Handler;

class BarcodeGeneratorEAN
{
    public $kod;
    public $isp;
    public $number;
    public $width;
    public $height;
    public $font = 1;

    public function __construct($number){
        $this->setDefs();
        $this->number = $number;
        $this->getNum();
    }

    function setDefs(){
        $this->kod = array(
            "0" => array("a"=>"0001101","b"=>"0100111","c"=>"1110010"),
            "1" => array("a"=>"0011001","b"=>"0110011","c"=>"1100110"),
            "2" => array("a"=>"0010011","b"=>"0011011","c"=>"1101100"),
            "3" => array("a"=>"0111101","b"=>"0100001","c"=>"1000010"),
            "4" => array("a"=>"0100011","b"=>"0011101","c"=>"1011100"),
            "5" => array("a"=>"0110001","b"=>"0111001","c"=>"1001110"),
            "6" => array("a"=>"0101111","b"=>"0000101","c"=>"1010000"),
            "7" => array("a"=>"0111011","b"=>"0010001","c"=>"1000100"),
            "8" => array("a"=>"0110111","b"=>"0001001","c"=>"1001000"),
            "9" => array("a"=>"0001011","b"=>"0010111","c"=>"1110100")
        );
        $this->isp = array(
            "0" => array("2"=>"a","3"=>"a","4"=>"a","5"=>"a","6"=>"a","7"=>"a"),
            "1" => array("2"=>"a","3"=>"a","4"=>"b","5"=>"a","6"=>"b","7"=>"b"),
            "2" => array("2"=>"a","3"=>"a","4"=>"b","5"=>"b","6"=>"a","7"=>"b"),
            "3" => array("2"=>"a","3"=>"a","4"=>"b","5"=>"b","6"=>"b","7"=>"a"),
            "4" => array("2"=>"a","3"=>"b","4"=>"a","5"=>"a","6"=>"b","7"=>"b"),
            "5" => array("2"=>"a","3"=>"b","4"=>"b","5"=>"a","6"=>"a","7"=>"b"),
            "6" => array("2"=>"a","3"=>"b","4"=>"b","5"=>"b","6"=>"a","7"=>"a"),
            "7" => array("2"=>"a","3"=>"b","4"=>"a","5"=>"b","6"=>"a","7"=>"b"),
            "8" => array("2"=>"a","3"=>"b","4"=>"a","5"=>"b","6"=>"b","7"=>"a"),
            "9" => array("2"=>"a","3"=>"b","4"=>"b","5"=>"a","6"=>"b","7"=>"a")
        );
        $this->width = 102;
        $this->height = 15;
    }

    function getNum(){
        $koef = 2;

        $first=substr($this->number,0,1);

        $im=imagecreate($this->width,$this->height);
        $p=imagecolorallocate($im,255,255,255);
        $s=imagecolorallocate($im,0,0,0);
        imagefill($im,0,0,$p);
        $isp_="";
        for ($j=2;$j<8;$j++) $isp_.=$this->isp[$first][$j];

        imagefilledrectangle($im,3,0,3,$this->height,$s);
        imagefilledrectangle($im,5,0,5,$this->height,$s);
        for($i=1;$i<strlen($this->number)-6;$i++){
            $curr=substr($this->number,$i,1);
            $is=substr($isp_,$i-1,1);
            $curr_code=$this->kod["$curr"]["$is"];
            $nach=6+7*($i-1);
            for($j=1;$j<8;$j++)
                if(substr($curr_code,$j-1,1)=="1")
                    imagefilledrectangle($im,$nach+($j-1),0,$nach+($j-1),$this->height,$s);
            //imagestring($im,$this->font,$nach+1,$this->height-11,$curr,$s);
        };
        imagefilledrectangle($im,49,0,49,$this->height,$s);
        imagefilledrectangle($im,51,0,51,$this->height,$s);
        for($i=7;$i<strlen($this->number);$i++){
            $curr=substr($this->number,$i,1);
            $curr_code=$this->kod["$curr"]["c"];
            $nach=11+7*($i-1);
            for($j=1;$j<8;$j++)
                if(substr($curr_code,$j-1,1)=="1")
                    imagefilledrectangle($im,$nach+($j-1),0,$nach+($j-1),$this->height,$s);
            //imagestring($im,$this->font,$nach+1,$this->height-11,$curr,$s);
        };
        imagefilledrectangle($im,95,0,95,$this->height,$s);
        imagefilledrectangle($im,97,0,97,$this->height,$s);
        //imagestring($im,$this->font,0,$this->height-11,$first,$s);

        // Выводим полученный код:
        header ('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
}