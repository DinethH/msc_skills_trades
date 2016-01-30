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
    $product_type = 0;
    
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
            
            if ($stmt->execute() == true) {
                //print "$counter ";
            }
            $counter++;
        }
        
    }
}

print "Total $counter records processed<br>";

fclose($file);



/// print data

$stmt = $db->query('SELECT * FROM msc_skills_traders');

print "Fetched data from the DB<br>";

?>

<style type="text/css">
    .wrapper {
        width: 100%;
    }
    .float {
        float: left;
    }
    .tab1 { width: 50px; }
    .tab2 { width: 200px; }
    .tab3 { width: 300px; }
    .tab4 { width: 100px; }
    .tab5 { width: 500px; }
    .tab6 { width: 100px; }
    .head {
        font-weight: bold;
    }

</style>

<div class="wrapper">
    <div class="inner-wrapper head">
        <div class="float tab1">ID</div>
        <div class="float tab2">Industry</div>
        <div class="float tab3">Category</div>
        <div class="float tab4">Code</div>
        <div class="float tab5">Description</div>
        <div class="float tab6">Product Type</div>
        <div style="clear: both;"></div>
    </div>
    


<?php



while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $industry = $row['industry']; 
    $category = $row['category'];
    $code = $row['code'];
    $description = $row['description'];
    $product_type = $row['product_type'];
    
?>

    <div class="inner-wrapper children">
        <div class="float tab1"><?php echo $id; ?></div>
        <div class="float tab2"><?php echo $industry; ?></div>
        <div class="float tab3"><?php echo $category; ?></div>
        <div class="float tab4"><?php echo $code; ?></div>
        <div class="float tab5"><?php echo $description; ?></div>
        <div class="float tab6"><?php echo $product_type; ?></div>
        <div style="clear: both;"></div>
    </div>


<?php
    
    
}

?>

</div>

<?php

// output to a csv
$stmt = $db->query('SELECT * FROM msc_skills_traders');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen('output.csv', 'w');

foreach ($rows as $row) {
    fputcsv($fp, $row);
}

fclose($fp);