<?php
// get database connection
include_once '../config/database.php';
include_once '../objects/bestseller.php';
 
include_once '../library/PHPExcel.php';

$database = new Database();
$db = $database->getConnection();
$services = [];

$bestSeller = new Bestseller($db);

function fetchServices(){
    $apiKey = '7d35625c829faadad76685f82f27127c'; 
    $apiUrl = 'https://followiz.com/api/v2';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiUrl . '?key=' . $apiKey . '&action=services',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      // CURLOPT_HTTPHEADER => array(
      //   'Cookie: PHPSESSID=b5uh61utrbmos6bsracgii5m8j'
      // ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);
}

function readFromExcel(){
    $inputFileName = __DIR__ . '/../bestsellers.xlsx';

    $object = PHPExcel_IOFactory::load($inputFileName);
    foreach($object->getWorksheetIterator() as $worksheet){
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        for($row = 1; $row <= $highestRow; $row++){
            $item['service'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $item['ranking'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $item['id'] = trim(explode('-', $item['service'])[0]);
            $data[] = $item;
        }
    }
    return $data;
}

function calculation(){
    $categories = [];
    $list = [];
    $count_of_category = 0;

    // get all of services
    global $services;
    $services = fetchServices();

    foreach($services as $service){
        if(empty($list[$service->category])){
            $list[$service->category] = ['service_ids' => '', 'service_count' => 0, 'best_count' => 0, 'best_ids' => ''];
        }
        $list[$service->category]['service_ids'] .= $service->service . " ";
        $list[$service->category]['service_count']++;
    }

    // get top services from Excel
    $tops = readFromExcel();
    foreach($tops as $item){
        $category = findCategoryById($item);
       
        if($category && $list[$category]['best_count'] < (int)($list[$category]['service_count'] / 10)){
            $list[$category]['best_count']++;
            $list[$category]['best_ids'] .= $item['id'] . " ";
        }
    }

    // --------------> Insert $list to DB subcategory Bestseller
    // var_dump($list);
    global $bestSeller;

    $bestSeller->delete_all("sub_categories");
    $index = 0;
    foreach($list as $key => $item){
        $index++;
        $row['id'] = $index;
        $row['category_name'] = $key;
        $row['service_ids'] = trim($item['service_ids']);
        $row['service_count'] = $item['service_count'];
        $row['best_ids'] = trim($item['best_ids']);
        $row['best_count'] = $item['best_count'];
        // var_dump($row);
        $bestSeller->create("sub_categories", $row);
    }

    $main_list = [];
    foreach($list as $key => $item){
        $main_category = trim(explode('-', $key)[0]);
        if(empty($main_list[$main_category])){
            $main_list[$main_category] = ['service_ids' => '', 'service_count' => 0, 'best_count' => 0, 'best_ids' => ''];
        }
        $main_list[$main_category]['service_ids'] .= $item['service_ids'] . " ";
        $main_list[$main_category]['service_count'] += (int)$item['service_count'];
        $main_list[$main_category]['best_ids'] .= trim($item['best_ids']) . " ";
        $main_list[$main_category]['best_count'] += (int)$item['best_count'];
    }

    // insert $main_list to DB main-category Bestseller
    $bestSeller->delete_all("main_categories");
    $index = 0;
    foreach($main_list as $key => $item){
        $index++;
        $row['id'] = $index;
        $row['category_name'] = $key;
        $row['service_ids'] = trim($item['service_ids']);
        $row['service_count'] = $item['service_count'];
        $row['best_ids'] = trim($item['best_ids']);
        $row['best_count'] = $item['best_count'];
        // var_dump($row);
        $bestSeller->create("main_categories", $row);
    }

}   

function findCategoryById($item){
    global $services;
    foreach($services as $service){
        if($service->service == $item['id']){
            return $service->category;
        }
    }
    return false;
}

calculation();
?>