<?php
namespace App\Classes;

class NumberConvert
{
static public function ReadDecimal($amount,$lang,$currency_vi,$currency_en,$currency_2_vi,$currency_2_en) {
  $textnumber ='';
  if(NumberConvert::numberOfDecimals($amount)>0){
    $num = explode(".", $amount);
    if($lang == 'vi'){
    $textnumber = NumberConvert::Word($num[0],$lang).$currency_vi.' phẩy '.NumberConvert::Word($num[1],$lang).' '.$currency_2_vi;
    return ucfirst($textnumber);
    }else{
    $textnumber = NumberConvert::Word($num[0],$lang).$currency_en.' point '.NumberConvert::Word($num[1],$lang).' '.$currency_2_en;
    return ucfirst($textnumber);
    }
  }else{
    $textnumber = NumberConvert::Word($amount,$lang);
    if($lang == 'vi'){
    return ucfirst($textnumber.$currency_vi." chẵn");
    }else{
    return ucfirst($textnumber.$currency_en." only");
    }
  }
}
static public function Word($amount,$lang)
{
        $prefix = '';
         if($amount <=0)
        {
          $amount = abs($amount);
          if($lang == 'vi'){
            $prefix = "âm ";
          }else{
            $prefix = "negative ";
          }
        }

        $textnumber = "";
        $length = strlen($amount);

        for ($i = 0; $i < $length; $i++)
        $unread[$i] = 0;

        for ($i = 0; $i < $length; $i++)
        {
            $so = substr($amount, $length - $i -1 , 1);

            if ( ($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)){
                for ($j = $i+1 ; $j < $length ; $j ++)
                {
                    $so1 = substr($amount,$length - $j -1, 1);
                    if ($so1 != 0)
                        break;
                }

                if (intval(($j - $i )/3) > 0){
                    for ($k = $i ; $k <intval(($j-$i)/3)*3 + $i; $k++)
                        $unread[$k] =1;
                }
            }
        }

        if($lang == 'vi'){
          $Text=array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
          $TextLuythua =array("","nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");

                  for ($i = 0; $i < $length; $i++)
                  {
                      $so = substr($amount,$length - $i -1, 1);
                      if ($unread[$i] ==1)
                      continue;

                      if ( ($i% 3 == 0) && ($i > 0))
                      $textnumber = $TextLuythua[$i/3] ." ". $textnumber;

                      if ($i % 3 == 2 )
                      $textnumber = 'trăm ' . $textnumber;

                      if ($i % 3 == 1)
                      $textnumber = 'mươi ' . $textnumber;


                      $textnumber = $Text[$so] ." ". $textnumber;
                  }
                  $str_replace = [
                    array('key'=>"không mươi",'value'=>"lẻ"),
                    array('key'=>"lẻ không",'value'=>""),
                    array('key'=>"mươi không",'value'=>"mươi"),
                    array('key'=>"một mươi",'value'=>"mười"),
                    array('key'=>"mươi năm",'value'=>"mươi lăm"),
                    array('key'=>"mươi một",'value'=>"mươi mốt"),
                    array('key'=>"mười năm",'value'=>"mười lăm"),
                     ];
                    foreach($str_replace as $sr){
                      $textnumber = str_replace($sr['key'],$sr['value'],$textnumber);
                    }
                    return $prefix.$textnumber;

        }else{
          $Text=array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
          $TextLuythua =array("","thousand", "million", "billion", "trillion" ,"quadrillion");
          for ($i = 0; $i < $length; $i++)
          {
              $so = substr($amount,$length - $i -1, 1);
              if ($unread[$i] ==1)
              continue;

              if ( ($i% 3 == 0) && ($i > 0))
              $textnumber = $TextLuythua[$i/3].' '.$textnumber;

              if ($i % 3 == 2 )
              $textnumber = ' hundred'.' '.$textnumber ;

              if ($i % 3 == 1)
              $textnumber = 'con '.$textnumber ;

              $textnumber = $Text[$so] ." ". $textnumber;

          }
          $str_replace = [
            array('key'=>"one con",'value'=>"ten"),
            array('key'=>"ten one",'value'=>"eleven"),
            array('key'=>"ten two",'value'=>"twelve"),
            array('key'=>"ten three",'value'=>"thirteen"),
            array('key'=>"ten four",'value'=>"fourteen"),
            array('key'=>"ten five",'value'=>"fifteen"),
            array('key'=>"ten six",'value'=>"sixteen"),
            array('key'=>"ten seven",'value'=>"seventeen"),
            array('key'=>"ten eight",'value'=>"eighteen"),
            array('key'=>"ten nine",'value'=>"nineteen"),
            array('key'=>"two con zero",'value'=>"twenty"),
            array('key'=>"two con ",'value'=>"twenty-"),
            array('key'=>"three con zero",'value'=>"thirty"),
            array('key'=>"three con ",'value'=>"thirty-"),
            array('key'=>"four con zero",'value'=>"forty"),
            array('key'=>"four con ",'value'=>"forty-"),
            array('key'=>"five con zero",'value'=>"fifty"),
            array('key'=>"five con ",'value'=>"fifty-"),
            array('key'=>"six con zero",'value'=>"sixty"),
            array('key'=>"six con ",'value'=>"sixty-"),
            array('key'=>"seven con zero",'value'=>"seventy"),
            array('key'=>"seven con ",'value'=>"seventy-"),
            array('key'=>"eight con zero",'value'=>"eighty"),
            array('key'=>"eight con ",'value'=>"eighty-"),
            array('key'=>"nine con zero",'value'=>"ninety"),
            array('key'=>"nine con ",'value'=>"ninety-"),
             ];
            foreach($str_replace as $sr){
              $textnumber = str_replace($sr['key'],$sr['value'],$textnumber);
            }
            return $prefix.$textnumber;

        }


    }

    static private function numberOfDecimals($value)
{
    if ((int)$value == $value)
    {
        return 0;
    }
    else if (! is_numeric($value))
    {
        // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
        return false;
    }

    return strlen($value) - strrpos($value, '.') - 1;
}

}
