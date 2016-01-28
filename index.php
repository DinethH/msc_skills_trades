<?php

$db = new PDO('mysql:host=localhost;dbname=testDB;charset=utf8', 'dinethh', '');
$stmt = $db->prepare("TRUNCATE TABLE msc_skills_traders");

if ($stmt->execute() == true) {
    print "Database cleared<br>";
}

$file = fopen("CSI_codes (building divisions).txt","r");

$line = fgets($file);

$product_category = "";
$counter = 0;

while(!feof($file)) {
    $line = fgets($file);
    $words = preg_split('/(\s)/', $line, PREG_SPLIT_DELIM_CAPTURE);
    
    $industry = "Building & Construction";
    $code = trim($words[0]);
    $description = trim($words[1]);
    $product_type = 1;
    
    if ($code >= 1000) {
        
        if (($code % 1000) == 0) {
            $w2 = (explode(" - ",$description));
            $product_category = $w2[1];
        } else {
            $stmt = $db->prepare("INSERT INTO msc_skills_traders (industry, category, code, description, product_type) 
                VALUES (:industry, :category, :code, :description, :product_type)");
            $stmt->bindParam(':industry', $industry);
            $stmt->bindParam(':category', $product_category);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':product_type', $product_type);
            
            $stmt->execute();
            
            if ($stmt->execute() == true) {
                print "Line # $counter inserted<br>";
            }
        }
        $counter++;
    }
}


fclose($file);
