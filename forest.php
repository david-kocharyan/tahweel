<?php
/* =========================================
 * Enqueues child theme stylesheet
 * ========================================= */
$last_insert_id;
function gosolar_zozo_enqueue_child_theme_styles() {
    wp_enqueue_style( 'gosolar-zozo-child-style', get_stylesheet_uri(), array(), '1.2.0' );
}
add_action( 'wp_enqueue_scripts', 'gosolar_zozo_enqueue_child_theme_styles', 30 );


function hid() {
    $sum = $_POST['sum'];
    $trees = $_POST['trees'];
    $co = $_POST['co'];
    $ht = '<input type="hidden" name="sum" value='."$sum".'>';
    $ht .= '<input type="hidden" name="trees" value='."$trees".'>';
    $ht .= '<input type="hidden" name="co" value='."$co".'>';
    return $ht;
}

add_shortcode('hid', 'hid');

function co()
{
    $co = $_POST['co'] ?? $_GET['co'];
    return '<input type="hidden" id="co" name="co" value='."$co".'>';
}
add_shortcode('co', 'co');

function firstName()
{
    $first = $_POST['firstname'] ?? $_GET['firstName'];
    return '<input type="hidden" id="firstName" name="firstName" value='."$first".'>';
}
add_shortcode('firstName', 'firstName');

function lastName()
{
    $last = $_POST['lastname'] ?? $_GET['lastName'];
    return '<input type="hidden" id="lastName" name="lastName" value='."$last".'>';
}
add_shortcode('lastName', 'lastName');

function email()
{
    $email = $_POST['email'];
    return '<input type="hidden" id="email" name="email" value='."$email".'>';
}
add_shortcode('email', 'email');


function offsetParam()
{
    $offset = (isset($_GET['offset']) && !empty($_GET['offset'])) ? number_format((float)$_GET['offset'], 1, '.', ''):  "";
    if(!empty($offset)) {
        return (explode(".", $offset)[1] == 0) ? explode(".", $offset)[0] : $offset;
    }
    return $offset;
}
add_shortcode('offsetParam', 'offsetParam');

function offsetParam5()
{
    return (($_GET['offset'] * 5) ?? "") == 0 ? "" : round((offsetParam() * 5));
}
add_shortcode('offsetParam5', 'offsetParam5');

function offsetParam25()
{
    return (($_GET['offset'] * 25) ?? "") == 0 ? "" : intval(offsetParam5() * 5);
}
add_shortcode('offsetParam25', 'offsetParam25');

function paramWithText()
{
    $param = offsetParam();
    if(!empty($param)) {
        return "Your total CO<sub>2</sub> offset is <strong>".$param."</strong> tons, that is <strong> ".offsetParam5() ." </strong> trees";
    }
    return;
}
add_shortcode('paramWithText', 'paramWithText');

if(isset($_POST['action']) && $_POST['action'] == 'mp') {

    $name = $_POST['firstname'] ?? "-";
    $surname = $_POST['lastname'] ?? "-";
    $email = $_POST['email'] ?? "-";
    $sum = $_POST['sum'] ?? "-";
    $date = (isset($_POST['date']) && null != $_POST['date']) ? strtotime($_POST['date']) : time();
    $status = isset($_POST['date']) ? 1 : 0;
    $order_id = connect_to_mysql(false, true)['order_id'] + 1;

    $sql = "INSERT INTO donators (name, surname, email, sum, date, status, orderID) VALUES ('".$name."', '".$surname."', '".$email."', '".$sum."', '". $date ."', '". $status ."', '".$order_id."' )";
    try{
        connect_to_mysql(null, null, null, $sql);
    } catch (Exception $e) {

    }
    if(!isset($_POST['date'])) {
        generate(null, $order_id);
    } else {
        header('Location: https://myforestarmenia.org/donors/');
        exit();
    }

}


function generate($payment_id = null, $order_id = null){

//    $ameria_base_url = "https://services.ameriabank.am/";

    $ameria_base_url = "https://servicestest.ameriabank.am/"; // * TEST

    $generating_unique_payment_url = "VPOS/api/VPOS/InitPayment";
    $get_payment_details_url = "VPOS/api/VPOS/GetPaymentDetails";
//    $client_id = "1ca7ef6f-73dc-4a97-a9d2-c79ac327dce5";

    $client_id = "b04658fd-6e74-4f5b-b8ff-9272074841e3"; // * TEST

//    $username = "19535131_api";
    $username = "3d19541048"; // * TEST

//    $password = "nQErv8KNePpWghv";
    $password = "lazY2k"; // *TEST

    $amount = $_POST["sum"] ?? 10;
    $firstName = $_POST['firstname'];
    $lastName = $_POST["lastname"];
    $trees = $_POST['trees'];
    $co = $_POST['co'];
    $lang = $_GET['lang'] ?? "en";
    $backUrl = $lang == "am" ? "https://myforestarmenia.org/hy/certificate" : "https://myforestarmenia.org/certificate";
    $back_url_parameters = "?firstName=$firstName&lastName=$lastName&trees=$trees&co=$co";
    $data = array(
        "ClientID" => $client_id,
        "Username" => $username,
        "Password" => $password,
        "Description" => "Donation for planting trees",
        "OrderID" => $order_id,
//        "Amount" => $amount,
        "Amount" => 10, // * TEST
        "BackURL" => $backUrl.$back_url_parameters,
//        "Currency" => "840"
        "Currency" => "051" // * TEST
    );

    if(null == $payment_id) {
        $response = send($ameria_base_url, $generating_unique_payment_url, true, json_encode($data));
        connect_to_mysql(true, null, $order_id);

        if(gettype($response) == "string") {
            $response = json_decode($response);
            $id = $response->PaymentID;
            $redirect_url = "VPOS/Payments/Pay?id=$id&lang=$lang";
// 			send_email($firstName, $lastName, $trees, $co);
            wp_redirect( $ameria_base_url.$redirect_url );
            exit;
        }

    } else {
        $details = array(
            "PaymentID" => $payment_id,
            "Username" => $username,
            "Password" => $password,
        );
        return send($ameria_base_url, $get_payment_details_url, true, json_encode($details));
    }
}

if(isset($_GET["paymentID"])) {
    $answer = generate($_GET["paymentID"]);
    if(gettype($answer) == "string") {
        $answer = json_decode($answer);
        function paymentDetails()
        {

// 			.$answer->Amount;
            try {
                global $last_insert_id;
                $update = "UPDATE donators SET status = 1 WHERE orderID = '". $_GET['orderID'] ."'";
                connect_to_mysql(null, null, null, null, null, null, $update);

            } catch (Exception $e) {

            }

            return "Thank you for offsetting your carbon footprint. We are generating a Certificate, which will be downloaded to your device.";
        }

        function canceledPayment() {
            return "Something Went Wrong, Please Try Again.";
        }

        function responceSuccess() {
            return 1;
        }
        function responceFail() {
            return 0;
        }

        if($answer->ResponseCode == 00 || $answer->ResponseCode == 1 || $answer->ResponseCode == 01) {
            add_shortcode('paymentDetails', 'paymentDetails');
            add_shortcode('responce', 'responceSuccess');
            global $wpdb;
            $email = $wpdb->get_results("SELECT sum, email FROM donators WHERE orderID = '". $_GET['orderID'] ."' ");
            $text = $wpdb->get_results("SELECT * FROM static_texts");
            if($email[0]->sum < 250) {
                send_email($email[0]["email"], $text[0]["supp_email"]);
            } elseif($email[0]->sum >= 250 && $email[0]->sum < 1500) {
                send_email($email[0]["email"], $text[0]["fco_email"]);
            } else {
                send_email($email[0]["email"], $text[0]["ci_email"]);
            }
        } else {
            add_shortcode('paymentDetails', 'canceledPayment');
            add_shortcode('responce', 'responceFail');
        }


    }

}

function send($ameria_base_url, $url, $post = null, $data = null) {
    $curl = curl_init();

    $headers = array('Content-Type: application/json', 'accept: application/json');


    curl_setopt($curl, CURLOPT_URL, $ameria_base_url . $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    if($post != null) {
        curl_setopt($curl, CURLOPT_POST, true);
    }
    if(null != $data) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {

        return $err;
    } else {

        return $response;
    }
}

function connect_to_mysql($insert = null, $select = null, $order_id = null, $sql_query = null, $last_id = null, $getData = null, $global_query = null){
    $servername = "localhost";
    $username = "myfophgs_myforestarm";
    $password = "vFJ81x7CjU";
    $dbname = "myfophgs_myforestarm";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(null != $global_query) {
        $conn->query($global_query);
    }
    if(null != $getData && $getData == 'form') {
        $sel = "SELECT * FROM donators WHERE status = 0";
        $res = $conn->query($sel)->free_result();


        // $res->fetch_all(MYSQLI_ASSOC);
    }
    if(null != $sql_query) {
        $conn->query($sql_query);
        global $last_insert_id;
        $last_id = mysqli_insert_id($conn);
        $last_insert_id = $last_id;
        return;
    }

    if($insert != null) {
        $sql = "INSERT INTO ameria_orders (order_id) VALUES ('".$order_id."')";
        $conn->query($sql);
    } elseif($select != null) {
        $sql = "SELECT order_id FROM ameria_orders ORDER BY id DESC LIMIT 1";
        return $conn->query($sql)->fetch_assoc();
    }

}

function send_email($email, $msg)
{
//     $msg = wordwrap($msg,70);
    $headers = "From: MyForestArmenia<info@myforestarmenia.org>";
    // send email
    mail($email, "Thank You Letter", $msg, $headers);
}

// send_email("Pargev", "Agh", 3, 5);

function post_val($name) {
    if(!isset($_POST[$name])) return null;
    elseif(empty($_POST[$name])) return null;
    return $_POST[$name];
}

// if($_SERVER[REQUEST_URI] == "/about-us/" || $_SERVER[REQUEST_URI] == "/hy/about-us/") {

//     function commited()
//     {
//         global $wpdb;
//         $commited_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum >= 250 AND sum <= 999 ORDER BY date DESC ");
//     	return json_encode($commited_members);
//     }
//     add_shortcode('commited', 'commited');

//     function benefactors()
//     {
//         global $wpdb;
//         $benefactors_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum >= 1000 ORDER BY date DESC ");
//     	return json_encode($benefactors_members);
//     }
//     add_shortcode('benefactors', 'benefactors');

//     function supporters()
//     {
//         global $wpdb;
//         $supporters_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum <= 249 ORDER BY date DESC");
//     	return json_encode($supporters_members);
//     }
//     add_shortcode('supporters', 'supporters');

// } else if($_SERVER[REQUEST_URI] == "/subscribed-users/") {
//     function donors()
//     {
//         global $wpdb;
//         $all = $wpdb->get_results( "SELECT * FROM donators ORDER BY id DESC ");
//     	return json_encode($all);
//     }
//     add_shortcode('donors', 'donors');
// }




function redirectBack()
{
    $location = $_SERVER['HTTP_REFERER'];
    wp_safe_redirect($location);
    exit();
}



if($_SERVER[REQUEST_URI] == "/donate-page/" || $_SERVER[REQUEST_URI] == "/hy/donate-page/") {

    function commited()
    {
        global $wpdb;
        $commited_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum >= 250 AND sum < 1500 ORDER BY date DESC ");
        return json_encode($commited_members);
    }
    add_shortcode('commited', 'commited');

    function benefactors()
    {
        global $wpdb;
        $benefactors_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum >= 1500 AND sum < 10000 ORDER BY date DESC ");
        return json_encode($benefactors_members);
    }
    add_shortcode('benefactors', 'benefactors');

    function supporters()
    {
        global $wpdb;
        $supporters_members = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum < 250 ORDER BY date DESC");
        return json_encode($supporters_members);
    }
    add_shortcode('supporters', 'supporters');

    function ambassadors()
    {
        global $wpdb;
        $ambassadors = $wpdb->get_results( "SELECT name, surname, sum, date FROM donators WHERE status = 1 AND sum >= 10000 ORDER BY date DESC");
        return json_encode($ambassadors);
    }
    add_shortcode('ambassadors', 'ambassadors');

} else if($_SERVER[REQUEST_URI] == "/subscribed-users/") {
    function donors()
    {
        global $wpdb;
        $all = $wpdb->get_results( "SELECT * FROM donators ORDER BY date DESC");
        return json_encode($all);
    }
    add_shortcode('donors', 'donors');
} else if($_SERVER[REQUEST_URI] == "/email-messages") {
    global $wpdb;
    $wpdb->update("static_texts",
        array(
            "supp_email" => $_POST["supp_email"],
            "fco_email" => $_POST["fco_email"],
            "ci_email" => $_POST["ci_email"],
        ),
        array( 'id' => 1 ) );
    redirectBack();

} else if($_SERVER[REQUEST_URI] == "/donors/") {

    function emailTexts()
    {
        global $wpdb;
        $data = $wpdb->get_results( "SELECT * FROM static_texts" );
        return json_encode($data);
    }
    add_shortcode('emailTexts', 'emailTexts');

}
