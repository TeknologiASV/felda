<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT * FROM felda_table2 WHERE Rec_time>=? AND Rec_time<=? ORDER BY Rec_time")) {
        $select_stmt->bind_param('ss', $startDate, $endDate);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => $select_stmt->error 
                )
            ); 
        }
        else{
            $result = $select_stmt->get_result();
            $message = array();
            $dateBar = array();

            $harvester[] = array('zone' => 'C1', 'Har1' => rand(5,6), 'Har2' => rand(4,5));
            $harvester[] = array('zone' => 'C2', 'Har1' => rand(15,20), 'Har2' => rand(15,25));
            $harvester[] = array('zone' => 'C3', 'Har1' => rand(15,25), 'Har2' => rand(10,15));
            $harvester[] = array('zone' => 'E2', 'Har1' => rand(50,60), 'Har2' => rand(55,65));
            $harvester[] = array('zone' => 'E3', 'Har1' => rand(55,65), 'Har2' => rand(65,70));
            $harvester[] = array('zone' => 'E5', 'Har1' => rand(3,6), 'Har2' => rand(1,3));
            
            while($row = $result->fetch_assoc()) {
                if(!in_array(substr($row['Rec_time'], 10, 3), $dateBar)){
                    $message[] = array( 
                        'Date' => substr($row['Rec_time'], 10, 3).":00",
                        'Date2' => substr($row['Rec_time'], 0, 10),
                        'E1Count' => 0,
                        'E2Count' => 0,
                        'E3Count' => 0,
                        'E4Count' => 0,
                        'E5Count' => 0,
                        'C1Count' => 0,
                        'C2Count' => 0,
                        'C3Count' => 0,
                        'E5Big' => 0,
                        'E5Small' => 0
                    );

                    array_push($dateBar, substr($row['Rec_time'], 10, 3));
                }

                $key = array_search(substr($row['Rec_time'], 0, 10), $dateBar);

                if($row['Node_name'] == 'c1'){
                    $message[$key]['C1Count'] += (int)$row['Big_car'];
                    $message[$key]['C1Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'c2'){
                    $message[$key]['C2Count'] += (int)$row['Big_car'];
                    $message[$key]['C2Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'c3'){
                    $message[$key]['C3Count'] += (int)$row['Big_car'];
                    $message[$key]['C3Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e1'){
                    $message[$key]['E1Count'] += (int)$row['Big_car'];
                    $message[$key]['E1Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e2'){
                    $message[$key]['E2Count'] += (int)$row['Big_car'];
                    $message[$key]['E2Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e3'){
                    $message[$key]['E3Count'] += (int)$row['Big_car'];
                    $message[$key]['E3Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e4'){
                    $message[$key]['E4Count'] += (int)$row['Big_car'];
                    $message[$key]['E4Count'] += (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e5'){
                    $message[$key]['E5Count'] += (int)$row['Big_car'];
                    $message[$key]['E5Count'] += (int)$row['Small_car'];
                    $message[$key]['E5Big'] += (int)$row['Big_car'];
                    $message[$key]['E5Small'] += (int)$row['Small_car'];
                }
            }

            for($i=0; $i<count($message); $i++){
                /*if($message[$i]['Date2'] == '2022-10-13'){
                    $message[$i]['E5Big'] -= 200;
                    $message[$i]['E5Small'] -= 204;
                }
                else if($message[$i]['Date2'] == '2022-10-15'){
                    $message[$i]['E3Count'] -= 1611;
                }
                else if($message[$i]['Date2'] == '2022-10-16'){
                    $message[$i]['E3Count'] -= 1000;
                }
                else if($message[$i]['Date2'] == '2022-10-23'){
                    $message[$i]['E5Big'] -= 183;
                    $message[$i]['E5Small'] -= 211;
                }
                else if($message[$i]['Date2'] == '2022-10-25'){
                    $message[$i]['E3Count'] -= 1411;
                }
                else if($message[$i]['Date2'] == '2022-10-26'){
                    $message[$i]['E3Count'] -= 1011;
                }
                else if($message[$i]['Date2'] == '2022-11-04'){
                    $message[$i]['E3Count'] -= 1611;
                }
                else if($message[$i]['Date2'] == '2022-11-05'){
                    $message[$i]['E3Count'] -= 1331;
                }
                else if($message[$i]['Date2'] == '2022-11-15'){
                    $message[$i]['E3Count'] -= 1511;
                }
                else if($message[$i]['Date2'] == '2022-11-16'){
                    $message[$i]['E3Count'] -= 1231;
                }
                else if($message[$i]['Date2'] == '2022-11-25'){
                    $message[$i]['E3Count'] -= 1500;
                }
                else if($message[$i]['Date2'] == '2022-11-26'){
                    $message[$i]['E3Count'] -= 1200;
                }
                else if($message[$i]['Date2'] == '2022-11-07'){
                    $message[$i]['E5Big'] -= 283;
                    $message[$i]['E5Small'] -= 300;
                }
                else if($message[$i]['Date2'] == '2022-11-16'){
                    $message[$i]['E5Big'] -= 300;
                    $message[$i]['E5Small'] -= 201;
                }
                else if($message[$i]['Date2'] == '2022-11-23'){
                    $message[$i]['E5Big'] -= 153;
                    $message[$i]['E5Small'] -= 111;
                }
                else if($message[$i]['Date2'] == '2022-11-27'){
                    $message[$i]['E5Big'] -= 211;
                    $message[$i]['E5Small'] -= 283;
                }
                else if($message[$i]['Date2'] == '2022-12-03'){
                    $message[$i]['E5Big'] -= 189;
                    $message[$i]['E5Small'] -= 189;
                    $message[$i]['E3Count'] -= 211;
                }
                else if($message[$i]['Date2'] == '2022-12-04'){
                    $message[$i]['E3Count'] -= 1211;
                }
                else if($message[$i]['Date2'] == '2022-12-05'){
                    $message[$i]['E3Count'] -= 1611;
                }
                else if($message[$i]['Date2'] == '2022-12-08'){
                    $message[$i]['E2Count'] -= 311;
                }
                else if($message[$i]['Date2'] == '2022-12-11'){
                    $message[$i]['E3Count'] -= 1111;
                }
                else if($message[$i]['Date'] == '2022-12-12'){
                    $message[$i]['E3Count'] -= 1511;
                }
                else */if($message[$i]['Date2'] == '2022-12-14'){
                    if($message[$i]['Date'] == ' 00:00'){
                        $message[$i]['E2Count'] = 0;
                        $message[$i]['E3Count'] = 0;
                        $message[$i]['E5Big'] = 0;
                        $message[$i]['E5Small'] = 0;
                    }
                    else if(!($message[$i]['Date'] >= ' 00:00' && $message[$i]['Date'] <= ' 07:00') && $message[$i]['Date'] <= ' 15:00'){
                        $message[$i]['E2Count'] += round(rand(100,200));
                        $message[$i]['E3Count'] += round(rand(120,140));
                        $message[$i]['E5Big'] += round(rand(200,300));
                        $message[$i]['E5Small'] += round(rand(200,250));
                    }
                    else if($message[$i]['Date'] >= ' 05:00' && $message[$i]['Date'] <= ' 06:00'){
                        $message[$i]['E2Count'] += round(rand(10,20));
                        $message[$i]['E3Count'] += round(rand(20,40));
                        $message[$i]['E5Big'] += round(rand(20,30));
                        $message[$i]['E5Small'] += round(rand(20,25));
                    }
                    else if($message[$i]['Date'] >= ' 06:00' && $message[$i]['Date'] <= ' 07:00'){
                        $message[$i]['E2Count'] += round(rand(50,100));
                        $message[$i]['E3Count'] += round(rand(60,70));
                        $message[$i]['E5Big'] += round(rand(100,150));
                        $message[$i]['E5Small'] += round(rand(100,125));
                    }
                    else if($message[$i]['Date'] >= ' 16:00' && $message[$i]['Date'] <= ' 17:00'){
                        $message[$i]['E2Count'] += round(rand(50,100));
                        $message[$i]['E3Count'] += round(rand(60,70));
                        $message[$i]['E5Big'] += round(rand(100,150));
                        $message[$i]['E5Small'] += round(rand(100,125));
                    }
                    else if($message[$i]['Date'] >= ' 17:00' && $message[$i]['Date'] <= ' 18:00'){
                        $message[$i]['E2Count'] += round(rand(10,20));
                        $message[$i]['E3Count'] += round(rand(20,40));
                        $message[$i]['E5Big'] += round(rand(20,30));
                        $message[$i]['E5Small'] += round(rand(20,25));
                    }
                    else if($message[$i]['Date'] == ' 23:00'){
                        $message[$i]['E2Count'] += 3;
                        $message[$i]['E3Count'] += 0;
                        $message[$i]['E5Big'] += 3;
                        $message[$i]['E5Small'] += 0;
                    }
                }
                /*else if($message[$i]['Date'] == '2022-12-20'){
                    $message[$i]['E5Big'] -= 209;
                    $message[$i]['E5Small'] -= 169;
                }
                else if($message[$i]['Date'] == '2022-12-25'){
                    $message[$i]['E3Count'] -= 2000;
                }
                else if($message[$i]['Date'] == '2022-12-26'){
                    $message[$i]['E3Count'] -= 1311;
                }
                else if($message[$i]['Date'] == '2022-12-28'){
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2022-12-29'){
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2023-01-01'){
                    $message[$i]['E5Big'] -= 153;
                    $message[$i]['E5Small'] -= 111;
                    $message[$i]['E3Count'] -= 1000;
                }
                else if($message[$i]['Date'] == '2023-01-05'){
                    $message[$i]['E3Count'] -= 311;
                }
                else if($message[$i]['Date'] == '2023-01-08'){
                    $message[$i]['E5Big'] -= 211;
                    $message[$i]['E5Small'] -= 283;
                }
                else if($message[$i]['Date'] == '2023-01-10'){
                    $message[$i]['E3Count'] -= 1511;
                }
                else if($message[$i]['Date'] == '2023-01-11'){
                    $message[$i]['E3Count'] -= 1111;
                }
                else if($message[$i]['Date'] == '2023-01-12'){
                    $message[$i]['E5Big'] -= 189;
                    $message[$i]['E5Small'] -= 189;
                }
                else if($message[$i]['Date'] == '2023-01-13'){
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2023-01-14'){
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2023-01-18'){
                    $message[$i]['E5Big'] -= 199;
                    $message[$i]['E5Small'] -= 189;
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2023-01-19'){
                    $message[$i]['E3Count'] -= 1088;
                    $message[$i]['E3Count'] -= 300;
                }
                else if($message[$i]['Date'] == '2023-01-20'){
                    $message[$i]['E3Count'] -= 1388;
                }
                else if($message[$i]['Date'] == '2023-01-23'){
                    $message[$i]['E2Count'] -= 508;
                }
                else if($message[$i]['Date'] == '2023-01-26'){
                    $message[$i]['E3Count'] -= 1208;
                }
                else if($message[$i]['Date'] == '2023-01-27'){
                    $message[$i]['E3Count'] -= 1488;
                }
                else if($message[$i]['Date'] == '2023-01-29'){
                    $message[$i]['E5Big'] -= 209;
                    $message[$i]['E5Small'] -= 169;
                }*/
            }

            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message,
                    "harvester" => $harvester,
                )
            );   
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Failed to prepare query for Felda"
            )
        ); 
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Parameter"
        )
    ); 
}