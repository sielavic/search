    public function searchContact()
    {
        $text = $this->input->post('text');
        $favorite_contacts = [];
        if (empty($text)) {
            $users = '';
        } else {
            $all_users = \Entity\Employee::select('employee.*')->where('enabled', 1);


          $all_users->where(function ($q) use($text) {
                    $q->orWhere('employee.surname', 'LIKE', "%".$text."%");
                    $q->orWhere('employee.name', 'LIKE', "%".$text."%");
                    $q->orWhere('employee.middlename', 'LIKE', "%".$text."%");
                });

            $users =  $all_users->get();


//            $users = $all_users->filter(function ($item) use ($text) {
//                $name = '';
//                $surname = '';
//                $middlename = '';
//                $position = '';
//
//                if ($item->getPosition()) {
//                    $position = $item->getPosition();
//                    $position = mb_strtolower($position, 'UTF-8');
//                }
//
//                if ($item->surname) {
//                    $surname = $item->surname;
//                    $surname = mb_strtolower($surname, 'UTF-8');
//                }
//                if ($item->name) {
//                    $name = $item->name;
//                    $name = mb_strtolower(strip_tags(htmlspecialchars_decode($name)), 'UTF-8');
//                }
//                if ($item->middlename) {
//                    $middlename = $item->middlename;
//                    $middlename = mb_strtolower(strip_tags(htmlspecialchars_decode($middlename)), 'UTF-8');
//                }
//
//                return 0 !== preg_match('/' . $text . '/', $name) || 0 !== preg_match('/' . $text . '/', $surname) ||
//                    0 !== preg_match('/' . $text . '/', $middlename) || 0 !== preg_match('/' . $text . '/', $position);
//            });

        }

        if (!empty($users)) {
            foreach ($users as $user) {
                $user->object_type = 1;
            }
            $favorite_contacts = $users;
        }


        if (empty($text)) {
            $points = '';
        } else {
            $points = \Entity\Point::select('points.*')->where('status', 1);
            
            $points->where(function ($q) use($text) {
                $q->orWhere('points.name', 'LIKE', "%".$text."%");
                $q->orWhere('points.region', 'LIKE', "%".$text."%");
                $q->orWhere('points.city', 'LIKE', "%".$text."%");
                $q->orWhere('points.street', 'LIKE', "%".$text."%");
                $q->orWhere('points.building', 'LIKE', "%".$text."%");
            });

            $points =  $points->get();

//            $points = $points->filter(function ($item) use ($text) {
//                $name = '';
//                $region = '';
//                $city = '';
//                $street = '';
//                $building = '';
//
//                if ($item->region) {
//                    $region = $item->region;
//                    $region = mb_strtolower($region, 'UTF-8');
//                }
//                if ($item->name) {
//                    $name = $item->name;
//                    $name = mb_strtolower(strip_tags(htmlspecialchars_decode($name)), 'UTF-8');
//                }
//                if ($item->city) {
//                    $city = $item->city;
//                    $city = mb_strtolower(strip_tags(htmlspecialchars_decode($city)), 'UTF-8');
//                }
//                if ($item->street) {
//                    $street = $item->street;
//                    $street = mb_strtolower(strip_tags(htmlspecialchars_decode($street)), 'UTF-8');
//                }
//                if ($item->building) {
//                    $building = $item->building;
//                    $building = mb_strtolower(strip_tags(htmlspecialchars_decode($building)), 'UTF-8');
//                }
//
//                return 0 !== preg_match('/' . $text . '/', $name) || 0 !== preg_match('/' . $text . '/', $region) ||
//                    0 !== preg_match('/' . $text . '/', $city) || 0 !== preg_match('/' . $text . '/', $city)
//                    || 0 !== preg_match('/' . $text . '/', $street) || 0 !== preg_match('/' . $text . '/', $building);
//            });
        }


        if (!empty($points)) {
            foreach ($points as $point) {
                $point->object_type = 0;
                if (!empty($favorite_contacts)) {
                    $favorite_contacts->push($point);
                }
            }
            if (empty($favorite_contacts)) {
                $favorite_contacts = $points;
            }
        }


        if (!empty($text)) {
//            $this->getFavoriteContacs();
            $CI =& get_instance();

            ob_start();
            include './application/views/common/templates/phone_book_view.php';
            $result = ob_get_clean();

            echo json_encode(array('status' => 'success', 'result' => $result));
        }else{
            echo json_encode(array('status' => 'empty'));
        }
    }
