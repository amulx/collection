<?php 

function getpage($sql,$page_size=20) 
{ 
      global $page,$totalpage,$sums;  //out param 
      $page = $_GET["page"]; 
      //$eachpage = $page_size; 
      $pagesql = strstr($sql," from "); 
      $pagesql = "select count(*) as ids ".$pagesql; 
      $result = mysql_query($pagesql); 
      if($rs = mysql_fetch_array($result)) $sums = $rs[0]; 
      $totalpage = ceil($sums/$page_size); 
      if((!$page)($page<1)) $page=1; 
   $startpos = ($page-1)*$page_size; 
   $sql .=" limit $startpos,$page_size "; 
    return $sql; 
} 
function showbar($string="") 
{      
    global $page,$totalpage; 
$out="共<font ".$totalpage."color=@#red@#><b>".$totalpage."</b></font>页  "; 
    $linkNum =4; 
    $start = ($page-round($linkNum/2))>0 ? ($page-round($linkNum/2)) : "1"; 
    $end   = ($page+round($linkNum/2))<$totalpage ? ($page+round($linkNum/2)) : $totalpage; 
    $prestart=$start-1; //OsPHP.COM.CN 
    $nextend=$end+1; 
    if($page<>1)  
$out .= "<a href=@#?page=1&&".$string."@#title=第一页>第一页</a> "; 
    if($start>1) 
$out.="<a href=@#?page=".$prestart."@# title=上一页>..<<</a> "; 

for($t=$start;$t<=$end;$t++) 
    { 
     $out .= ($page==$t) ? "<font [".$t."]color=@#red@#><b>[".$t."]</b></font> " : "<a $thref=@#?page=$t&&".$string."@#>$t</a> "; 
    } 
if($end<$totalpage) 
$out.="<a href=@#?page=".$nextend."&&".$string."@# title=下一页>>>..</a>"; 
    if($page<>$totalpage) 
   $out .= " <a href=@#?page=".$totalpage."&&".$string."@# title=最后页>最后页</a>"; //PHP开源代码 
   return $out; 
}

?>