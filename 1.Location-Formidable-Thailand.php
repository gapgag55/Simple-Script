<?php
  //connect to mysql db
  $servername = "some servername";
  $username = "some username";
  $password = "some password";
  $dbname = "some database";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  echo "Connected successfully";

  mysqli_set_charset($conn,"utf8");


  //read the json file contents
  $changwats = file_get_contents('https://raw.githubusercontent.com/ignitry/thai-tambons/master/changwats/json/th.json');
  $amphoes = file_get_contents('https://raw.githubusercontent.com/ignitry/thai-tambons/master/amphoes/json/th.json');
  $tambons = file_get_contents('https://raw.githubusercontent.com/ignitry/thai-tambons/master/tambons/json/th.json');


  //convert json object to php associative array
  $data = json_decode($changwats, true);
  $changwats = $data['th']['changwats'];

  $data = json_decode($amphoes, true);
  $amphoes = $data['th']['amphoes'];

  $data = json_decode($tambons, true);
  $tambons = $data['th']['tambons'];

  $frm_id = 12160;
  $itemID = 60426;

  $desc = "a:2:{s:7:\"browser\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36\";s:8:\"referrer\";s:83:\"http://surveyform.local/wp-admin/admin-ajax.php?action=frm_forms_preview&form=xa7xk\";}";

  foreach ($changwats as $changwat):
  // Array(pid, name) - $changwat
    foreach ($amphoes as $amphoe):
      // Array(pid, name, changwat_pid)
      if ($changwat['pid'] == $amphoe['changwat_pid']) {
        foreach ($tambons as $tambon):
          // Array(pid, name, latitude, longtitude, amphoe_pid, changwat_pid)
          if ($amphoe['pid'] == $tambon['amphoe_pid']) {
            $changwat_name = $changwat['name'];
            $amphoe_name = $amphoe['name'];
            $tambon_name = $tambon['name'];

            $frm_id++;
            // wp_frm_items
            $entry = "INSERT INTO wp_frm_items (id, item_key, name, description, ip, form_id, post_id, user_id, parent_item_id, is_draft, updated_by, created_at, updated_at) VALUES('$frm_id', '$frm_id', '$amphoe_name', '$desc', '172.17.0.1', 20, 0, 1, 0, 0, '2018-07-02 16:52:21', '2018-07-02 16:52:21', '2018-07-02 16:52:21')";

            if ($conn->query($entry) === TRUE) {
              // echo "New entry created successfully";
            } else {
                echo $conn->error;
            }

            $item_one = $itemID++;
            $item_two = $itemID++;
            $item_three = $itemID++;

            // //insert item meta
            $addItem = "INSERT INTO wp_frm_item_metas (id, meta_value, field_id, item_id, created_at) VALUES
            ('$item_one', '$changwat_name', '420', '$frm_id', '2018-07-02 16:52:21'),
            ('$item_two', '$amphoe_name', '419', '$frm_id', '2018-07-02 16:52:21'),
            ('$item_three', '$tambon_name', '418', '$frm_id', '2018-07-02 16:52:21')";

            if ($conn->query($addItem) === TRUE) {
                // echo "New addItem created successfully";
            } else {
                echo $conn->error;
            }
          }
        endforeach;
      }
    endforeach;
  endforeach;
?>
