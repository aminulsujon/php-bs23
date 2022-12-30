<?php 
$servername = "localhost";$username = "root";$password = "";$dbname = "ecommerce";
$conn = new mysqli($servername,$username,$password,$dbname);
if ($conn->connect_error){die("Connection failed: " . $conn->connect_error);}

// $sql_category_list = "SELECT 
//     COUNT(item_category_relations.categoryId) as count, category.Name
//     FROM `item_category_relations`
//         LEFT JOIN category ON category.id = item_category_relations.categoryId
//     WHERE `categoryId` > 0
//     GROUP BY `categoryId`
//     ORDER BY `count` DESC
//     ";
// $arr_category_list = $conn->query($sql_category_list);
$sql_category_products = "SELECT 
    COUNT(item_category_relations.categoryId) as count, category.Name, category.Id
    FROM `item_category_relations`
        LEFT JOIN category ON category.id = item_category_relations.categoryId
    WHERE `categoryId` > 0
    GROUP BY `categoryId`
    ORDER BY `count` DESC
    ";
$arr_category_products = $conn->query($sql_category_products);

while($rowf = $arr_category_products->fetch_assoc()) {
    $categoryCount[$rowf['Id']] = $rowf['count'];
}


$sql_category_list = "SELECT * FROM `catetory_relations` ORDER BY `ParentcategoryId`";
$arr_category_list = $conn->query($sql_category_list);

if(!empty($arr_category_list) && $arr_category_list->num_rows > 0){
    $i = 1;
    while($rowf = $arr_category_list->fetch_assoc()) {
       $cp[$rowf['categoryId']] = $rowf['ParentcategoryId'];
       $pc[$rowf['ParentcategoryId']][$i] = $rowf['categoryId'];
       $i++;
    }
}
// print_r($pc);


$cat = [];
$sql_category = "SELECT * FROM `category`";
$arr_category = $conn->query($sql_category);
while($rowf = $arr_category->fetch_assoc()) {
    $cat[$rowf['Id']] = $rowf['Name'];
}

$child  = '';
$y=0;
$catName = '';
$children2 = $children3 = '';
$sql_category1 = "SELECT * FROM `category`";
$arr_category1 = $conn->query($sql_category1);
while($rowf = $arr_category1->fetch_assoc()) {
    $z=0;
    $y=0;
    $x=0;
    if(isset($cp[$rowf['Id']])){
        // echo $children[$rowf['Id']].$rowf['Name'].'<hr>';
        // $hp[$children[$rowf['Id']]] = $rowf['Id'];
        // $catName .= $cp[$rowf['Id']];
    }else{
        $childrens = '';
        if(isset($pc[$rowf['Id']])){
            $cpc2total = 0;
            $cpcParent = 0;
            $childSize = sizeof($pc[$rowf['Id']]);
            $m=1;
            foreach($pc[$rowf['Id']] as $key => $value){
                // print_r($pc[$value]);die();
                if(isset($pc[$value])){
                    
                    $children2 = '<ul>';
                    $cpc3total = $mp = 0;
                    foreach ($pc[$value] as $ke => $val){
                        if(isset($pc[$val])){
                            $children3 = '<ul>';
                            $x = 0;
                            foreach ($pc[$val] as $k => $v){
                                if(isset($pc[$v])){
                                    print_r('another lavel');die();
                                }
                                if(isset($categoryCount[$v])){
                                    $cpc3 = $categoryCount[$v];
                                }else{
                                    $cpc3 = 0;
                                }
                                // $cpcParent = $cpcParent+$cpc3;
                                $mp = $mp+$cpc3;
                                $x = $x+$cpc3;
                                $cpc3total = $cpc3total + $cpc3;
                                $children3 .= '<li>'.$cat[$v].'('.$cpc3.')'.'</li>';
                            }
                            $children3 .= '</ul>';
                        }
                        if(isset($categoryCount[$val])){
                            $cpc2 = $categoryCount[$val];
                        }else{
                            $cpc2 = 0;
                        }
                        $y = $x+$y+$cpc2;
                        // print_r($mp.'/');
                        // $cpcParent2 = $cpcParent+$cpc2;
                        // $y = $y+$cpc2;
                        $mp = $mp + $cpc2;
                        $children2 .= '<li>'.$cat[$val].'('.$cpc2.')'.$children3.'</li>';
                    }
                    $children2 .= '</ul>';
                }
                // print_r($cpc2total.'/');
                if(isset($categoryCount[$value])){
                    $cpc1 = $categoryCount[$value];
                }else{
                    $cpc1 = 0;
                }
                $z = $x+$y+$cpc1;
                // $cpcParent = $cpcParent2+$cpc1;
                // $cpc1total = $cpc2total + $cpc1;
                $mp = $mp+$cpc1;
                $childrens .= '<li>'.$cat[$value].'('.$mp.')'.$children2.'</li>';
                $m++;
            }
        }
        $catName .= '<li>'.$rowf['Name'].'['.$z.']<ul>'.$childrens.'</ul></li>';
    }
   

}





?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Task 2 | PHP Developer Test</title>
        <style>h1{font-size:1rem;}table{border-top:1px solid #ddd;border-left:1px solid #ddd}td{border-right:1px solid #ddd;border-bottom:1px solid #ddd;padding:10px}</style>
	</head>
	<body>
    <h1>Task 2: To create a categories tree with number of items contain in each category</h1>
    
    <?php
    echo '<ul>'.$catName.'</ul>';
    ?>
    
    </body>
</html>
