<?php
error_reporting(0);   
//$button = $_GET ['submit'];
$search = $_GET ['search']; 
  
if(strlen($search)<=1)
echo "Search term too short";
else{
echo "You searched for <b>$search</b> <hr size='1'></br>";
mysql_connect("localhost","root","polyu");
mysql_select_db("project");
    
$search_exploded = explode (" ", $search);
    
foreach($search_exploded as $search_each)
{
$x++;
if($x==1)
$construct .="(post_title LIKE '%$search_each%' OR post_content LIKE '%$search_each%')";
else
$construct .="AND (post_title LIKE '%$search_each%' OR post_content LIKE '%$search_each%')";
    
}
  
$constructs ="SELECT * FROM m_posts WHERE $construct";
$run = mysql_query($constructs);
    
$foundnum = mysql_num_rows($run);
    
if ($foundnum==0)
echo "Sorry, there are no matching result for <b>$search</b>.</br></br>1. 
Try more general words. for example: If you want to search 'how to create a website'
then use general keyword like 'create' 'website'</br>2. Try different words with similar
 meaning</br>3. Please check your spelling";
else
{ 
  
echo "$foundnum results found !<p>";
  
$per_page = 20;
$start = $_GET['start'];
$max_pages = ceil($foundnum / $per_page);
if(!$start)
$start=0; 
$getquery = mysql_query("SELECT * FROM m_posts WHERE $construct LIMIT $start, $per_page");
  
while($runrows = mysql_fetch_assoc($getquery))
{
$title = $runrows ['post_title'];
$desc = $runrows ['post_content'];
$url = "http://sharediscount.tk:25000/readPost.php?id=" . $runrows ['ID'];
   
echo "
<a target='_blank' href='".$url."'><b>".$title."</b></a><br>
".$desc."<br>
<a target='_blank' href='".$url."'>".$url."</a><br><br><p>
";
    
}
  
//Pagination Starts
echo "<center>";
  
$prev = $start - $per_page;
$next = $start + $per_page;
                       
$adjacents = 3;
$last = $max_pages - 1;
  
if($max_pages > 1)
{   
//previous button
if (!($start<=0)) 
echo " <a href='search.php?search=$search&submit=Search&start=$prev'>Prev</a> ";    
          
//pages 
if ($max_pages < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
{
$i = 0;   
for ($counter = 1; $counter <= $max_pages; $counter++)
{
if ($i == $start){
echo " <a href='search.php?search=$search&submit=Search&start=$i'><b>$counter</b></a> ";
}
else {
echo " <a href='search.php?search=$search&submit=Search&start=$i'>$counter</a> ";
}  
$i = $i + $per_page;                 
}
}
elseif($max_pages > 5 + ($adjacents * 2))    //enough pages to hide some
{
//close to beginning; only hide later pages
if(($start/$per_page) < 1 + ($adjacents * 2))        
{
$i = 0;
for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
{
if ($i == $start){
echo " <a href='search.php?search=$search&submit=Search&start=$i'><b>$counter</b></a> ";
}
else {
echo " <a href='search.php?search=$search&submit=Search&start=$i'>$counter</a> ";
} 
$i = $i + $per_page;                                       
}
                          
}
//in middle; hide some front and some back
elseif($max_pages - ($adjacents * 2) > ($start / $per_page) && ($start / $per_page) > ($adjacents * 2))
{
echo " <a href='search.php?search=$search&submit=Search&start=0'>1</a> ";
echo " <a href='search.php?search=$search&submit=Search&start=$per_page'>2</a> .... ";
 
$i = $start;                 
for ($counter = ($start/$per_page)+1; $counter < ($start / $per_page) + $adjacents + 2; $counter++)
{
if ($i == $start){
echo " <a href='search.php?search=$search&submit=Search&start=$i'><b>$counter</b></a> ";
}
else {
echo " <a href='search.php?search=$search&submit=Search&start=$i'>$counter</a> ";
}   
$i = $i + $per_page;                
}
                                  
}
//close to end; only hide early pages
else
{
echo " <a href='search.php?search=$search&submit=Search&start=0'>1</a> ";
echo " <a href='search.php?search=$search&submit=Search&start=$per_page'>2</a> .... ";
 
$i = $start;                
for ($counter = ($start / $per_page) + 1; $counter <= $max_pages; $counter++)
{
if ($i == $start){
echo " <a href='search.php?search=$search&submit=Search&start=$i'><b>$counter</b></a> ";
}
else {
echo " <a href='search.php?search=$search&submit=Search&start=$i'>$counter</a> ";   
} 
$i = $i + $per_page;              
}
}
}
          
//next button
if (!($start >=$foundnum-$per_page))
echo " <a href='search.php?search=$search&submit=Search&start=$next'>Next</a> ";    
}   
echo "</center>";
} 
} 
?>