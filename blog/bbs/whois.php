<?php
include_once("./_common.php");
include_once("{$g4[path]}/head.sub.php");


$query = $_GET['query'];

$mode = 'KLOOKUP';

if($query!="")
{   
    if($mode== "KLOOKUP")
        {$server="whois.nic.or.kr"; $ser_name="KRNIC";}         
    if($mode== "LOOKUP")
        {$server="whois.networksolutions.com"; $ser_name="INTERNIC";}         

    if($query)
    {   
        $fp = @fsockopen($server,43);
        if(!$fp) {
            echo "WHOIS 서버에 접속할 수 없습니다. 잠시후 다시... <br>\n";
        } else {
            if(!ereg($query, "\n$")) {
                $oldquery=$query;
                $query .= "\n";
            }
            fputs($fp,"$query");
            $i=0;
            while(!feof($fp)) {
                $result[$i]=fgets($fp,80);
                $i++; 
            }
            fclose($fp);
            ?>
                <div style='padding:10px'>

                    <hr><center><h3> <?=$oldquery?> 에 대한 <?=$ser_name?>  WHOIS 검색결과 입니다.  </h3></center>  
                    <?
                    for($j=0 ; $j<count($result) ; $j++)
                    { 
                        echo $result[$j].'<br>';
                    }
                    ?>
                    <div style='text-align:center'><input type=button value=닫기 onclick=self.close()></div>
                    <HR>

                </div>
            <?
        }  
    }
} 

include_once("{$g4[path]}/tail.sub.php");
?>