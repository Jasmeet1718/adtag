<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**************************************
Functions for the dropdowns begin here
**************************************/

/*
This function gets all the clients in the database
Selects only distinct ones
*/

function getAllClients($conn) {
    $response;
    $sql = "SELECT DISTINCT `client` FROM `adtagdata`;";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data in the database
*/

function getAllCampaigns($conn) {
    $response;
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata`;";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data between the two given dates
*/

function getCampaignsWithDate($conn,$from,$to) {
    $response;
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata` WHERE `date` BETWEEN ? AND ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss',$from,$to);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data for the given clients
*/

function getCampaignsWithClient($conn,$clients) {
    $response;
    $in = join(',', array_fill(0, count($clients), '?'));
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata` WHERE `client` IN ($in);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($clients)), ...$clients);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data for the given date and clients
*/

function getCampaignsWithDateAndClient($conn,$from,$to,$clients) {
    $response;
    $in = join(',', array_fill(0, count($clients), '?'));
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata` WHERE `date` BETWEEN ? AND ? AND `client` IN ($in);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss' . str_repeat('s', count($clients)),$from,$to,...$clients);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/***********************************
Functions for the records begin here
************************************/

/*
This function gets all the data between the two given dates
*/

function getOnlyDateRecords($conn,$from,$to) {
    $response;
    $sql = "SELECT * FROM `adtagdata` WHERE `date` BETWEEN ? AND ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss',$from,$to);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data for the selected clients
*/

function getOnlyClientRecords($conn,$clients) {
    $response;
    $in = join(',', array_fill(0, count($clients), '?'));
    $sql = "SELECT * FROM `adtagdata` WHERE `client` IN ($in);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($clients)), ...$clients);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data for the selected campaigns
*/

function getOnlyCampaignRecords($conn,$campaigns) {
    $response;
    $in = join(',', array_fill(0, count($campaigns), '?'));
    $sql = "SELECT * FROM `adtagdata` WHERE `id` IN ($in);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($campaigns)), ...$campaigns);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the data for the selected campaigns and date
*/

function getDateAndClientRecords($conn,$from,$to,$clients) {
    $response;
    $in = join(',', array_fill(0, count($clients), '?'));
    $sql = "SELECT * FROM `adtagdata` WHERE `date` BETWEEN ? AND ? AND `client` IN ($in);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss' . str_repeat('s', count($clients)),$from,$to,...$clients);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function updates the status of the campaign
*/

function updateStatus($conn,$id,$status,$analytic_name) {
    date_default_timezone_set('Asia/Kolkata');
    $time = date('Y-m-d H:i:s');
    $response;
    $sql = "UPDATE `adtagdata` SET `status` = ?,`analytic_name` = ? , `active_time` = '$time' WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi',$status,$analytic_name,$id);
    if ($stmt->execute()) {
        $response = true;
    } else {
        $response = false;
    }
    // $conn->close();
    return $response;
}

function updateApprovalStatus($conn,$id,$approval_status,$client_name) {
    date_default_timezone_set('Asia/Kolkata');
    $time = date('Y-m-d H:i:s');
    $response;
    $sql = "UPDATE `adtagdata` SET `approval` = ?,`client_name` = ? , `client_time` = '$time' WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi',$approval_status,$client_name,$id);
    if ($stmt->execute()) {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}

function updateRemark($conn,$id,$remark,$status_v,$veena_name) {
    date_default_timezone_set('Asia/Kolkata');
    $time = date('Y-m-d H:i:s');
    $response;
    if ($_SESSION['team']!=='Veena'){$sql = "UPDATE `adtagdata` SET `remark` = ?, `status_v` = ? WHERE `id` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi',$remark,$status_v,$id);
        if ($stmt->execute()) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;}
        else{
            $sql = "UPDATE `adtagdata` SET `remark` = ?, `status_v` = ? ,`veena_name` = ? , `veena_time` = '$time' WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi',$remark,$status_v,$veena_name,$id);
    if ($stmt->execute()) {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
        }
    
}

/*******************************************
Functions for the client.php page begin here
*******************************************/

/*
This function gets all the ACTIVE campaigns on the client page and appends to the
dropdown
*/

function getAllActiveCampaignsWithClient($conn,$master_client) {
    $response;
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata` WHERE `master_client` = ? AND `status` = 'active';";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$master_client,);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the ACTIVE campaigns withing a certain date on the client page and appends to the
dropdown
*/

function getActiveCampaignsWithClientAndDate($conn,$from,$to,$master_client) {
    $response;
    $sql = "SELECT `id`,`campaign_name` FROM `adtagdata` WHERE `date` BETWEEN ? AND ? AND `master_client` = ? AND `status` = 'active';";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$from,$to,$master_client);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the ACTIVE campaigns between the date range
*/

function getActiveRecordsWithDate($conn,$from,$to,$master_client) {
    $response;
    $sql = "SELECT * FROM `adtagdata` WHERE `master_client` = ? AND `date` BETWEEN ? AND ? AND `status` = 'active';";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$master_client,$from,$to);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

/*
This function gets all the ACTIVE campaigns that are selected
*/
function getActiveRecordsWithClient($conn,$master_client,$campaigns) {
    $response;
    $in = join(',', array_fill(0, count($campaigns), '?'));
    $sql = "SELECT * FROM `adtagdata` WHERE `master_client` = ? AND `id` IN ($in) AND `status` = 'active';";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s' . str_repeat('i', count($campaigns)),$master_client,...$campaigns);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        foreach ($res as $row) {
            $response[] = $row;
        }
    }
    // $conn->close();
    return $response;
}

?>