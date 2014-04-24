<?
//This script is useful for parsing the logs in real time, i.e parse only the data that gets appended, say every 60 secs.

$filename = "/var/log/apache2/access.log";

// Use shell command 'stat' instead of filesize() to handle files >2GB
$prevLength = trim(shell_exec("stat -c%s " . escapeshellarg($filename)));
$curLength = $prevLength;

while(true)
{
$flog = fopen($filename, "r");
fseek($flog, $prevLength); // Go to the end of the file

$curLength = trim(shell_exec("stat -c%s " . escapeshellarg($filename))); // Get the current length of file

//If file length has changed, means something got appended. Process it accordingly.
if($curLength > $prevLength)
{
        $contents = fread($flog, $curLength - $prevLength); // Read only the appended data
        print_r($contents);
        $prevLength = $curLength; // Update the prev length
}
sleep(60); //check again after 60 secs.
fclose($flog);
}
?>
