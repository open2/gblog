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
            echo "WHOIS ������ ������ �� �����ϴ�. ����� �ٽ�... <br>\n";
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

                    <hr><center><h3> <?=$oldquery?> �� ���� <?=$ser_name?>  WHOIS �˻���� �Դϴ�.  </h3></center>  
                    <?
                    for($j=0 ; $j<count($result) ; $j++)
                    { 
                        echo $result[$j].'<br>';
                    }
                    ?>
                    <div style='text-align:center'><input type=button value=�ݱ� onclick=self.close()></div>
                    <HR>

                </div>
            <?
        }  
    }
} 

include_once("{$g4[path]}/tail.sub.php");
?>