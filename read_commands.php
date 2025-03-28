<?php

require_once "network.php";

// Simulated file system
session_start();

$fileSystem = [
    "/" => [
        "Documents" => [
            "metadata" => 	[
                 "0",
                "permissions" => "drwxr-xr-x", // Directory permissions
                "owner" => "user",
                "group" => "group",
                "created" => "2025-02-01 10:00:00",
                "modified" => "2025-02-01 10:05:00"
	    ],
	    "LoverIsADay.txt" => "
Time changed, we're different
But my mind still says redundant things
Can I not think?
Will you love this part of me?
My lover is a day I can't forget
Furthering my distance from you
Realistically I can't leave now
But I'm okay as long as you
Keep me from going crazy
Keep me from going crazy
Straight up ahead you'll find a sign
That says you can't get by with a lie
But if I stayed away by a thread from the glory path
And made my life harder, lying 'bout the stupid shit I say
Then you wouldn't know a single thing about
How I feel about you
And those really dumb things people feel
I'll take the bumpy road, it'll probably break my legs
As long as I don't show you what's ruining my head
Funny thing about you is you read me pretty well
But you haven't found me yet at the bottom of the well
Annoying you with smoke signals, asking you for help
'Cause your immediate presence lifts me straight away from hell
Me and Mr. Heart, we say the cutest things about you
How you seem unreal and we'd probably die so quick without you
Suffocated from the radiated air around us
Full of happiness we don't have
Brightness gone, so dark without you, girl
Time changed, we're different
But my mind still says redundant things
Can I not think?
Will you love this part of me?
My lover is a day I can't forget
Furthering my distance from you
Realistically I can't leave now
But I'm okay as long as you
Keep me from going crazy
Keep me from going crazy
Can I not think?
Will you love this part of me?
My lover is a day I can't forget
Furthering my distance from you
Realistically I can't leave now
But I'm okay as long as you
Keep me from going crazy
Keep me from going crazy",

	 "BirchTree.txt" => "
I could be my best if I spoke my own head for you
You could see me now if you told yourself how you knew me
Oh, are you not lonely?
And oh, as you sit by the birch tree
Come to the tree, bring a birthday card for you
Seem a bit shocked, but crack a brief smile
I notice
Oh, you're not lonely
And oh, as we sit by the birch tree
As she goes in again
I look at my own head
Back from the blue, I know it's nothing new
I know we're pretty young but I see what people grow into
'Cause two years ahead I can see that you might not know me
Oh, I could be lonely
And oh, as I sit by the bitch tree",
	    
	 "WithoutYou.txt" => "
Do you really have to talk
About the things you do with him?
DO you really have to talk about it love?
do you really have to talk
About the way that you love him?
Do you really have to talk about your love?
Nah-nah-nah-nah...
Living my life without you
Nah-nah-nah-nah...
Living my life without you
Did you really have to do
Those things you knew that could hurt me?
Did you really have to do those things to me?
But I know that I can't be
The one you love that's in your life
But I know that I can't be the one you love",

            "hello.txt" => "
		     __ 
                    / _) .. ROAR!!!
           _.----._/ /
        __/         /
     __/  (  |  (  |
    /__.-'|_|--|__|
    ",
            "metadata" => [
                "1",
                "permissions" => "-rw-r--r--", // File permissions
                "owner" => "user",
                "group" => "group",
                "created" => "2025-02-01 10:00:00",
                "modified" => "2025-02-01 10:10:00",
                "size" => 1234 // file size in bytes
                ],
            "Subfolder" => [
                "metadata" => [
                    "2",
                    "permissions" => "drwxr-xr-x",
                    "owner" => "user1",
                    "group" => "group1",
                    "created" => "2025-02-01 10:05:00",
                    "modified" => "2025-02-01 10:07:00"
                ],
                "something.txt" => "
                    Hello World!",
              ],
        ],
        "Pictures" => [
            "metadata" => [
                "4",
                "permissions" => "drwxr-xr-x",
                "owner" => "user2",
                "group" => "group2",
                "created" => "2025-02-01 10:10:00",
                "modified" => "2025-02-01 10:12:00"
            ],
            "photo.jpg" => [
                "metadata" => [
                    "5",
                    "permissions" => "-rw-r--r--",
                    "owner" => "user2",
                    "group" => "group2",
                    "created" => "2025-02-01 10:10:00",
                    "modified" => "2025-02-01 10:11:00",
                    "size" => 789
                ]
            ]
        ],
        "Videos" => [
            "metadata" => [
                "6",
                "permissions" => "drwxr-xr-x",
                "owner" => "user3",
                "group" => "group3",
                "created" => "2025-02-01 10:15:00",
                "modified" => "2025-02-01 10:16:00"
            ],
        ],
        "Projects" => [
            "metadata" => [
                "7",
                "permissions" => "drwxr-xr-x",
                "owner" => "user1",
                "group" => "group1",
                "created" => "2025-02-01 10:20:00",
                "modified" => "2025-02-01 10:21:00"
            ],
            "project1" => [
                "metadata" => [
                    "8",
                    "permissions" => "drwxr-xr-x",
                    "owner" => "user1",
                    "group" => "group1",
                    "created" => "2025-02-01 10:20:00",
                    "modified" => "2025-02-01 10:21:00"
                ],
                "file2.txt" => [
                    "metadata" => [
                        "9",
                        "permissions" => "-rw-r--r--",
                        "owner" => "user1",
                        "group" => "group1",
                        "created" => "2025-02-01 10:20:00",
                        "modified" => "2025-02-01 10:21:00",
                        "size" => 1012
                    ]
                ]
            ]
        ]
    ]
];


$currentDirectory = "/";

if (!isset($_SESSION['fileSystem'])) {
    $_SESSION['fileSystem'] = $fileSystem;
}

if (!isset($_SESSION['currentDirectory'])) {
    $_SESSION['currentDirectory'] = "/";
}

function process_ls($fileSystem, $currentDirectory): string {
    if ($currentDirectory === "/") {
        $currentLevel = $fileSystem["/"];
        return format_directory_contents($currentLevel);
    }
    $currentDirectory = rtrim($currentDirectory, "/");
    $path = array_filter(explode("/", $currentDirectory), 'strlen');
    $currentLevel = $fileSystem["/"];
    foreach ($path as $part) {
        if (!isset($currentLevel[$part])) {
             return "Directory not found.\n";
        }
        if (!is_array($currentLevel[$part])) {
            return "Not a directory.\n";
        }
        $currentLevel = $currentLevel[$part]; 
    }
    return format_directory_contents($currentLevel);
}
function process_ls_l($fileSystem, $currentDirectory) : string { 
       // Handle root directory special case
    if ($currentDirectory === "/") {
        $currentLevel = $fileSystem["/"];
        return format_directory_contents($currentLevel);
    }
    // Remove trailing slash if present
    $currentDirectory = rtrim($currentDirectory, "/");
    
    // Split path into components
    $path = array_filter(explode("/", $currentDirectory), 'strlen');
    // Start from root
    $currentLevel = $fileSystem["/"];
    // Traverse the path
    foreach ($path as $part) {
        if (!isset($currentLevel[$part])) {
             return "Directory not found.\n";
        }
        //if (!is_array($currentLevel[$part])) {
          //  return "Not a directory.\n";
       // }
      $currentLevel = $currentLevel[$part]; 
    }
        // Prepare output to hold directory contents
    $output = "";
    foreach ($currentLevel as $name => $content) {
       if ($name === "metadata") continue; //ignore the header 
        // Build the ls -l format
        $line = "";
        
        // Check for metadata for files and directories
        if (isset($content['metadata'])) {
        // If it's a directory (or any array with metadata), display the metadata
        $metadata = $content['metadata'];
        $line .= $metadata['permissions'] . " ";
        $line .= $metadata['owner'] . " ";
        $line .= $metadata['group'] . " ";
        $line .= $metadata['created'] . " ";
        $line .= $metadata['modified'] . " ";
        $line .= $name . "\n";
        } elseif (is_string($content)) {
        // If it's a file (content is a string), display default file metadata
        $line .= "-rw-r--r-- "; // Default permissions for files
        $line .= "user "; // Default owner
        $line .= "group "; // Default group
        $line .= "2025-02-01 10:00:00 "; // Default created timestamp
        $line .= "2025-02-01 10:10:00 "; // Default modified timestamp
        $line .= $name . "\n";
}
        // Append the line to the output
        $output .= $line . "\n";
    }

    return $output;
}


function format_directory_contents(array $contents): string {
    if (empty($contents)) {
        return "This directory is empty.\n";
    }
    $output = [];
    foreach ($contents as $name => $content) {
        if (is_array($content)) {
            if ($name === "metadata") continue;
            $output[] = $name . "/";
        } else {
            $output[] = $name;
        }
    }
    sort($output);
    return implode(" ", $output) . "\n";
}

function process_cd(&$currentDirectory, $fileSystem, $dir): string  {
    if ($dir === "..") {
        if ($currentDirectory !== "/") {
            $pathParts = explode("/", trim($currentDirectory, "/"));
            array_pop($pathParts); 
            $currentDirectory = "/" . implode("/", $pathParts);
            if ($currentDirectory === "") {
                $currentDirectory = "/";
            }
        }
        return "";
    }  
        
    $targetPath = trim($dir, "/");
    $pathParts = explode("/", $targetPath);

    // Handle the chaining of cd, ex: cd Documents/Subfolder/temp
     if (count($pathParts) > 1) {
        // Start from the root if it's an absolute path, otherwise start from the current directory
        $newDirectory = (substr($dir, 0, 1) === "/") ? "/" : rtrim($currentDirectory, "/");
        // Split current directory into parts
        $path = array_filter(explode("/", trim($newDirectory, "/")), 'strlen');
        // Start from the root of the file system
        $currentLevel = $fileSystem["/"]; //basically initialzing to zero
        // Traverse the file system to the current directory
        foreach ($path as $part) {
            if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
                return "Error: Invalid directory path.\n";
            }
            $currentLevel = &$currentLevel[$part];
        }
        
        //for every directory in the path
        foreach ($pathParts as $part) {
                // Check if the directory exists
                if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
                    return "Error: Directory '$part' not found.\n";
                }
                $currentLevel = &$currentLevel[$part];
                $newDirectory = rtrim($newDirectory, "/") . "/" . $part;
            }
        // Update the current directory
        $currentDirectory = $newDirectory;    
    }
    else {    
        $path = array_filter(explode("/", trim($currentDirectory, "/")), 'strlen');
        // Start from the root of the file system
        $currentLevel = $fileSystem["/"];
        // Traverse the file system to the current directory
        foreach ($path as $part) {
            if (!isset($currentLevel[$part]) || $part === 'metadata') {
                return "Error: Directory not found.\n";
            }
                $currentLevel = $currentLevel[$part];
        }
        // Check if the target directory exists
        if ($dir === "metadata" || !isset($currentLevel[$dir]) || !is_array($currentLevel[$dir])) {
            return "Error: Directory not found.\n";
        }
        // Update the current directory path
        $currentDirectory = rtrim($currentDirectory, "/") . "/" . $dir;
    }
    return "";
}

function process_pwd($currentDirectory) : string {
    return $currentDirectory . "\n";
}
function process_mkdir(&$fileSystem, $currentDirectory, $newdir): string {
    // Traverse to the current directory in the file system
    $path = array_filter(explode("/", trim($currentDirectory, "/")), 'strlen');
    $currentLevel = &$fileSystem["/"]; // Start from root

    // Traverse the path to reach the current directory
    foreach ($path as $part) {
        $currentLevel = &$currentLevel[$part];
    // Check if the directory already exists
    if (isset($currentLevel[$newdir])) {
        return "Directory already exists: $newdir\n";
        }
    }
    // Create the new directory
    $currentLevel[$newdir] = [];
    return $newdir . "\n";
}
function process_cat(&$filesystem, $currentDirectory, $file): string {
    $currentDirectory = rtrim($currentDirectory, "/");
    $pathParts = array_filter(explode("/", $currentDirectory), 'strlen');
    $currentLevel = $filesystem["/"];
    foreach ($pathParts as $part) {
        if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
            return "Error: Invalid directory path.\n";
        }
        $currentLevel = $currentLevel[$part];
    }
    if (!isset($currentLevel[$file])) {
        return "Error: File not found.\n";
    }
    if (is_array($currentLevel[$file])) {
        return "Error: '$file' is a directory.\n";
    }
    return ($currentLevel[$file] ?? "[Empty File]") . "\n";
}
function process_mv(&$filesystem, $currentDirectory, $oldname, $newname): string {
    // Helper to resolve absolute paths
    $resolvePath = function ($baseDir, $path) {
        $isAbsolute = (substr($path, 0, 1) === '/');
        $parts = $isAbsolute ? [] : explode('/', trim($baseDir, '/'));
        foreach (explode('/', $path) as $part) {
            if ($part === '..') {
                if (!empty($parts)) array_pop($parts);
            } elseif ($part !== '.' && $part !== '') {
                $parts[] = $part;
            }
        }
        return '/' . implode('/', $parts);
    };

    // Resolve full source and target paths
    $sourcePath = $resolvePath($currentDirectory, $oldname);
    $targetPath = $resolvePath($currentDirectory, $newname);

    // Navigate to source directory
    $sourceDir = dirname($sourcePath);
    $sourceName = basename($sourcePath);
    $sourceParts = array_filter(explode('/', trim($sourceDir, '/')), 'strlen');
    $sourceLevel = &$filesystem['/'];
    foreach ($sourceParts as $part) {
        if (!array_key_exists($part, $sourceLevel) || !is_array($sourceLevel[$part])) {
            return "Error: Source path invalid.";
        }
        $sourceLevel = &$sourceLevel[$part];
    }

    // Check if source exists
    if (!array_key_exists($sourceName, $sourceLevel)) {
        return "Error: '$oldname' not found.";
    }

    // Navigate to target directory
    $targetDir = dirname($targetPath);
    $targetName = basename($targetPath);
    $targetParts = array_filter(explode('/', trim($targetDir, '/')), 'strlen');
    $targetLevel = &$filesystem['/'];
    foreach ($targetParts as $part) {
        if (!array_key_exists($part, $targetLevel) || !is_array($targetLevel[$part])) {
            return "Error: Target directory does not exist.";
        }
        $targetLevel = &$targetLevel[$part];
    }

    // If target is a directory, append source name (e.g., mv file.txt dir/)
    if (array_key_exists($targetName, $targetLevel) && is_array($targetLevel[$targetName])) {
        $targetLevel = &$targetLevel[$targetName];
        $targetName = $sourceName;
    }

    // Check for conflicts
    if (array_key_exists($targetName, $targetLevel)) {
        return "Error: '$targetName' already exists."; 
    }

    // Perform the move/rename
    $targetLevel[$targetName] = $sourceLevel[$sourceName];
    unset($sourceLevel[$sourceName]);

    return "Successfully moved '$oldname' to '$newname'.";
}

function process_chmod($fileSystem, $currentDirectory, $argument, $targetFile) : string {
    $output = process_ls_l($fileSystem, $currentDirectory);
    $lines = explode("\n", trim($output)); // Split output into lines
    $found = false;
    foreach ($lines as &$line) { // Use reference to modify $line directly
        $words = preg_split('/\s+/', $line); // Split each line into words
        if (empty($words)) continue;
	
	// Check if this is the target file
        if (end($words) === $targetFile) {
	    $found = true;	
	    if ($argument === "u+x" || $argument === "+100") { // add execute permissions to the user
                $words[0] = substr_replace($words[0], 'x', 3, 1); // Modify execute bit for user
	    }
	    if ($argument === "g-w" || $argument === "-020") //remove write permissions from the group
                $words[0] = substr_replace($words[0], '-', 5, 1); // Modify execute bit for user
	    }
	    if ($argument === "o=r" || $argument === "=004") { // set only to read for others
                $words[0] = substr_replace($words[0], '-', 8, 1); // Modify execute bit for user
                $words[0] = substr_replace($words[0], '-', 9, 1); // Modify execute bit for user
	    }
	    if ($argument === "a+x" || $argument ===  "+111") { // set execution to all
                $words[0] = substr_replace($words[0], 'x', 3, 1); // Modify execute bit for user
                $words[0] = substr_replace($words[0], 'x', 6, 1); // Modify execute bit for user
                $words[0] = substr_replace($words[0], '-', 9, 1); // Modify execute bit for user
	    } 
	    if ($argument === "u=rw" || $argument === "=600") { // set only to read and write for user
                $words[0] = substr_replace($words[0], 'x', 1, 1); // Modify execute bit for user
                $words[0] = substr_replace($words[0], 'x', 2, 1); // Modify execute bit for user
                $words[0] = substr_replace($words[0], '-', 3, 1); // Modify execute bit for user
	    }
	    if ($argument === "go-rwx" || $argument === "-077") { //remove permissions for group and others
		    for ($i = 4; $i < 10; $i++) {
                	$words[0] = substr_replace($words[0], '-', $i, 1);
		   }
	       }
        $line = implode(" ", $words); // Reconstruct modified line
	}
    if (!$found) return "Error: Invalid Input\n";
    else return implode("\n", $lines) . "\n"; // Return the updated output otherwise return error
}


function retrieve_files_from_directory($fileSystem, $currentDirectory) : array {
	$files = []; // will hold all the files in current directory, the key will hold the file name and value will be its content
	 $currentDirectory = rtrim($currentDirectory, "/");
    	$pathParts = array_filter(explode("/", $currentDirectory), 'strlen');
    	$currentLevel = $fileSystem["/"];
	foreach ($pathParts as $part) {
        if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
            return [];
        	}
        $currentLevel = $currentLevel[$part];
	}
	//for every thing in the current directory
	foreach($currentLevel as $name => $content) {
	//if the content is a directory, skip it
	if (!is_array($content)) {
		$files[$name] = $content;
		}
	}
	return $files;
}



function process_grep($fileSystem, $currentDirectory, $flag, $pattern, $file) : string {
    $currentDirectory = rtrim($currentDirectory, "/");
    $pathParts = array_filter(explode("/", $currentDirectory), 'strlen');
    $currentLevel = $fileSystem["/"];
     $line_numbers = 0;
     $count = 0;
    // Navigate to the current directory
    foreach ($pathParts as $part) {
        if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
            return "Error: Invalid directory path.\n";
        }
        $currentLevel = $currentLevel[$part];
    }

    $results = [];

    // Look for the pattern in every txt file in the current directory 
    if ($file === "*.txt") {
        $found = false;
        $files = retrieve_files_from_directory($fileSystem, $currentDirectory); // Check if .txt files exist  
        foreach($files as $name => $content) {
                $lines = explode("\n", trim($content));
                
                foreach ($lines as $line) {
                    $line_numbers ++;
                    // Handle different flags
                    if ($flag === "-n") {
                        if (strpos($line, $pattern) !== false) {
                            $found = true;
                            $results[] = $line_numbers . ": " . $line; // Add the line number to line
                        }
                    }
                    if ($flag === "-c") {
                        $found = true;
                        if (strpos($line, $pattern) !== false) {
                            $count++;
                        }
                    }
                    if ($flag === "-i") {
                        $found = true;
                        if (stripos($line, $pattern) !== false) {
                            $results[] = $line; // Add the matching line to results
                        }
                    }
                    // Handle default case (no flag)
                     if (strpos($line, $pattern) !== false && $found === false) {
                         $results[] = $line;
                }
            }
        }
    }
    
    // Else block for handling a specific file
    else {
        if (!isset($currentLevel[$file])) {
            return "Error: File not found.\n";
        }
        if (is_array($currentLevel[$file])) {
            return "Error: '$file' is a directory.\n";
        }
        
        // Split the file into lines
        $lines = explode("\n", trim($currentLevel[$file]));
        $found = false;
        // Loop through the lines of the specific file
        foreach ($lines as $line) {
            $line_numbers++;
            
            if ($flag === "-n") {
                if (strpos($line, $pattern) !== false) {
                    $found = true;
                    $results[] = $line_numbers . ": " . $line; // Add line number
                }
            }
            if ($flag === "-c") {
                $found = true;
                if (strpos($line, $pattern) !== false) {
                    $count++;
                }
            }
            if ($flag === "-i") {
                if (stripos($line, $pattern) !== false) {
                    $found = true;
                    $results[] = $line; // Case-insensitive match
                }
            }
            // Default matching condition (without any specific flag)
            if (strpos($line, $pattern) !== false && $found === false) {
                $results[] = $line;
            }
        }
    }

    // Return the count if '-c' flag is used, otherwise return matched lines
    if ($count > 0)  return $count;
    else return empty($results) ? "No matches found\n" : implode("\n", $results);
}


function retrieve_files_from_argument($fileSystem, $currentDirectory, $expression) : array {
    $files = [];
    $searchPath = rtrim($currentDirectory, "/");
    $pathParts = array_filter(explode("/", $searchPath), 'strlen');
    $currentLevel = $fileSystem["/"];
    
    foreach ($pathParts as $part) {
        if (!isset($currentLevel[$part]) || !is_array($currentLevel[$part])) {
            return [];
        }
        $currentLevel = $currentLevel[$part];
    }
    
    foreach ($currentLevel as $name => $content) {  
        $fullPath = $searchPath . "/" . $name;

        if (is_array($content)) {
            $files = array_merge($files, retrieve_files_from_argument($fileSystem, $fullPath, $expression));
        } else {
            if (fnmatch($expression, $name)) { 
                $files[$fullPath] = $content;
            }
        }
    }
    //if empty($files) echo "empty bozo";
    return $files;
}

function process_find($fileSystem, $currentDirectory, $path, $expression) {
    // Strip quotes from the expression (e.g., "WithoutYou.txt" → WithoutYou.txt)
    $expression = trim($expression, "\"'");
    $files = retrieve_files_from_argument($fileSystem, $path, $expression);

    foreach ($files as $fileName => $content) {
        echo "File: $fileName\n";
    }
}

function process_ping($host) : void {
	$output = $GLOBALS['ping'];
	$lines = explode("\n", $output);

        for ($i = 0; $i < count($lines); $i++) {
		echo $lines[$i] . "\n";
		flush();
		sleep(1);
	}
}

function process_ip() : void {
	echo $GLOBALS['ip'] . "\n";
}

function process_ip($flag) : void {
        if ($flag === "a" || $flag === "addr") {
        echo $GLOBALS['ip'] . "\n";
        }
        elseif ($flag === "route") {
        echo $GLOBALS['route'] . "\n";
        }
}

function process_traceroute($host) : void {
        $output = $GLOBALS['traceroute'];
        $lines = explode("\n", $output);

        for ($i = 0; $i < count($lines); $i++) {
                echo $lines[$i] . "\n";
                flush();
                sleep(1);
        }
}



// Main loop
while (true) {
    // Display the prompt with the current directory
    echo "\033[32mexample_user@LinuxLab:\033[0m" . "\033[34m$currentDirectory\033[0m". "\033[32m$\033[0m ";
    // Read input
    $handle = fopen("php://stdin", "r");
    $input = fgets($handle);
    fclose($handle);  
    $input = trim($input); // Remove whitespace and newline
    
    // Process commands
    if ($input === "ls") {
	    echo process_ls($fileSystem, $currentDirectory);
    } elseif (str_starts_with($input, "cd ")) {
        $dir = trim(substr($input, 3)); // Extract the directory name
        $error = process_cd($currentDirectory, $fileSystem, $dir);
        if ($error) {
            echo $error;
        }
    }   elseif (str_starts_with($input, "mkdir ")) {
        // Extract the directory name from the input
        $newdir = trim(substr($input, 6)); // Get everything after "mkdir "
        if ($newdir === "") {
            echo "Error: Directory name not specified.\n";
        } else {
            process_mkdir($fileSystem, $currentDirectory, $newdir);
        }
    }
    else if (str_starts_with($input, "cat")) {
        $file = trim(substr($input, 4));
        echo process_cat($fileSystem, $currentDirectory, $file);
    } 
    else if ($input === "pwd") {
        echo process_pwd($currentDirectory);
    }
    else if ($input === "mv") {
        $s = "";
        $t = "";
        echo process_mv($fileSystem, $currentDirectory, $s, $t);
    }
    else if ($input === "-l") { 
    	echo process_ls_l($fileSystem, $currentDirectory);
    }
    else if (str_starts_with($input, "chmod")) { 
	    $argument = explode(" ", $input);
	    echo process_chmod($fileSystem, $currentDirectory, $argument[1], $argument[2]);
    }
else if (str_starts_with($input, "grep")) {
    // Corrected regex to handle optional flag without requiring extra spaces
    preg_match('/grep\s+(-[^\s"]+)?\s*"?([^"]+)"?\s+(\S+)/', $input, $matches);
    
    // Check if matches are sufficient (minimum 3 groups: command, pattern, file)
    if (count($matches) < 3) {
        echo "Error: Invalid grep syntax.\n";
    } else {
        $flag = isset($matches[1]) ? $matches[1] : '';
        $pattern = $matches[2];
        $file = $matches[3];
        
        echo process_grep($fileSystem, $currentDirectory, $flag, $pattern, $file) . "\n";
    }
}
 elseif (str_starts_with($input, "find ")) {
    // Use regex to parse "find <path> -name <pattern>"
    preg_match('/find\s+(\S+)\s+-name\s+"([^"]*)"/', $input, $matches);
    if (count($matches) !== 3) {
        echo "Usage: find <path> -name \"<pattern>\"\n";
        continue;
    }
    $path = $matches[1];
    $expression = $matches[2];
    process_find($fileSystem, $currentDirectory, $path, $expression);
 }
 else if(str_starts_with($input, "ping")) {
	 if (preg_match('/^ping\s+(google\.com)$/', $input, $matches)) {
		 $host = $matches[1];
        	  process_ping($host);
    } else {
        echo "Invalid ping command format.\n";
    }
 }
    else if (str_starts_with($input, "ip")) {
    $args = explode(" ", $input); // Split input into words
    $flag = $args[1] ?? ""; // Get second word or empty string if not provided

    if ($flag === "a" || $flag === "addr" || $flag === "route") {
        process_ip($flag); // Pass argument correctly
    } else {
        echo "Invalid command. Enter 'ip help' for more information.\n";
    }
 }

 else if(str_starts_with($input, "traceroute")) {
         if (preg_match('/^traceroute\s+(google\.com)$/', $input, $matches)) {
                 $host = $matches[1];
                 process_traceroute($host);
    } else {
        echo "Invalid traceroute command format.\n";
    }
 }
 
else if ($input === "exit") {
        break;
    } else {
        echo "Command not recognized: $input\n";
    }
}

?>
