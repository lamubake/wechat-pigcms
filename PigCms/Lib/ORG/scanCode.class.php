<?php
class scanCode {
const STARTA = 103;
const STARTB = 104;
const STARTC = 105;
const STOP = 106;
private $unit_width = 1; //单位宽度 缺省1个象素
private $is_set_height = false;
private $width = -1;
private $height = 35;
private $quiet_zone = 4; //默认边距
private $font_height = 15;
private $font_type = 4;
private $color =0x000000;
private $bgcolor =0xFFFFFF;
private $image = null;
private $codes = array("212222","222122","222221","121223","121322","131222","122213","122312","132212","221213","221312","231212","112232","122132","122231","113222","123122","123221","223211","221132","221231","213212","223112","312131","311222","321122","321221","312212","322112","322211","212123","212321","232121","111323","131123","131321","112313","132113","132311","211313","231113","231311","112133","112331","132131","113123","113321","133121","313121","211331","231131","213113","213311","213131","311123","311321","331121","312113","312311","332111","314111","221411","431111","111224","111422","121124","121421","141122","141221","112214","112412","122114","122411","142112","142211","241211","221114","413111","241112","134111","111242","121142","121241","114212","124112","124211","411212","421112","421211","212141","214121","412121","111143","111341","131141","114113","114311","411113","411311","113141","114131","311141","411131","211412","211214","211412","2331112");
private $valid_code = -1;
private $type ='B';
private $start_codes =array('A'=>self::STARTA,'B'=>self::STARTB,'C'=>self::STARTC);
private $code ='';
private $bin_code ='';
private $text ='';
private $showType ='bottom';//top bottom
public function __construct($code='',$text='',$type='B',$showType='')
{
  if (in_array($type,array('A','B','C')))
   $this->setType($type);
  else
   $this->setType('B');
  if ($code !=='')
   $this->setCode($code);
  if ($text !=='')
   $this->setText($text);
 if ($showType !=='')
   $this->setShowType($showType);
}
public function setUnitWidth($unit_width) 
{
  $this->unit_width = $unit_width;
  $this->quiet_zone = $this->unit_width*6;
  $this->font_height = $this->unit_width*15;
  if (!$this->is_set_height)
  {
   $this->height = $this->unit_width*35;
  }
}
public function setFontType($font_type)
{
  $this->font_type = $font_type;
}
public function setBgcolor($bgcoloe)
{
  $this->bgcolor = $bgcoloe;
}
public function setColor($color)
{
  $this->color = $color;
}
public function setCode($code)
{
  if ($code !='')
  {
   $this->code= $code;
  }
}
public function setText($text)
{
  $this->text = $text;
}

public function setType($type)
{
  $this->type = $type;
}
public function setShowType($showType)
{
  $this->showType = $showType;
}
public function setHeight($height)
{
  $this->height = $height;
  $this->is_set_height = true;
}

private function getValueFromChar($ch)
{
  $val = ord($ch);
  try
  {
   if ($this->type =='A')
   {
    if ($val > 95)
     throw new Exception(' illegal barcode character '.$ch.' for code128A in '.__FILE__.' on line '.__LINE__);
    if ($val < 32)
     $val += 64;
    else
     $val -=32;
   }
   elseif ($this->type =='B')
   {
    if ($val < 32 || $val > 127)
     throw new Exception(' illegal barcode character '.$ch.' for code128B in '.__FILE__.' on line '.__LINE__);
    else
     $val -=32;
   }
   else
   {
    if (!is_numeric($ch) || (int)$ch < 0 || (int)($ch) > 99)
     throw new Exception(' illegal barcode character '.$ch.' for code128C in '.__FILE__.' on line '.__LINE__);
    else
    {
     if (strlen($ch) ==1)
      $ch .='0';
     $val = (int)($ch);
    }
   }
  }
  catch(Exception $ex)
  {
   errorlog('die',$ex->getMessage());
  }
  return $val;
}
private function parseCode()
{
  $this->type=='C'?$step=2:$step=1;
  $val_sum = $this->start_codes[$this->type];
  $this->width = 35;
  $this->bin_code = $this->codes[$val_sum];
  for($i =0;$i<strlen($this->code);$i+=$step)
  {
   $this->width +=11;
   $ch = substr($this->code,$i,$step);
   $val = $this->getValueFromChar($ch);
   $val_sum += $val;
   $this->bin_code .= $this->codes[$val];
  }
  $this->width *=$this->unit_width;
  $val_sum = $val_sum%103;
  $this->valid_code = $val_sum;
  $this->bin_code .= $this->codes[$this->valid_code];
  $this->bin_code .= $this->codes[self::STOP];
}
public function getValidCode()
{
  if ($this->valid_code == -1)
   $this->parseCode();
  return $this->valid_code;
}
public function getWidth()
{
  if ($this->width ==-1)
   $this->parseCode();
  return $this->width;
}

public function getHeight()
{
  if ($this->width ==-1)
   $this->parseCode();
  return $this->height;
}
   
public function createBarCode($image_type ='png',$file_name=null)
{
  $this->parseCode();
  $this->image = ImageCreate($this->width+2*$this->quiet_zone,$this->height + $this->font_height + $this->quiet_zone); 
  $this->bgcolor = imagecolorallocate($this->image,$this->bgcolor >> 16,($this->bgcolor >> 8)&0x00FF,$this->bgcolor & 0xFF);
  $this->color = imagecolorallocate($this->image,$this->color >> 16,($this->color >> 8)&0x00FF,$this->color & 0xFF);
  ImageFilledRectangle($this->image, 0, 0, $this->width + 2*$this->quiet_zone,$this->height + $this->font_height, $this->bgcolor); 
  $sx = $this->quiet_zone;
  $sy = $this->font_height -1;

  $fw = 10; //編號為2或3的字體的寬度為10，為4或5的字體寬度為11
  if ($this->font_type >3)
  {
   $sy++;
   $fw=11;
  }
  $ex = 0;

  if($this->text){ //不显示号码，则生成满的条码
    if($this->showType == 'top'){
      $ey = $this->height + $this->font_height - 2; //条码显示上方
    }else if($this->showType == 'bottom'){
      $ey = $this->height - 2;  //条码显示下方
      $sy  = $this->quiet_zone;
    }
  }else{
    $ey = $this->height+$this->font_height;
    $sy  = $this->quiet_zone;
  }

  for($i=0;$i<strlen($this->bin_code);$i++)
  {
   $ex = $sx + $this->unit_width*(int) $this->bin_code{$i} -1;
   if ($i%2==0) 
    ImageFilledRectangle($this->image, $sx, $sy, $ex,$ey, $this->color); 
   $sx =$ex + 1;
  }

  if($this->text){ //存在条码号再显示
    $t_num = strlen($this->text);
    $t_x = $this->width/$t_num;
    $t_sx = ($t_x -$fw)/2;        //目的为了使文字居中平均分布

    if($this->showType == 'top'){
      $y  = 0;
    }else if($this->showType == 'bottom'){
      $y  = $this->height;
    }

    for($i=0;$i<$t_num;$i++)
    { 
     imagechar($this->image,$this->font_type,6*$this->unit_width +$t_sx +$i*$t_x,$y,$this->text{$i},$this->color);
    }
  }

  if (!$file_name)
  {
   header("Content-Type: image/".$image_type); 
  }  
  switch ($image_type)
  {
   case 'jpg':
   case 'jpeg':
    Imagejpeg($this->image,$file_name);
    break;
   case 'png':
    Imagepng($this->image,$file_name);
    break;
   case 'gif':
    break;
    Imagegif($this->image,$file_name);
   default:
    Imagepng($this->image,$file_name);
    break;
  }
}
}
?>